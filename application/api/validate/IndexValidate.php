<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 10:45
 */

namespace app\api\validate;

use think\Validate;

/**
 * Class IndexValidate
 * 主页验证器
 * @package app\api\validate
 */
class IndexValidate extends Validate
{
    protected $rule = [
        'sign_in'=> 'requireSign', // 当天签到验证
    ];
    /**
     * @var array 自定义提示
     */
    protected $message = [];

    /**
     * 验证是否已签到
     * @return bool|string
     */
    protected function requireSign() {
        $userInfo = request()->userInfo;
        // 今天签到情况
        $signToday = $userInfo->userWeek()
            ->whereTime('add_time', 'today')
            ->find();
        return !$signToday ? true : '已签到';
    }

    /**
     * @var array 场景验证
     */
    protected $scene = [
        'sign'=> ['sign_in'],
    ];
}