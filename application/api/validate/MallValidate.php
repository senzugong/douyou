<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 16:51
 */

namespace app\api\validate;

use app\common\model\UsdtMall;
use app\common\model\UsdtOrder;
use think\Validate;

class MallValidate extends Validate
{
    protected $rule = [
        'mall_id'=> 'require|checkMall',
        'money'=> 'require|checkMoney',
        'pay_type'=> 'require|between:1,3',
        'gathering_id'=> 'require',
        'usdt_num'=>'require|egt:1|checkUsdtnum',
        'usdt_price'=>'require|gt:0',
        'mix_rmb'=>'require|gt:0',
        'order_id'=>'require',

    ];
    /**
     * @var array 自定义提示
     */
    protected $message = [
        'mall_id'=> '购买id不能为空!',
        'money.require'=> '金额不能为空!',
        'pay_type'=> '支付方式错误',
        'gathering_id'=> '付款ID不能为空',
        'usdt_num.require'=> 'usdt数量不能为空',
        'usdt_num.gt'=> 'usdt数量必须大于等于1个',
        'usdt_price'=> 'usdt价格不能缺',
        'mix_rmb'=> '最小交易金额必填',
        'type'=> '类型不能为空!',
        'order_id'=> '订单id不能为空!',
    ];
    /**
     * @var array 场景验证
     */
    protected $scene = [
        'shopping'=> ['mall_id', 'money'],
        'confirm_pay'=> ['order_id'],
        'allocate'=> ['order_id'],
        'sell'=> ['usdt_price','usdt_num'], // 我要发布（我要出售）
        'mall'=>['mall_id'], // 取消发布
        'again_mall'=>['mall_id'=> 'require|checkAgainMall'], // 重新发布
        'cancel_order'=> ['order_id'=> 'require|checkStatus:cancel'], // 取消订单
        'appeal'=> ['order_id'=> 'require|checkStatus:trade'], // 申诉
    ];
    /**
     * 验证usdt数量
     */
    protected function checkUsdtnum($value,$rule,$data){
        $userInfo = request()->userInfo;
        if($data['type'] ==1){ // 出售usdt
            if($value > bcmul($userInfo['dw_usdt'],1.02,2)){
                return '你的账户usdt不足!';
            }else{
                return true;
            }
        }else{
            return true;
        }
    }
    /**
     * 验证mall_id(0,1)
     */
    protected function checkMall($value){
        $userInfo = request()->userInfo;
        $mall_info = UsdtMall::where("mall_id=$value and status IN (0,1)")->find();
        if(!$mall_info){
            return '该挂单你无法操作';
        }else{
            return true;
        }
    }

    /**
     * 重新发布验证
     * @param $value
     * @return bool|string
     * @throws \think\exception\DbException
     */
    protected function checkAgainMall($value) {
        $userInfo = request()->userInfo;
        // 挂单信息
        $usdtMall = UsdtMall::get($value);
        if (!$usdtMall || $usdtMall->user_id != $userInfo->user_id) {
            return '该挂单你无法操作';
        }
        if ($usdtMall['usdt_num'] <= $usdtMall['over_usdt']) {
            return '该挂单已经全部成交';
        }
        // 用户出售usdt
        $overNum = bcsub($usdtMall['usdt_num'], $usdtMall['over_usdt'], 2);
        if (bcmul($overNum,1.02,2) > $userInfo['dw_usdt']) {
            return '你的账户usdt不足!';
        }
        return true;
    }
    /**
     * 验证金额
     */
    protected function checkMoney($value,$rule,$data)
    {
        // 挂单信息
        $mall_info = UsdtMall::get($data['mall_id']);
        // 挂单失效
        if(!$mall_info || $mall_info['status'] == 3){
            return '该挂单已取消!';
        }
        if ($mall_info['status'] == 2) {
            return '该挂单USDT数量已完';
        }
        // 下单的数量要在指定范围
        if($value < $mall_info['mix_rmb'])
        {
            return '金额小于最低限制金额!';
        }
        if($value > $mall_info['max_rmb'])
        {
            return '金额大于限制金额!';
        }
        // 用户信息
        // $userInfo = request()->userInfo;
        // 挂单是收购时，判断用户是否有充足的usdt
        // if ($mall_info['type'] == 2 && $userInfo->dw_usdt < bcmul($value,1.02,2)) {
        //     return '你的USDT不足，';
        // }
        return true;
    }

    /**
     * 验证订单状态
     * @param $value
     * @return bool|string
     * @throws \think\exception\DbException
     */
    protected function checkStatus($value, $rule) {
        $order = UsdtOrder::get($value);
        switch ($rule) {
            case 'cancel':
                // 订单取消验证
                if (!$order || $order['status'] !== 0) {
                    return '订单不可取消';
                } else {
                    return true;
                }
                break;
            case 'trade':
                // 申诉验证
                if (!$order || $order['status'] !== 1) {
                    return '此订单不可申诉';
                } else {
                    return true;
                }
                break;
            default:
                return '选择验证错误';
                break;
        }
    }
}