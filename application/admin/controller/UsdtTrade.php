<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 10:52
 */

namespace app\admin\controller;

use app\common\model\UsdtChangelog;
use app\common\model\UsdtLog;
use controller\BasicAdmin;
use service\LogService;
use think\Db;

/**
 * Class UsdtTrade
 * USDT充值提现
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
        $this->title = 'USDT充值提现列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = UsdtChangelog::alias('a')
            ->join('dw_users b', 'b.user_id=a.user_id')
            ->field('a.*,b.user_name,b.true_name')
            ->order('status asc, a.changelog_id asc');
        // 应用搜索条件
        foreach (['user_id', 'type', 'wallet_address', 'coin_address'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $db->where($key, $get[$key]);
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
        Db::startTrans();
        try {
            // 修改状态
            $changeLog->save(['status'=> $status]);
            if ($status == 1) {
                // 审核通过
                if ($changeLog->type == 1) {
                    // 充值
                    $user = $changeLog->user;
                    $user->save([
                        'dw_usdt'=> bcadd($user->dw_usdt, $changeLog->usdt_num, 4)
                    ]);
                    // 账单日志
                    UsdtLog::create([
                        'user_id'=> $user->user_id,
                        'log_content'=> 'USDT充值',
                        'usdt_charge_id'=> $logId,
                        'usdt_charge_type'=> 2, // 1 其他  2是充值提现
                        'type'=> 2,
                        'chance_usdt'=> $changeLog->usdt_num,
                        'dw_usdt'=> $user->dw_usdt,
                        'add_time'=> time(),
                    ]);
                    // 系统日志
                    LogService::write('系统管理', 'USDT充值成功');
                } elseif ($changeLog->type == 2) {
                    // 提现
                    LogService::write('系统管理', 'USDT提现成功');
                }
            } else {
                $name = $changeLog->type == 1 ? '充值' : '提现';
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