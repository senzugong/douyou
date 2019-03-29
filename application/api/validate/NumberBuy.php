<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19
 * Time: 14:18
 */

namespace app\api\validate;

use think\Validate;

/**
 * Class NumberBuy
 * 号码竞猜押注验证器
 * @package app\api\validate
 */
class NumberBuy extends Validate
{
    protected $rule = [
        'pay_password'=> 'require|checkPassword',
        'money'=> 'require|checkMoney',
        'type_id'=> 'require|between:1,2',
        'halt_time'=> 'require|checkTime',
    ];
    /**
     * @var array 自定义提示
     */
    protected $message = [
        'pay_password.require'=> '支付密码为空',
        'money.require'=> '抖金',
        'type_id'=> '类型错误',
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

    /**
     * 验证截止时间
     * @param $value
     * @return bool|string
     */
    protected function checkTime($value) {
        // 开奖前不能继续投注
        if ($value > time()) {
            return '已停止投注';
        } else {
            return true;
        }
    }
}