<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 10:52
 */

namespace app\admin\controller;

use app\common\library\JgPush;
use app\common\model\Message;
use app\common\model\UsdtChangelog;
use app\common\model\UsdtLog;
use controller\BasicAdmin;
use service\LogService;
use think\Db;

/**
 * Class UsdtTrade
 * USDT充值提币
 * @package app\admin\controller
 */
class UsdtTrade extends BasicAdmin
{
    /**
     * 列表
     * @return array|string
     */
    public function index() {
        // 设置页面标题
        $this->title = 'USDT充值提币列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = UsdtChangelog::alias('a')
            ->join('dw_users b', 'b.user_id=a.user_id')
            ->where(['a.type'=> 2]) // 只审核提币的
            ->field('a.*,b.user_name,b.true_name')
            ->order('status asc, a.changelog_id desc');
        // 应用搜索条件
        foreach (['user_id', 'type', 'wallet_address', 'coin_address'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                'user_id' == $key ? $db->where('a.'.$key, $get[$key]) :$db->where($key, $get[$key]);
            }
        }
        // 实例化并显示
        return parent::_list($db);
    }
    public function _data_filter(&$data) {
        if ($this->request->action() == 'index') {
            foreach ($data as &$v) {
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            }
        }
    }
    /**
     * 审核
     */
    public function examine() {
        // 修改post的值，0是启用，1是停用
        $logId = $this->request->post('changelog_id');
        $status = $this->request->post('status');
        if (!$logId || !$status || !in_array($status, [1,2])) {
            $this->error("参数错误");
        }
        $changeLog = UsdtChangelog::get($logId);
        if (!$changeLog) {
            $this->error("参数错误");
        }
        $user = $changeLog->user;
        Db::startTrans();
        try {
            // 修改状态
            $changeLog->save(['status'=> $status]);
            if ($status == 1) {
                // 审核通过
                if ($changeLog['type'] == 1) {
                    // 充值
                    $user->save([
                        'dw_usdt'=> bcadd($user->dw_usdt, $changeLog['usdt_num'], 4)
                    ]);
                    // 账单日志
                    $usdtLogId = UsdtLog::insertGetId([
                        'user_id'=> $user->user_id,
                        'log_content'=> 'USDT充值',
                        'usdt_charge_id'=> $logId,
                        'usdt_charge_type'=> 2, // 1 其他  2是充值提币
                        'type'=> 2,
                        'log_status'=> 2, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到
                        'chance_usdt'=> $changeLog['usdt_num'],
                        'dw_usdt'=> $user->dw_usdt,
                        'add_time'=> time(),
                    ]);
                    // 消息通知
                    Message::create([
                        'user_id'=> $user['user_id'],
                        'trigger_id'=> $usdtLogId,
                        'title'=> '您的充值已到账',
                        'content'=> "您已成功充值{$changeLog['usdt_num']}USDT",
                        'msg_type'=> 6,
                        'add_time'=> time(),
                    ]);
                    // 推送消息
                    JgPush::send($user['user_id'], "您的充值已到账");
                    // 系统日志
                    LogService::write('系统管理', 'USDT充值成功');
                } elseif ($changeLog['type'] == 2) {
                    // 账单日志
                    $usdtLogId = UsdtLog::insertGetId([
                        'user_id'=> $user['user_id'],
                        'log_content'=> 'USDT提币',
                        'usdt_charge_id'=> $logId,
                        'usdt_charge_type'=> 2, // 1 其他  2是充值提币
                        'type'=> 1,
                        'log_status'=> 2, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到
                        'chance_usdt'=> $changeLog['usdt_num'],
                        'dw_usdt'=> $user->dw_usdt,
                        'add_time'=> time(),
                    ]);
                    // 消息通知
                    Message::create([
                        'user_id'=> $user['user_id'],
                        'trigger_id'=> $usdtLogId,
                        'title'=> '您的提币已成功',
                        'content'=> "您已成功提币{$changeLog['usdt_num']}USDT",
                        'msg_type'=> 6,
                        'add_time'=> time(),
                    ]);
                    // 推送消息
                    JgPush::send($user['user_id'], "您的提币已成功");
                    // 提币
                    LogService::write('系统管理', 'USDT提币成功');
                }
            } else {
                // 审核没有通过
                $name = $changeLog->type == 1 ? '充值' : '提币';
                // 手续费
                $money = round(bcmul($changeLog['usdt_num'], 1.05, 6), 4);
                // 返还USDT
                $user->save([
                    'dw_usdt'=> bcadd($user->dw_usdt, $money, 4)
                ]);
                // 账单日志
                $usdtLogId = UsdtLog::insertGetId([
                    'user_id'=> $user->user_id,
                    'log_content'=> "USDT{$name}失败",
                    'usdt_charge_id'=> $logId,
                    'usdt_charge_type'=> 2, // 1 其他  2是充值提币
                    'type'=> 2,
                    'log_status'=> $changeLog['type'] == 1 ? 8 : 2, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到 8USDT充值
                    'chance_usdt'=> $money,
                    'dw_usdt'=> $user->dw_usdt,
                    'add_time'=> time(),
                ]);
                // 消息通知
                Message::create([
                    'user_id'=> $user['user_id'],
                    'trigger_id'=> $usdtLogId,
                    'title'=> "您的{$name}失败",
                    'content'=> "您{$name}的{$changeLog['usdt_num']}USDT没有通过",
                    'msg_type'=> 6,
                    'add_time'=> time(),
                ]);
                // 推送消息
                JgPush::send($user['user_id'], "您的{$name}失败");
                LogService::write('系统管理', "USDT{$name}不通过");
            }
            // 提交
            Db::commit();
            $commit = true;
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            $commit = false;
        }
        $commit && $this->success("操作成功！", '');
        $this->error("操作失败，请稍候再试！");
    }
}