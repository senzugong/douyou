<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 14:12
 */

namespace app\api\validate;

use app\common\model\UserWallet;
use think\Validate;
class UsdtValidate extends Validate
{

    protected $rule = [
        'wallet_id'=> 'require|checkWallet',
        'usdt_num' => 'require|egt:10',
        'dw_num' => 'require|egt:10',
        'pay_password'=> 'require|checkPassword',
        'wallet_address'=> 'require',
        'wallet_type_id'=> 'require',
        'coin_num'=> 'require|gt:0',
        'ratio'=> 'require',
    ];
    /**
     * @var array 自定义提示
     */
    protected $message = [
        'wallet_id.require'=> '设为默认钱包地址id不能为空!',
        'usdt_num.require'=> 'usdt数量不能为空!',
        'usdt_num.egt'=> 'usdt数量必须大于等于10!',
        'dw_num.require'=> '蚪金数量不能为空!',
        'dw_num.egt'=> '蚪金数量必须大于等于10!',
        'wallet_address.require'=> '钱包地址不能为空!',
        'wallet_type_id.require'=> '钱包类型不能为空!',
        'coin_num.require'=> '货币数量不能为空!',
        'ratio.require'=> '兑换比例不能为空!',
        'coin_num.gt'=> '货币数量必须大于等于0!',
    ];
    /**
     * 验证钱包
     * @param $value
     * @return bool|string
     */
    protected function checkWallet($value)
    {
        $userInfo = request()->userInfo;
        $result = UserWallet::where(['wallet_id'=>$value,'user_id'=>$userInfo['user_id']])->find();
        if(!$result){
            return '该钱包地址不存在!';
        }else{
            return true;
        }

    }

    /**
     * 验证支付密码
     * @param $value
     * @return bool|string
     */
    protected function checkPassword($value) {
        return CommonValidate::validate('payPassword', $value);
    }
    /**
     * 验证可提现数量
     * @param $value
     * @return bool|string
     */
    protected function checkNum($value) {
        // 用户信息
        $userInfo = request()->userInfo;
        if ($userInfo['dw_usdt'] <= $value) {
            return '当前usdt不足!' ;
        }else{
            return true;
        }
    }
    /**
     * @var array 场景验证
     */
    protected $scene = [
        'add'=> ['pay_password', 'wallet_address', 'wallet_type_id'],
        'recharge'=> ['usdt_num'],
        'add_usdt'=> ['usdt_num','wallet_id','coin_num','ratio'],//充值
        'reflect'=> ['usdt_num'=>'require|egt:10|checkNum','wallet_id','coin_num','ratio','pay_password'], //提现
        'ratio_dw'=>['usdt_num','pay_password'],
        'ratio_usdt'=>['dw_num','pay_password'],
    ];

}