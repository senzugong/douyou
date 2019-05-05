<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 14:00
 */

namespace app\api\controller;
use app\api\validate\MallValidate;
use app\common\library\JgPush;
use app\common\model\UsdtChangelog;
use app\common\model\UsdtLog;
use app\common\model\UsdtMall;
use app\common\model\User;
use app\common\model\UsdtOrder;
use app\common\model\UserBank;
use app\common\model\UserGathering;
use app\common\model\UserWallet;
use app\common\model\WalletType;
use app\common\model\Message;
use controller\BasicApi;
use think\Config;
use think\Db;
use think\Request;

/**
 * Class UsdtMall场外交易控制器
 * @package app\api\controller
 */
class Mall extends BasicApi
{
    /**
     * 发布列表
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function mall(Request $request){
        // 分页
        $page = $request->param('page', 1);
        // 获取发布的记录
        $list['mall_list'] =UsdtMall::alias('a')
            ->join('dw_users b', 'a.user_id = b.user_id')
            ->where("a.status IN (0,1)")
            ->where(['a.type'=> 1]) // 发布类型
            ->field('b.user_name,b.user_avatar,a.*')
            ->order('a.add_time desc')
            ->page($page)
            ->select();
        foreach($list['mall_list'] as &$v){
            $v['user_avatar'] = Config::get('image_url').$v['user_avatar'];
            $v['surplus_usdt'] = bcsub($v['usdt_num'],$v['over_usdt'],4);
        }
        $list['mall_count'] =  UsdtMall::where("status IN (0,1)")->where("type = 1")->count();
        return $this->response($list);
    }

    /**
     * 购买下单 (我要购买  我要出售)
     * @param Request $request
     * @param MallValidate $mallValidate
     * @return \think\Response
     * @throws \think\exception\DbException
     */
    public function shopping(Request $request,MallValidate $mallValidate)
    {
        $result = $this->get_open();
        if(!$result){
            return $this->response('已休市!',304);
        }
        // 验证数据
        if (!$mallValidate->scene('shopping')->check($request->post())) {
            return  $this->response( $mallValidate->getError() ,304);
        }
        $mall_id = $request->post('mall_id');
        $money = $request->post('money');
        $userInfo = $request->userInfo;
//        if($userInfo['is_examine'] !=1){
//            return $this->response('未完成高级认证!', 304);
//        }
        if(!$userInfo['pay_password']){
            return $this->response('请先设置支付密码!', 304);
        }
        // 发布单
        $usdtMall = UsdtMall::get($mall_id);
        $success_usdt = '0.0000';
        $list = Db::table('dw_usdt_order')->where("mall_id = $mall_id and status IN (0,1,2,3)")->select();
        foreach($list as &$v){
            $success_usdt = bcadd($success_usdt, $v['order_usdt_num'], 2);
        }
        if($success_usdt >= $usdtMall['usdt_num']){
            return $this->response('数量不足，请勿操作该单!', 304);
        }
        if($money > bcsub($usdtMall['usdt_num'],$usdtMall['over_usdt'],4))
        {
            return $this->response('剩余usdt库存不足!', 304);
        }
        // 订单号
        $order_sn = sha1($userInfo->user_id . $mall_id . $money . microtime());
        Db::startTrans();
        try {
            // 购买数量扣减
            $overNum = bcadd($usdtMall->over_usdt, $money, 4);
            $usdtMall->save([
                'over_usdt'=> $overNum,
                'status'=> $overNum >= $usdtMall->usdt_num ? 2 : 1,
            ]);
            // 购买就是充值USDT
//            $user_wallet = UserWallet::where(['user_id'=>$userInfo['user_id'], 'status'=> 1])->find();
            $coin = WalletType::where(['id'=>3])->find();
            $data = [
                'user_id'=>$userInfo['user_id'],
//                'wallet_address'=>$user_wallet['wallet_address'],
                'type'=>1,
                'usdt_num'=>$money,
                'coin'=>$coin['wallet_name'],
                // 'coin_num'=>$coin_num,
                'coin_address'=>$coin['coin_address'],
                // 'ratio'=>$ratio,
                'status'=>0,
                'add_time'=>time(),
            ];
            $changelog = UsdtChangelog::insertGetId($data);
            // 生成订单
            $usdtOrder = UsdtOrder::create([
                'order_sn' => $order_sn,
                'mall_id' => $mall_id,
                'mall_user_id' => $usdtMall['user_id'],
                'changelog_id' => $changelog,
                'user_id' => $userInfo->user_id,
                'order_money' => bcmul($money,$usdtMall['usdt_price'],2),
                'order_usdt_num' => $money,
                'gathering_id' => $request->post('gathering_id'),
                'pay_type' => $request->post('pay_type'),
                'add_time'=>time()
            ]);
            // 消息通知
            Message::create([
                'user_id'=> $usdtMall['user_id'],
                'trigger_id'=> $usdtOrder->order_id,
                'title'=> '您发布出售USDT已被下单',
                'content'=> "您发布出售USDT已被下单（{$order_sn}），赶紧去看看吧！",
                'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                'add_time'=> time(),
            ]);
            // 推送消息
            JgPush::send($usdtMall['user_id'], '您发布出售USDT已被下单');
            // 提交
            Db::commit();
            // 返回结果
            return $this->response(['order_id'=> $usdtOrder->order_id]);
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            // 返回结果
            return $this->response('购买失败', 305);
        }
    }

    /**
     * 确认付款
     * @param Request $request
     * @param MallValidate $mallValidate
     * @return \think\Response
     */
    public function confirm_pay(Request $request, MallValidate $mallValidate) {
        // 验证数据
        if (!$mallValidate->scene('confirm_pay')->check($request->post())) {
            return  $this->response( $mallValidate->getError() ,304);
        }
        $usdtOrder = UsdtOrder::get($request->post('order_id'));
        if ($usdtOrder['status'] >= 1) {
            return $this->response('已确定付款');
        }
        Db::startTrans();
        try {
            // 订单确定付款
            UsdtOrder::where(['order_id' => $request->post('order_id')])
                ->update(['status' => 1]);
            $usdtMall = $usdtOrder->usdtMall;
            // 确定付款的两个类型
            if ($usdtMall['type'] == 1) {
                // 发布是出售的，由下单人确认付款，通知发布人
                // 消息通知
                Message::create([
                    'user_id'=> $usdtOrder['mall_user_id'],
                    'trigger_id'=> $request->post('order_id'),
                    'title'=> "您发布出售USDT订单已付款",
                    'content'=> "您发布出售USDT订单（{$usdtOrder['order_sn']}）已付款，赶紧去看看吧！",
                    'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                    'add_time'=> time(),
                ]);
                // 推送消息
                JgPush::send($usdtOrder['mall_user_id'], '您发布出售USDT订单已付款');
            } elseif ($usdtMall['type'] == 2) {
                // 发布是收购的，由发布人确认付款，通知下单人
                // 消息通知
                Message::create([
                    'user_id'=> $usdtOrder['user_id'],
                    'trigger_id'=> $request->post('order_id'),
                    'title'=> "您下单出售USDT订单已付款",
                    'content'=> "您下单出售USDT订单（{$usdtOrder['order_sn']}）已付款，赶紧去看看吧！",
                    'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                    'add_time'=> time(),
                ]);
                // 推送消息
                JgPush::send($usdtOrder['user_id'], '您下单出售USDT订单已付款');
            }
            // 提交
            Db::commit();
            return $this->response();
        }catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('确认付款失败', 308);
        }
    }

    /**
     * 拨币
     * @param Request $request
     * @param MallValidate $mallValidate
     * @return \think\Response
     * @throws \think\exception\DbException
     */
    public function allocate(Request $request, MallValidate $mallValidate) {
        // 验证数据
        if (!$mallValidate->scene('allocate')->check($request->post())) {
            return  $this->response( $mallValidate->getError() ,304);
        }
        // 订单信息
        $order = UsdtOrder::get($request->post('order_id'));
        if ($order['status'] >= 2) {
            return $this->response('已拨币');
        }
        Db::startTrans();
        try {
            // 挂单
            $usdtMall = $order->usdtMall;
            if ($usdtMall['status'] == 2) {
                // 卖完的自动下架
                $notDealCount = UsdtOrder::where(['mall_id'=> $usdtMall['mall_id']])
                    ->where('status IN (0, 1, 3)')
                    ->count();
                if ($notDealCount <= 1) {
                    $usdtMall->save(['status'=> 3]);
                }
            }
            // 下单人
            $orderUser = $order->orderUser;
            // 发布人
            $mallUser = $order->mallUser;
            if ($usdtMall['type'] == 1) {
                // 发布是出售的，由发布人确认收款，通知下单人
                $orderUser->save([
                    'dw_usdt'=> bcadd($orderUser['dw_usdt'], $order['order_usdt_num'], 4)
                ]);
                // 下单用户USDT日志
                UsdtLog::create([
                    'user_id'=> $orderUser->user_id,
                    'log_content'=> '场外交易购买USDT',
                    'usdt_charge_id'=> $order['changelog_id'],
                    'usdt_charge_type'=> 2,
                    'type'=> 2, // 1 支出 2转入
                    'log_status'=> 8, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到 8USDT充值
                    'chance_usdt'=> $order['order_usdt_num'],
                    'dw_usdt'=> $orderUser['dw_usdt'],
                    'add_time'=> time(),
                ]);
                // 确定充值订单
                UsdtChangelog::where(['changelog_id'=> $order['changelog_id']])->update(['status'=> 1]);
                // 发布用户，写入交易日志
                UsdtLog::create([
                    'user_id'=> $mallUser['user_id'],
                    'log_content'=> '场外交易出售USDT',
                    'usdt_charge_type'=> 1,
                    'type'=> 1, // 1 支出 2转入
                    'log_status'=> 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到
                    'chance_usdt'=> $order['order_usdt_num'],
                    'dw_usdt'=> $mallUser['dw_usdt'],
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
                // 推送消息
                JgPush::send($order['user_id'], '您的购买USDT订单已完成');
            } elseif ($usdtMall['type'] == 2) {
                // 发布是收购的，由下单人确认收款，通知发布人
                $mallUser->save([
                    'dw_usdt'=> bcadd($mallUser['dw_usdt'], $order['order_usdt_num'], 4)
                ]);
                // 发布用户日志
                UsdtLog::create([
                    'user_id'=> $mallUser['user_id'],
                    'log_content'=> '场外交易收购USDT',
                    'usdt_charge_type'=> 1,
                    'type'=> 2, // 1 支出 2转入
                    'log_status'=> 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到
                    'chance_usdt'=> $order['order_usdt_num'],
                    'dw_usdt'=> $mallUser['dw_usdt'],
                    'add_time'=> time(),
                ]);
                // 下单用户USDT日志
                UsdtLog::create([
                    'user_id'=> $orderUser['user_id'],
                    'log_content'=> '场外交易出售USDT',
                    'usdt_charge_type'=> 2,
                    'type'=> 1, // 1 支出 2转入
                    'log_status'=> 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到 8USDT充值
                    'chance_usdt'=> $order['order_usdt_num'],
                    'dw_usdt'=> $orderUser['dw_usdt'],
                    'add_time'=> time(),
                ]);
                // 消息通知
                Message::create([
                    'user_id'=> $order['mall_user_id'],
                    'trigger_id'=> $order['order_id'],
                    'title'=> "您的收购USDT订单已完成",
                    'content'=> "您的收购USDT订单（{$order['order_sn']}）已完成，赶紧去看看吧！",
                    'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                    'add_time'=> time(),
                ]);
                // 推送消息
                JgPush::send($order['mall_user_id'], '您的收购USDT订单已完成');
            }
            // 订单完成
            $order->save(['status'=> 2]);
            // 提交
            Db::commit();
            return $this->response();
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('拨币失败', 306);
        }
    }

    /**
     * 申诉
     * @param Request $request
     * @param MallValidate $mallValidate
     * @return \think\Response
     */
    public function appeal(Request $request, MallValidate $mallValidate) {
        // 验证数据
        if (!$mallValidate->scene('appeal')->check($request->post())) {
            return  $this->response( $mallValidate->getError() ,304);
        }
        $status_msg = $request->post('status_msg');
        if(!$status_msg){
            return $this->response('申述理由必填',304);
        }
        Db::startTrans();
        try {
            $userInfo = $request->userInfo;
            // 将订单改成申诉
            UsdtOrder::where(['order_id'=> $request->post('order_id')])
                ->update(['status'=> 3,'status_msg'=>$status_msg,'state_time'=>time()]);
            $order = UsdtOrder::get($request->post('order_id'));
            $msg_user = $userInfo['user_id'] == $order['user_id'] ? $order['mall_user_id'] : $order['user_id'];
            // 消息通知通知对方
            Message::create([
                'user_id'=> $msg_user,
                'trigger_id'=> $order['order_id'],
                'title'=> "您的USDT订单已被申诉",
                'content'=> "您的USDT订单（{$order['order_sn']}）已被申诉，赶紧去看看吧！",
                'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                'add_time'=> time(),
            ]);
            // 推送消息
            JgPush::send($msg_user, '您的USDT订单已被申诉');
            // 提交
            Db::commit();
            return $this->response();
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('申诉失败', 306);
        }
    }

    /**
     * 我要发布（我要出售）
     * @param Request $request
     * @param MallValidate $mallValidate
     * @return \think\Response
     * @throws \think\exception\DbException
     */
    public function sell(Request $request,MallValidate $mallValidate)
    {
        $result = $this->get_open();
        if(!$result){
            return $this->response('已休市!',304);
        }
        // 验证数据
        if (!$mallValidate->scene('sell')->check($request->post())) {
            return  $this->response( $mallValidate->getError() ,304);
        }
        $userInfo = $request->userInfo;
//        if($userInfo['is_examine'] !=1){
//            return $this->response('未完成高级认证!', 304);
//        }
        if(!$userInfo['pay_password']){
            return $this->response('请先设置支付密码!', 304);
        }
        $count = UsdtMall::where("'user_id'={$userInfo['user_id']}  and status !=3")->count();
        if($count >=2 ){
            return $this->response('当前只能发布两条数据', 304);
        }

        $usdt_num = $request->post('usdt_num');
        if($usdt_num <1){
            return $this->response('出售usdt数量最低为1', 304);
        }
        $usdt_price = $request->post('usdt_price',6.70); //usdt单价
        $mix_rmb = $request->post('mix_rmb',$usdt_price); //最小的交易金额
        $max_rmb = $request->post('max_rmb',bcmul($usdt_num,$usdt_price,2)); //最大的交易金额
        if($mix_rmb >= $max_rmb){
            return $this->response('最大交易额小于等于最小交易额!', 304);
        }
        if($max_rmb > bcmul($usdt_num,$usdt_price,2)){
            return $this->response('超过了最大限额!', 304);
        }

        $wx_gathering = $request->post('wx_gathering',''); //微信id
        $bank_gathering = $request->post('bank_gathering',''); //银行id
        $zfb_gathering = $request->post('zfb_gathering',''); //支付宝id
        if(!$wx_gathering && !$bank_gathering && !$zfb_gathering){
            return $this->response('支付方式必选一个', 304);
        }
        Db::startTrans();
        try {
            // 发布挂单
            UsdtMall::create([
                'usdt_num' => $usdt_num,
                'usdt_price' => $usdt_price,
                'mix_rmb' => $mix_rmb,
                'max_rmb' => $max_rmb,
                'user_id' => $userInfo['user_id'],
                'wx_gathering' => $wx_gathering,
                'bank_gathering' => $bank_gathering,
                'zfb_gathering' => $zfb_gathering,
                'add_time'=>time(),
            ]);
            // 扣除usdt
            $userInfo->save([
                'dw_usdt'=> bcsub($userInfo->dw_usdt, bcmul($usdt_num,1.02,4), 4),
            ]);
            // USDT日志
            UsdtLog::create([
                'user_id' => $userInfo->user_id,
                'log_content' => '场外交易出售USDT',
                'usdt_charge_type' => 1,
                'type' => 1, // 1 支出 2转入
                'log_status'=> 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到
                'chance_usdt' =>  bcmul($usdt_num,1.02,4),
                'dw_usdt' => $userInfo->dw_usdt,
                'add_time' => time(),
            ]);
            // 提交
            Db::commit();
            return $this->response();
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('发布失败', 306);
        }
    }

    /**
     * 我的发布
     * @param Request $request
     * @return \think\Response
     */
    public function my_sell(Request $request) {
        // 分页
        $page = $request->post('page', 1);
        // 状态
        $status = $request->post('status', 1);
        $userInfo = $request->userInfo;
        // 状态调整
        if ($status == 1) {
            // 上架中
            $status = " status != 3 ";
        } else {
            // 下架
            $status = " status = 3";
        }
        // 发布的挂单
        $list['usdtMall'] = $userInfo->usdtMall()
            ->where($status)
            ->page($page)
            ->order('add_time desc')
            ->select();
        $list['count']  = $userInfo->usdtMall()
            ->where($status)
            ->count();
        return $this->response($list);
    }

    /**
     * 我的订单列表（）
     * @param Request $request
     * @param MallValidate $mallValidate
     * @return \think\Response
     * @throws \think\exception\DbException
     */
    public function order_list(Request $request)
    {
        $userInfo = $request->userInfo;
        $type  = $request->post('type',1);//1交易中 2申述中 3已取消 4全部记录
        $page  = $request->post('page',1);//分页
        $where = [];
        if($type == 1){
            $where = "a.status IN (0,1)";
        }elseif($type == 2){
            $where = "a.status = 3";
        }elseif($type ==3){
            $where = "a.status = 4";
        }
        $order_list = UsdtOrder::alias('a')
            ->join('dw_users b','b.user_id = a.user_id')
            ->join('dw_users c','c.user_id = a.mall_user_id')
            ->join('dw_usdt_mall d','d.mall_id = a.mall_id')
            ->where($where)
            ->where(function ($query) use ($userInfo) {
                $query->where(['a.user_id' => $userInfo['user_id']])
                    ->whereOr(['a.mall_user_id'=> $userInfo['user_id']]);
            })
            ->field('b.user_name as order_user_name, b.user_avatar as order_user_avatar,
             c.user_name as mall_user_name, c.user_avatar as mall_user_avatar, a.*')
            ->page($page)
            ->order('a.add_time desc')
            ->select();
        foreach($order_list as &$v){
            $v['order_user_avatar'] = Config::get('image_url').$v['order_user_avatar'];
            $v['mall_user_avatar'] = Config::get('image_url').$v['mall_user_avatar'];
            $user_mall = UsdtMall::where(['mall_id'=>$v['mall_id']])->find();
            $v['usdt_price'] = $user_mall['usdt_price'];
            $v['mall_type'] = $user_mall['type']; // 发布的是出售还是收购
            if($userInfo['user_id'] == $user_mall['user_id']){
                $v['is_mall_user'] = 1;
            }else{
                $v['is_mall_user'] = 0;
            }
        }
        return $this->response($order_list);

    }

    /**
     * 取消发布的数据(我要购买的数据)
     * @param Request $request
     * @param MallValidate $mallValidate
     * @return \think\Response
     * @throws \think\exception\DbException
     */
    public function cancel_mall(Request $request,MallValidate $mallValidate)
    {
        // 验证数据
        if (!$mallValidate->scene('mall')->check($request->post())) {
            return  $this->response( $mallValidate->getError() ,304);
        }
        $userInfo = $request->userInfo;
        $mall_id  = $request->post('mall_id');//发布的id
        $mall_info = UsdtMall::get($mall_id);
        if ($mall_info['type'] == 2) {
            // 下架发布收购的单
            return $this->cancel_sell($userInfo, $mall_info);
        }
        if($mall_info['status'] == 0){
            Db::startTrans();
            try{
                // 挂卖下架
                $mall_info->save(['status'=>3]);
                // 用户USDT余额
                $userInfo->save([
                    'dw_usdt'=>bcadd($userInfo['dw_usdt'], bcmul($mall_info['usdt_num'],1.02,4), 4)
                ]);
                // USDT日志
                UsdtLog::create([
                    'user_id' => $userInfo->user_id,
                    'log_content' => '场外交易-下架出售USDT',
                    'usdt_charge_type' => 1,
                    'type' => 2, // 1 支出 2转入
                    'log_status'=> 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到
                    'chance_usdt' => bcmul($mall_info['usdt_num'],1.02,4),
                    'dw_usdt' => $userInfo->dw_usdt,
                    'add_time' => time(),
                ]);
                Db::commit();
                return $this->response();
            }catch (\Exception $exception){
                Db::rollback();
                return $this->response('取消失败!',304);
            }
        }elseif($mall_info['status'] == 1){//部分成交
            $orderException = UsdtOrder::where("mall_id='$mall_id' and mall_user_id='{$userInfo['user_id']}' and status in (1,3)")->count();
            if ($orderException > 0) {
                return $this->response('订单存在异常!',307);
            }
            // 订单列表（待付款的订单取消）
            $orderList = UsdtOrder::where("mall_id='$mall_id' and mall_user_id='{$userInfo['user_id']}' and status = 0")
                ->field('order_id, user_id, order_sn, changelog_id, order_usdt_num')
                ->select();
            Db::startTrans();
            try{
                // 取消所有未确定付款的订单
                $cancelUsdt = 0;
                foreach ($orderList as $item) {
                    $cancelUsdt = bcadd($cancelUsdt, $item['order_usdt_num'], 4);
                    // 取消充值订单
                    UsdtChangelog::where(['changelog_id'=> $item['changelog_id']])->update(['status'=> 2]);
                    // 消息通知
                    Message::create([
                        'user_id'=> $item['user_id'],
                        'trigger_id'=> $item['order_id'],
                        'title'=> "您的USDT订单已被取消",
                        'content'=> "您的USDT订单（{$item['order_sn']}）已被取消，赶紧去看看吧！",
                        'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                        'add_time'=> time(),
                    ]);
                    // 推送消息
                    JgPush::send($item['user_id'], '您的USDT订单已被取消');
                }
                // 退回订单USDT
                $over_usdt = bcsub($mall_info['over_usdt'], $cancelUsdt, 4);
                // 账单剩余USDT
                $usdt_mall = bcsub($mall_info['usdt_num'], $over_usdt,4);
                // 没有确认支付的订单取消其订单
                UsdtOrder::where(['mall_id'=>$mall_id,'status'=>0])->update(['status'=>4]);
                // 挂卖下架
                $mall_info->save([
                    'status'=>3,
                    'over_usdt'=>$over_usdt,
                ]);
                // 用户USDT余额
                $userInfo->save([
                    'dw_usdt'=> bcadd($userInfo->dw_usdt, bcmul($usdt_mall,1.02,4), 4)
                ]);
                // USDT日志
                UsdtLog::create([
                    'user_id' => $userInfo->user_id,
                    'log_content' => '场外交易-下架出售USDT',
                    'usdt_charge_type' => 1,
                    'type' => 2, // 1 支出 2转入
                    'log_status'=> 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到
                    'chance_usdt' =>bcmul($usdt_mall,1.02,4),
                    'dw_usdt' => $userInfo->dw_usdt,
                    'add_time' => time(),
                ]);
                Db::commit();
                return $this->response();
            }catch (\Exception $exception){
                Db::rollback();
                return $this->response('取消失败!',304);
            }
        }

        return $this->response('订单不可取消',304);
    }

    /**
     * 下架发布的收购单
     * @param User $userInfo
     * @param UsdtMall $mall_info
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function cancel_sell(User $userInfo, UsdtMall $mall_info) {
        // 判断是否能够取消
        $orderException = UsdtOrder::where("mall_id='{$mall_info['mall_id']}' and mall_user_id='{$userInfo['user_id']}' and status in (1,3)")->count();
        if ($orderException > 0) {
            return $this->response('订单存在异常!', 307);
        }
        // 订单列表（待付款的订单取消）
        $orderList = UsdtOrder::where("mall_id='{$mall_info['mall_id']}' and mall_user_id='{$userInfo['user_id']}' and status = 0")
            ->field('order_id, user_id, order_sn, changelog_id, order_usdt_num')
            ->select();
        // 下架发布收购
        Db::startTrans();
        try {
            // 取消所有未确定付款的订单
            $cancelUsdt = 0;
            foreach ($orderList as $item) {
                $cancelUsdt = bcadd($cancelUsdt, $item['order_usdt_num'], 4);
                // 收购订单需要返还USDT给对方
                $orderUser = User::get($item['user_id']);
                $orderUser->save([
                    'dw_usdt'=> bcadd($orderUser['dw_usdt'], $item['order_usdt_num'], 4),
                ]);
                // USDT日志
                UsdtLog::create([
                    'user_id'=> $orderUser['user_id'],
                    'log_content'=> '场外交易出售USDT取消',
                    'usdt_charge_id'=> $item['changelog_id'],
                    'usdt_charge_type'=> 2,
                    'type'=> 2, // 1 支出 2转入
                    'log_status'=> 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到 8USDT充值
                    'chance_usdt'=> $item['order_usdt_num'],
                    'dw_usdt'=> $orderUser['dw_usdt'],
                    'add_time'=> time(),
                ]);
                // 消息通知
                Message::create([
                    'user_id'=> $item['user_id'],
                    'trigger_id'=> $item['order_id'],
                    'title'=> "您的USDT订单已被取消",
                    'content'=> "您的USDT订单（{$item['order_sn']}）已被取消，赶紧去看看吧！",
                    'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                    'add_time'=> time(),
                ]);
                // 推送消息
                JgPush::send($item['user_id'], '您的USDT订单已被取消');
            }
            // 没有确认支付的订单取消其订单
            UsdtOrder::where(['mall_id'=>$mall_info['mall_id'],'status'=>0])->update(['status'=>4]);
            // 退回订单USDT
            $over_usdt = bcsub($mall_info['over_usdt'], $cancelUsdt, 4);
            // 挂卖下架
            $mall_info->save([
                'status'=>3,
                'over_usdt'=>$over_usdt,
            ]);
            // 提交
            Db::commit();
            return $this->response();
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('下架失败', 306);
        }
    }

    /**
     * 重新上架挂单
     * @param Request $request
     * @param MallValidate $mallValidate
     * @return \think\Response
     * @throws \think\exception\DbException
     */
    public function again_mall(Request $request, MallValidate $mallValidate) {
        // 验证数据
        if (!$mallValidate->scene('again_mall')->check($request->post())) {
            return  $this->response( $mallValidate->getError() ,304);
        }
        // 用户信息
        $userInfo = $request->userInfo;
        // 挂单
        $usdtMall = UsdtMall::get($request->post('mall_id'));
        Db::startTrans();
        try {
            // 上架
            $usdtMall->save([
                'status'=> $usdtMall->over_usdt == 0 ? 0 : 1,
            ]);
            if ($usdtMall['type'] == 1) {
                // 出售usdt
                // 扣除Usdt
                $usdt_num = bcsub($usdtMall['usdt_num'], $usdtMall['over_usdt'], 4);
                $userInfo->save([
                    'dw_usdt' => bcsub($userInfo->dw_usdt, bcmul($usdt_num, 1.02, 4), 4)
                ]);
                // USDT日志
                UsdtLog::create([
                    'user_id' => $userInfo->user_id,
                    'log_content' => '场外交易-上架出售USDT',
                    'usdt_charge_type' => 1,
                    'type' => 1, // 1 支出 2转入
                    'log_status' => 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到
                    'chance_usdt' => bcmul($usdt_num, 1.02, 4),
                    'dw_usdt' => $userInfo->dw_usdt,
                    'add_time' => time(),
                ]);
            }
            // 提交
            Db::commit();
            return $this->response();
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('重新上架失败', 306);
        }
    }

    /**
     * @param Request $request 订单取消
     * @param MallValidate $mallValidate
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function cancel_order(Request $request,MallValidate $mallValidate){
        // 验证数据
        if (!$mallValidate->scene('cancel_order')->check($request->post())) {
            return  $this->response( $mallValidate->getError() ,304);
        }
        $userInfo = $request->userInfo;
        // 订单数据
        $order = UsdtOrder::get($request->post('order_id'));
        $mall = UsdtMall::get($order['mall_id']);
        Db::startTrans();
        try{
            // 取消订单退回usdt数量
            $usdt = bcsub($mall['over_usdt'], $order['order_usdt_num'], 4);
            // 取消订单
            $order->save(['status'=>4]);
            // 返还usdt到挂单
            $mall->save(['over_usdt' => $usdt]);
            if($mall['over_usdt'] >0){
                $mall->save([ 'status'=> 1]);
            }else{
                $mall->save([ 'status'=>0]);
            }
            if ($mall['type'] == 1) {
                // 发布是出售的
                // 取消充值订单
                UsdtChangelog::where(['changelog_id'=> $order['changelog_id']])->update(['status'=> 2]);
            } elseif ($mall['type'] == 2) {
                // 发布是收购的，将USDT退还给下单用户
                $orderUser = User::get($order['user_id']);
                $orderUser->save([
                    'dw_usdt'=> bcadd($orderUser['dw_usdt'], $order['order_usdt_num'], 4),
                ]);
                // USDT日志
                UsdtLog::create([
                    'user_id'=> $orderUser['user_id'],
                    'log_content'=> '场外交易出售USDT取消',
                    'usdt_charge_type'=> 2,
                    'type'=> 2, // 1 支出 2转入
                    'log_status'=> 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到 8USDT充值
                    'chance_usdt'=> $order['order_usdt_num'],
                    'dw_usdt'=> $orderUser['dw_usdt'],
                    'add_time'=> time(),
                ]);
            }
            // 消息通知对方
            $cancelUser = $userInfo['user_id'] == $order['user_id'] ? $order['mall_user_id'] : $order['user_id'];
            Message::create([
                'user_id'=> $cancelUser,
                'trigger_id'=> $order['order_id'],
                'title'=> "您的USDT订单已被取消",
                'content'=> "您的USDT订单（{$order['order_sn']}）已被取消，赶紧去看看吧！",
                'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                'add_time'=> time(),
            ]);
            // 推送消息
            JgPush::send($cancelUser, '您的USDT订单已被取消');
            // 提交数据
            Db::commit();
            return $this->response();
        }catch (\Exception $exception){
            // 回滚
            Db::rollback();
            return $this->response('取消失败!',304);
        }
    }

    /**
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detail_order(Request $request){
        $order_id =$request->post('order_id');
        if(!$order_id){
            return $this->response('订单id不能为空!',304);
        }
        $detail_order = UsdtOrder::where(['order_id'=>$order_id])->find();
        $detail_order['add_time'] = date('Y-m-d H:i:s',$detail_order['add_time']);
        $detail_order['usdt_price'] = UsdtMall::where(['mall_id'=>$detail_order['mall_id']])->value('usdt_price');
        return $this->response($detail_order);

    }
    /**
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detail_require(Request $request){
        $order_id =$request->post('order_id');
        if(!$order_id){
            return $this->response('订单id不能为空!',304);
        }
        $userInfo = $request->userInfo;
        $list['detail_order'] = UsdtOrder::where(['order_id'=>$order_id])->find();
        $list['detail_order']['cancel_time'] = ($list['detail_order']['add_time'] +900)-time();
        $list['detail_order'] ['add_time'] = date('Y-m-d H:i:s',$list['detail_order'] ['add_time']);
        if($list['detail_order']['state_time']){
            $list['detail_order'] ['state_time'] = date('Y-m-d H:i:s',$list['detail_order'] ['state_time']);
        }
        $list['mall_info'] = UsdtMall::where(['mall_id'=>$list['detail_order']['mall_id']])->find();
        if($list['detail_order']['pay_type'] ==1){

            $list ['pay_info'] =UserBank::where(['bank_id'=> $list['detail_order']['gathering_id']])->find();
        }else{
            $list ['pay_info'] =UserGathering::where(['gathering_id'=>$list['detail_order']['gathering_id']])->find();
            if($list ['pay_info']){
                $list['pay_info']['gathering_name'] = substr_replace($list['pay_info']['gathering_name'],'****',3,4);
                $list['pay_info']['gathering_img'] = $list['pay_info']['gathering_img']  ?Config::get('image_url').$list['pay_info']['gathering_img'] :'';
            }
        }

        if($userInfo['user_id'] == $list['detail_order']['mall_user_id']){
            $list['user_info'] = User::where(['user_id'=>$list['detail_order']['user_id']])->field('user_name,user_avatar')->find();
            $list['user_info']['user_avatar'] = $list['user_info']['user_avatar']  ?Config::get('image_url').$list['user_info']['user_avatar'] :'';
        }else{
            $list['user_info'] = User::where(['user_id'=>$list['detail_order']['mall_user_id']])->field('user_name,user_avatar')->find();
            $list['user_info']['user_avatar'] = $list['user_info']['user_avatar']  ?Config::get('image_url').$list['user_info']['user_avatar'] :'';
        }
        if($userInfo['user_id'] ==$list['mall_info']['user_id']){
            $list['user_info']['is_mall_user'] = 1;
        }else{
            $list['user_info']['is_mall_user'] = 1;
        }
        return $this->response($list);

    }
    /**默认支付方式
     * @param Request $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function default_payment(Request $request){
        $userInfo = $request->userInfo;
        $list['usdt_price'] = Db::table('dw_usdt_price')->value('usdt_price');
        $list['bank'] = UserBank::where(['user_id'=>$userInfo['user_id'],'status'=>1])->find();
        $list['wx_pay'] = UserGathering::where(['user_id'=>$userInfo['user_id'],'type'=>2,'status'=>1])->find();
        $list['zfb_pay'] = UserGathering::where(['user_id'=>$userInfo['user_id'],'type'=>3,'status'=>1])->find();
        $list['sell_usdt_price'] = 6.5;
        if($list['zfb_pay']){
            $list['zfb_pay']['gathering_name'] = substr_replace($list['zfb_pay']['gathering_name'],'****',3,4);
        }

        return $this->response($list);
    }
    //是否休市
    public function get_open(){
        $week = date("w");//今天周几
        if($week == 0){
            $list = Db::table('dw_basic_opentime')->where(['id'=>7])->find();
        }else{
            $list = Db::table('dw_basic_opentime')->where(['id'=>$week])->find();
        }
        $time = time();
        $start_time = strtotime(date('Y-m-d').' '.$list['start_time']);

        $end_time = strtotime(date('Y-m-d').' '.$list['end_time']);
        if($time>$start_time && $time< $end_time){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 开休市时间
     * @param Request $request
     */
    public function open_time(Request $request){
        $result['is_open'] = $this->get_open();
        $week = date("w");//今天周几
        if($week == 0){
            $result['week'] = Db::table('dw_basic_opentime')->where(['id'=>7])->find();
        }else{
            $result['week'] = Db::table('dw_basic_opentime')->where(['id'=>$week])->find();
        }
        return $this->response($result);
    }
    /**
     * 获取付款信息
     * @param Request $request
     * @return \think\Response
     * @throws \think\exception\DbException
     */
    public function gain_info(Request $request) {
        $type_id = $request->post('type_id');
        $gathering_id = $request->post('gathering_id');

        switch ($type_id) {
            case 1:
                // 银行卡
                $bank = UserBank::get($gathering_id);
                break;
            case 2:
                // 微信
                $bank = UserGathering::get($gathering_id);
                $bank['gathering_img'] = Config::get('image_url') . $bank['gathering_img'];
                break;
            case 3:
                // 支付宝
                $bank = UserGathering::get($gathering_id);
                $bank['gathering_img'] = Config::get('image_url') . $bank['gathering_img'];
                break;
            default:
                return $this->response('获取类型错误', 306);
                break;
        }
        // 订单时间
        $bank['order_time'] = UsdtOrder::where(['order_id'=> $request->post('order_id')])->value('add_time');
        return $this->response($bank);
    }
}