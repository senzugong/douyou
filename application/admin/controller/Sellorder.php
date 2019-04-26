<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/13
 * Time: 16:11
 */

namespace app\admin\controller;

use app\common\library\JgPush;
use app\common\model\Message;
use app\common\model\UsdtChangelog;
use app\common\model\UsdtLog;
use app\common\model\UsdtMall;
use app\common\model\UsdtOrder;
use controller\BasicAdmin;
use think\Config;
use think\Db;

/**
 * Class Order
 * 场外交易订单管理
 * @package app\admin\controller
 */
class Sellorder extends BasicAdmin
{
    /**
     * 场外交易订单
     * @return array|string
     */
    public function index() {
        // 设置页面标题
        $this->title = '场外交易订单列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = UsdtOrder::alias('a')
            ->join('dw_users b', 'b.user_id=a.user_id', 'left')
            ->join('dw_users d', 'd.user_id=a.mall_user_id', 'left')
            ->join('dw_users f', 'f.user_id=b.invite_user', 'left')
            ->join('dw_usdt_mall e','e.mall_id = a.mall_id')
            ->join('dw_user_gathering c', 'c.gathering_id=a.gathering_id', 'left')
            ->where('e.type =2')
            ->field('a.*,b.user_name,b.true_name,d.user_name as mall_user,d.true_name as mall_true_user,c.gathering_name,c.gathering_img, f.true_name as invite_name')
            ->order('order_id desc');
        // 应用搜索条件
        foreach (['user_id', 'status', 'user_phone'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                'user_id' == $key || 'status' == $key ? $db->where('a.'.$key, $get[$key]) :$db->where('b.'.$key, $get[$key]);
            }
        }
        // 实例化并显示
        return parent::_list($db);
    }
    public function _data_filter(&$data) {
        if ($this->request->action() == 'index') {
            foreach ($data as $val) {
                $val['gathering_img'] = $val['gathering_img'] ? Config::get('image_url') . $val['gathering_img'] : '';
                $val['invite_name'] = $val['invite_name'] ?: '';
                $val['add_time'] = date("Y-m-d H:i:s",$val['add_time']);
            }
        }
    }

    /**
     * 订单申诉操作
     * @throws \think\exception\DbException
     */
    public function edit() {
        $status = $this->request->post('status');
        $order_id = $this->request->post('order_id');
        $order = UsdtOrder::get($order_id);
        if (!$order) {
            $this->error('订单不存在');
        }
        if ($status == 2) {
            // 完成订单
            Db::startTrans();
            try {
                // 出售的挂单，将币拨到下单人
                $orderUser = $order->orderUser;
                // 挂单用户
                $mallUser = $order->mallUser;
                $order->save(['status'=> 2]); // 完成订单
                $usdtMall = $order->usdtMall; // 发布单
                if ($usdtMall['type'] == 1) {
                    // 发布出售，币发给下单人
                    $orderUser->save(['dw_usdt' => bcadd($orderUser->dw_usdt, $order->order_usdt_num, 4)]);
                    // 确定充值订单
                    UsdtChangelog::where(['changelog_id'=> $order['changelog_id']])->update(['status'=> 1]);
                } else {
                    // 发布收购的单
                    $mallUser->save(['dw_usdt' => bcadd($mallUser->dw_usdt, $order->order_usdt_num, 4)]);
                }
                // USDT日志
                UsdtLog::create([
                    'user_id'=> $orderUser->user_id,
                    'log_content'=> '场外交易购买USDT',
                    'usdt_charge_id'=> $order['changelog_id'],
                    'usdt_charge_type'=> 2,
                    'type'=> 2, // 1 支出 2转入
                    'log_status'=> 8, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到 8USDT充值
                    'chance_usdt'=> $order->order_usdt_num,
                    'dw_usdt'=> $orderUser->dw_usdt,
                    'add_time'=> time(),
                ]);
                UsdtLog::create([
                    'user_id'=> $mallUser->user_id,
                    'log_content'=> '场外交易出售USDT',
                    'usdt_charge_type'=> 1,
                    'type'=> 1, // 1 支出 2转入
                    'log_status'=> 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到
                    'chance_usdt'=> $order->order_usdt_num,
                    'dw_usdt'=> $mallUser->dw_usdt,
                    'add_time'=> time(),
                ]);

                // 消息通知
                Message::create([
                    'user_id'=> $order['user_id'],
                    'trigger_id'=> $order['order_id'],
                    'title'=> "您的购买USDT订单已完成",
                    'content'=> "您的购买USDT订单（{$order['order_sn']}）已完成，赶紧去看看吧！",
                    'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                    'add_time'=> time(),
                ]);
                Message::create([
                    'user_id'=> $order['mall_user_id'],
                    'trigger_id'=> $order['order_id'],
                    'title'=> "您的购买USDT订单已完成",
                    'content'=> "您的购买USDT订单（{$order['order_sn']}）已完成，赶紧去看看吧！",
                    'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                    'add_time'=> time(),
                ]);
                // 推送消息
                JgPush::send($order['user_id'], '您的USDT订单已完成');
                JgPush::send($mallUser['user_id'], '您的USDT订单已完成');
                // 提交
                Db::commit();
                $result = true;
            } catch (\Exception $exception) {
                // 回滚
                Db::rollback();
                $result = false;
            }
            $result ? $this->success('完成订单') : $this->error('操作失败, 请稍候再试!');
        } elseif ($status == 4) {
            // 取消订单
            Db::startTrans();
            try {
                $mall = UsdtMall::get($order['mall_id']);
                // 取消订单退回usdt数量
                $usdt = bcsub($mall['over_usdt'], $order['order_usdt_num'], 4);
                // 取消订单
                $order->save(['status'=>4]);
                // 返还usdt到挂单
                $mall->save(['over_usdt' => $usdt]);
                if ($mall['status'] == 2 ){
                    if ($mall['over_usdt'] > 0){
                        $mall->save(['status' => 1]);
                    } else {
                        $mall->save(['status' =>0]);
                    }
                }
                // 出售的挂单，将币拨到下单人
                $orderUser = $order->orderUser;
                if ($mall['type'] == 1) {
                    // 取消充值订单
                    UsdtChangelog::where(['changelog_id'=> $order['changelog_id']])->update(['status'=> 2]);
                } else {
                    // 发布收购
                    $orderUser->save(['dw_usdt' => bcadd($orderUser->dw_usdt, $order->order_usdt_num, 4)]);
                }
                // 消息通知（发布用户）
                Message::create([
                    'user_id'=> $order['mall_user_id'],
                    'trigger_id'=> $order['order_id'],
                    'title'=> "您的USDT订单已被取消",
                    'content'=> "您的USDT订单（{$order['order_sn']}）已被取消，赶紧去看看吧！",
                    'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                    'add_time'=> time(),
                ]);
                // 消息通知（下单用户）
                Message::create([
                    'user_id'=> $order['user_id'],
                    'trigger_id'=> $order['order_id'],
                    'title'=> "您的USDT订单已被取消",
                    'content'=> "您的USDT订单（{$order['order_sn']}）已被取消，赶紧去看看吧！",
                    'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                    'add_time'=> time(),
                ]);
                // 推送消息
                JgPush::send($order['mall_user_id'], '您的USDT订单已被取消');
                JgPush::send($order['user_id'], '您的USDT订单已被取消');
                // 提交
                Db::commit();
                $result = true;
            } catch (\Exception $exception) {
                // 回滚
                Db::rollback();
                $result = false;
            }
            $result ? $this->success('取消订单') : $this->error('操作失败, 请稍候再试!');
        } else {
            $this->error('操作错误');
        }
    }
}