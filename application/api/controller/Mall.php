<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 14:00
 */

namespace app\api\controller;
use app\api\validate\MallValidate;
use app\common\model\UsdtLog;
use app\common\model\UsdtMall;
use app\common\model\User;
use app\common\model\UsdtOrder;
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
        $type = $request->param('type', 1);  // 1是我要购买 2是我要出售
        // 获取发布的记录
        $list =UsdtMall::alias('a')
            ->join('dw_users b', 'a.user_id = b.user_id')
            ->where("a.type = $type and a.status IN (0,1)")
            ->field('b.user_name,b.user_avatar,a.*')
            ->order('a.usdt_price asc')
            ->page($page)
            ->select();
        foreach($list as &$v){
            $v['user_avatar'] = Config::get('image_url').$v['user_avatar'];
        }
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
        // 验证数据
        if (!$mallValidate->scene('shopping')->check($request->post())) {
            return  $this->response( $mallValidate->getError() ,304);
        }
        $mall_id = $request->post('mall_id');
        $money = $request->post('money');
        $userInfo = $request->userInfo;
        // 发布单
        $usdtMall = UsdtMall::get($mall_id);
        $usdtNum = 0;
        // 订单号
        $order_sn = sha1($userInfo->user_id . $mall_id . $money . microtime());
        Db::startTrans();
        try {
            // 购买数量扣减
            $overNum = bcadd($usdtMall->over_usdt, $usdtNum, 4);
            $usdtMall->save([
                'over_usdt'=> $overNum,
                'status'=> $overNum >= $usdtMall->usdt_num ? 2 : 1,
            ]);
            // 发布是收购usdt，要扣除用户usdt
            if ($usdtMall['type'] == 2) {
                $userInfo->save(['dw_usdt'=> bcsub($userInfo->dw_usdt, $overNum, 4)]);
                // USDT日志
                UsdtLog::create([
                    'user_id'=> $userInfo->user_id,
                    'log_content'=> '场外交易出售USDT',
                    'usdt_charge_type'=> 1,
                    'type'=> 1, // 1 支出 2转入
                    'chance_usdt'=> $overNum,
                    'dw_usdt'=> $userInfo->dw_usdt,
                    'add_time'=> time(),
                ]);
            }
            // 生成订单
            $usdtOrder = UsdtOrder::create([
                'order_sn' => $order_sn,
                'mall_id' => $mall_id,
                'mall_user_id' => $usdtMall->user_id,
                'user_id' => $userInfo->user_id,
                'order_money' => $money,
                'order_usdt_num' => $usdtNum,
                'gathering_id' => $request->post('gathering_id'),
                'pay_type' => $request->post('pay_type'),
            ]);
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
        Db::startTrans();
        try {
            // 订单确定付款
            UsdtOrder::where(['order_id' => $request->post('order_id')])
                ->update(['status' => 1]);
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
        Db::startTrans();
        try {
            // 挂单信息
            $usdtMall = $order->usdtMall;
            if ($usdtMall['type'] == 1) {
                // 出售的挂单，将币拨到下单人
                $orderUser = $order->orderUser;
                $orderUser->save(['dw_usdt'=> bcadd($orderUser->dw_usdt, $order->order_usdt_num, 4)]);
                // USDT日志
                UsdtLog::create([
                    'user_id'=> $orderUser->user_id,
                    'log_content'=> '场外交易收购USDT',
                    'usdt_charge_type'=> 1,
                    'type'=> 2, // 1 支出 2转入
                    'chance_usdt'=> $order->order_usdt_num,
                    'dw_usdt'=> $orderUser->dw_usdt,
                    'add_time'=> time(),
                ]);
            } elseif ($usdtMall['type'] == 2) {
                // 收购的挂单，将币拨到挂单人
                $mallUser = $order->mallUser;
                $mallUser->save(['dw_usdt'=> bcadd($mallUser->dw_usdt, $order->order_usdt_num, 4)]);
                // USDT日志
                UsdtLog::create([
                    'user_id'=> $mallUser->user_id,
                    'log_content'=> '场外交易收购USDT',
                    'usdt_charge_type'=> 1,
                    'type'=> 2, // 1 支出 2转入
                    'chance_usdt'=> $order->order_usdt_num,
                    'dw_usdt'=> $mallUser->dw_usdt,
                    'add_time'=> time(),
                ]);
            } else {
                throw new \Exception('错误的订单');
            }
            // 拨币成功，将订单改成已完成
            // 0代付款  1已付款（待放币） 2成功 （已付款已方币） 3申述中（已支付完成 未放币）  4取消
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
        Db::startTrans();
        try {
            // 将订单改成申诉
            UsdtOrder::where(['order_id'=> $request->post('order_id')])
                ->update(['status'=> 3]);
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
        // 验证数据
        if (!$mallValidate->scene('sell')->check($request->post())) {
            return  $this->response( $mallValidate->getError() ,304);
        }
        $userInfo = $request->userInfo;
        $type  = $request->post('type');
        $count = UsdtMall::where("'user_id'=>{$userInfo['user_id']}  and type =$type and status !=3")->count();
        if($count >=2 ){
            return $this->response('当前只能发布两条数据', 304);
        }
        $usdt_num = $request->post('usdt_num');
        $type  = $request->post('type');
        $usdt_price = $request->post('usdt_price'); //usdt单价
        $mix_rmb = $request->post('mix_rmb'); //最小的交易金额
        $max_rmb = $request->post('max_rmb',bcmul($usdt_num,$usdt_price,4)); //最大的交易金额
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
                'type' => $type,
                'user_id' => $userInfo['user_id'],
                'wx_gathering' => $wx_gathering,
                'bank_gathering' => $bank_gathering,
                'zfb_gathering' => $zfb_gathering,

            ]);
            // 用户出售USDT
            if ($type == 1) {
                // 扣除usdt
                $userInfo->save([
                    'dw_usdt'=> bcsub($userInfo->dw_usdt, $usdt_num, 4),
                ]);
                // USDT日志
                UsdtLog::create([
                    'user_id' => $userInfo->user_id,
                    'log_content' => '场外交易出售USDT',
                    'usdt_charge_type' => 1,
                    'type' => 1, // 1 支出 2转入
                    'chance_usdt' => $usdt_num,
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
            $status = ['!=', 4];
        } else {
            // 下架
            $status = 4;
        }
        // 发布的挂单
        $usdtMall = $userInfo->usdtMall()
            ->where(['status'=> $status])
            ->page($page)
            ->select();
        return $this->response($usdtMall);
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
            $where = "a.status IN (1,2)";
        }elseif($type == 2){
            $where = "a.status = 3";
        }elseif($type ==3){
            $where = "a.status = 4";
        }
        $order_list = UsdtOrder::alias('a')
            ->join('dw_users b','b.user_id = a.user_id')
            ->join('dw_users c','c.user_id = a.mall_user_id')
            ->where($where)
            ->where(function ($query) use ($userInfo) {
                $query->where(['a.user_id' => $userInfo['user_id']])
                    ->whereOr(['a.mall_user_id'=> $userInfo['user_id']]);
            })
            ->field('b.user_name as order_user_name, b.user_avatar as order_user_avatar,
             c.user_name as mall_user_name, c.user_avatar as mall_user_avatar, a.*')
            ->page($page)
            ->select();
        foreach($order_list as &$v){
            $v['order_user_avatar'] = Config::get('image_url').$v['order_user_avatar'];
            $v['mall_user_avatar'] = Config::get('image_url').$v['mall_user_avatar'];
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
        $type  = $request->post('type',1);//发布卖出  我要收购
        $mall_info = UsdtMall::get($mall_id);
        if($type == 1){
            if($mall_info['status'] == 0){
                Db::startTrans();
                try{
                    // 挂卖下架
                    $mall_info->save(['status'=>3]);
                    // 用户USDT余额
                    $userInfo->save([
                        'dw_usdt'=>bcadd($userInfo['dw_usdt'], $mall_info['usdt_num'], 4)
                    ]);
                    // USDT日志
                    UsdtLog::create([
                        'user_id' => $userInfo->user_id,
                        'log_content' => '场外交易-下架出售USDT',
                        'usdt_charge_type' => 1,
                        'type' => 2, // 1 支出 2转入
                        'chance_usdt' => $mall_info['usdt_num'],
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
                // 订单列表
                $orderList = UsdtOrder::where("mall_id'=>$mall_id and mall_user_id'={$userInfo['user_id']} and status IN(1,2,3)")
                    ->field('order_usdt_num')
                    ->select();
                // 取消扣除的usdt
                $usdt_order = 0;
                foreach ($orderList as $item) {
                    $usdt_order = bcadd($usdt_order, $item->order_usdt_num, 4);
                }
                //用户需要扣除的
                $usdt_mall = bcsub($mall_info['usdt_num'],$usdt_order,2);
                Db::startTrans();
                try{
                    // 没有确认支付的订单取消其订单
                    UsdtOrder::where(['mall_id'=>$mall_id,'status'=>0])->update(['status'=>4]);
                    // 挂卖下架
                    $mall_info->save([
                        'status'=>3,
                        'over_usdt'=>$usdt_order
                    ]);
                    // 用户USDT余额
                    $userInfo->save([
                        'dw_usdt'=> bcadd($userInfo->dw_usdt, $usdt_mall, 4)
                    ]);
                    // USDT日志
                    UsdtLog::create([
                        'user_id' => $userInfo->user_id,
                        'log_content' => '场外交易-下架出售USDT',
                        'usdt_charge_type' => 1,
                        'type' => 2, // 1 支出 2转入
                        'chance_usdt' => $usdt_mall,
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
        } elseif ($type == 2) { // 取消我要收购usdt
            if ($mall_info['status'] == 0)
            {
                Db::startTrans();
                try {
                    $mall_info->save(['status'=>3]);
                    // 提交
                    Db::commit();
                    return $this->response();
                } catch (\Exception $exception) {
                    // 回滚
                    Db::rollback();
                    return $this->response('取消失败!',304);
                }
            } elseif ($mall_info['status'] == 1)
            {
                // 订单列表
                $orderList = UsdtOrder::where("mall_id'=>$mall_id and mall_user_id'={$userInfo['user_id']} and status IN(1,2,3)")
                    ->field('user_id, order_usdt_num')
                    ->select();
                Db::startTrans();
                try{
                    // 没有确认付款的订单取消
                    UsdtOrder::where(['mall_id'=>$mall_id,'status'=>0])->update(['status'=>4]);
                    // 取消扣除的usdt
                    $usdt_order = 0;
                    // 退币（从订单退到下单用户）
                    foreach ($orderList as $item) {
                        // 下单用户
                        $userOrder = $item->orderUser;
                        $userOrder->save([
                            'dw_usdt'=>bcadd($userOrder->dw_usdt, $item->order_usdt_num, 4)
                        ]);
                        // USDT日志
                        UsdtLog::create([
                            'user_id' => $userOrder->user_id,
                            'log_content' => '场外交易-下架出售USDT',
                            'usdt_charge_type' => 1,
                            'type' => 2, // 1 支出 2转入
                            'chance_usdt' => $item->order_usdt_num,
                            'dw_usdt' => $userOrder->dw_usdt,
                            'add_time' => time(),
                        ]);
                        // 计算收购数量
                        $usdt_order = bcadd($usdt_order, $item->order_usdt_num, 4);
                    }
                    // 挂买下架
                    $mall_info->save([
                        'status'=>3,
                        'over_usdt'=>$usdt_order
                    ]);
                    // 提交表单
                    Db::commit();
                    return $this->response();
                }catch (\Exception $exception){
                    // 回滚
                    Db::rollback();
                    return $this->response('取消失败!',304);
                }
            }
        }

        return $this->response('订单不可取消',304);
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
            // 出售usdt
            if ($usdtMall['type'] == 1) {
                // 扣除Usdt
                $usdt_num = bcsub($usdtMall['usdt_num'], $usdtMall['over_usdt'], 4);
                $userInfo->save([
                    'dw_usdt' => bcsub($userInfo->dw_usdt, $usdt_num, 4)
                ]);
                // USDT日志
                UsdtLog::create([
                    'user_id' => $userInfo->user_id,
                    'log_content' => '场外交易-上架出售USDT',
                    'usdt_charge_type' => 1,
                    'type' => 1, // 1 支出 2转入
                    'chance_usdt' => $usdt_num,
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
            if ($mall['type'] == 2) {
                // 退回下单人账户
                $orderUser = $order->orderUser;
                $orderUser->save(['dw_usdt'=> bcadd($orderUser->dw_usdt, $usdt, 4)]);
                // USDT日志
                UsdtLog::create([
                    'user_id' => $orderUser->user_id,
                    'log_content' => '场外交易-取消交易USDT',
                    'usdt_charge_type' => 1,
                    'type' => 2, // 1 支出 2转入
                    'chance_usdt' => $usdt,
                    'dw_usdt' => $orderUser->dw_usdt,
                    'add_time' => time(),
                ]);
            }
            // 提交数据
            Db::commit();
            return $this->response();
        }catch (\Exception $exception){
            // 回滚
            Db::rollback();
            return $this->response('取消失败!',304);
        }
    }

}