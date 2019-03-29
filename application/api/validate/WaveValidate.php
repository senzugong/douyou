<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 14:50
 */

namespace app\api\validate;

use think\Validate;

/**
 * Class WaveValidate
 * 涨跌验证器
 * @package app\api\validate
 */
class WaveValidate extends Validate
{
    /**
     * @var array 规则
     */
    protected $rule = [
        'pay_password'=> 'require|checkPassword',
        'money'=> 'require|checkMoney',
        'type_id'=> 'require|between:1,2',
        'btc_id'=> 'require',
    ];
    /**
     * @var array 自定义消息
     */
    protected $message = [
        'pay_password.require'=> '支付密码为空',
        'money.require'=> '抖金不能为空',
        'type_id'=> '类型错误',
        'btc_id'=> '最新期数必传',
    ];
    /**
     * @var array 场景验证
     */
    protected $scene = [
        'buy'=> ['pay_password', 'money', 'type_id'],
        'new'=> ['btc_id'],
    ];

    /**
     * 验证支付密码
     * @param $value
     * @return bool|string
     */
    protected function checkPassword($value) {
        return CommonValidate::validate('payPassword', $value);
    }

    /**
     * 验证金额
     * @param $value
     * @return bool|string
     */
    protected function checkMoney($value) {
        // 用户信息
        $userInfo = request()->userInfo;
        if ($value > $userInfo->dw_money) {
            return '抖金不足';
        } else {
            return true;
        }
    }
}