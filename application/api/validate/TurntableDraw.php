<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 17:57
 */

namespace app\api\validate;

use app\common\model\TurntableLog;
use think\Validate;

/**
 * Class TurntableDraw
 * 幸运转盘抽奖验证器
 * @package app\api\validate
 */
class TurntableDraw extends Validate
{
    /**
     * @var array 验证规则
     */
    protected $rule = [
        'pay_password'=> 'requireWallet',
        'win_id'=> 'require',
        'address_detail'=> 'require',
        'mobile'=> 'require|checkPhone',
        'name'=> 'require',
    ];
    /**
     * @var array 自定义提示
     */
    protected $message = [
        'pay_password'=> '支付密码为空!',
         'win_id'=> '获奖日志id不能为空',
        'address_detail'=> '详细地址不能为空',
        'mobile.require'=> '手机号码不能为空',
        'name'=> '收货地址不能为空',
    ];

    /**
     * 验证手机号码
     * @param $value
     * @return bool|string
     */
    protected function checkPhone($value){
        $phone_preg = '/^0{0,1}(13[0-9]|15[0-9]|173|176|177|178|18[0-9])[0-9]{8}$/';
        if(preg_match($phone_preg, $value)){
            return true;
        }else{
            return '手机号码格式错误!';
        }
    }
    /**
     * 验证支付密码与钱包金额
     * @param $value
     * @return bool|string
     */
    protected function requireWallet($value) {
        $userInfo = request()->userInfo;
        $result =$userInfo->turntableLog()
            ->where(['type'=> 0, 'status'=> 1, 'turntable_id'=> 6])
            ->find();
        if ($userInfo['free_password'] == 1 || $result) {
            return true;
        }
        return CommonValidate::validate('payPassword', $value);
    }
    protected $scene = [
        'draw'=>['pay_password'],
        'address' => ['win_id','address_detail','mobile','name']
    ];
}