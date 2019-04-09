<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 19:47
 */

namespace app\api\validate;
use app\common\model\MoneyLog;
use app\common\model\UsdtLog;
use app\common\model\UserWallet;
use think\Validate;


/**
 * Class UserSuggest
 * 反馈验证
 * @package app\api\validate
 */
class WalletValidate extends Validate
{
    protected $rule = [
        'wallet_id'=> 'require|checkWallet',
        'dw_num' => 'require|egt:10',
        'pay_password'=> 'require|checkPassword',
        'wallet_address'=> 'require',
        'wallet_type_id'=> 'require',
        'coin_num'=> 'require|gt:0',
        'ratio'=> 'require',
        'bill_id'=> 'require|checkBill',
    ];
    /**
     * @var array 自定义提示
     */
    protected $message = [
        'wallet_id.require'=> '设为默认钱包地址id不能为空!',
        'dw_num.require'=> '抖金数量不能为空!',
        'dw_num.egt'=> '抖金数量必须大于等于10!',
        'wallet_address.require'=> '钱包地址不能为空!',
        'wallet_type_id.require'=> '钱包类型不能为空!',
        'coin_num.require'=> '货币数量不能为空!',
        'ratio.require'=> '兑换比例不能为空!',
        'coin_num.gt'=> '货币数量必须大于等于0!',
        'bill_id.require'=> '用户账单id不能为空!',
    ];
    /**
     * 验证钱包
     * @param $value
     * @return bool|string
     */
    protected function checkWallet($value)
    {
       $result = UserWallet::where(['wallet_id'=>$value])->find();
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
        if ($userInfo['dw_money'] <= $value) {
            return '当前抖金不足!' ;
        }else{
            return true;
        }
    }
    /**
     * 验证可提现数量
     * @param $value
     * @return bool|string
     */
    protected function checkBill($value) {
        // 用户信息
        $userInfo = request()->userInfo;
        $result = UsdtLog::where(['user_id'=>$userInfo['user_id'],'log_id'=>$value])->find();
        if (!$result) {
            return '该账单不存在!' ;
        }else{
            return true;
        }

    }
    /**
     * @var array 场景验证
     */
    protected $scene = [
        'add'=> ['pay_password', 'wallet_address', 'wallet_type_id'],
        'recharge'=> ['dw_num'],
        'add_dw'=> ['dw_num','wallet_id','coin_num','ratio'],
        'reflect'=> ['dw_num'=>'require|egt:10|checkNum','wallet_id','coin_num','ratio','pay_password'],
        'bill'=> ['bill_id'],
        'wallet'=> ['wallet_id'],
    ];

}