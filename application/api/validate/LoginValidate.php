<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19
 * Time: 14:18
 */

namespace app\api\validate;

use app\common\model\User;
use think\Validate;
use think\Db;
/**
 * Class NumberBuy
 * 号码竞猜押注验证器
 * @package app\api\validate
 */
class LoginValidate extends Validate
{
    protected $rule = [
        'user_phone'=> 'require',
        'password'=> 'require|checkPassword',

    ];
    /**
     * @var array 自定义提示
     */
    protected $message = [
        'user_phone.require'=> '用户账号不能为空',
        'password.require'=> '用户密码不能为空',
    ];


    /**
     * 验证登录密码
     * @param $value
     * @return bool|string
     */
    protected function checkPassword($value,$rule,$data)
    {
        $password = md5(md5($value));
        $userInfo1 = User::where(['user_phone'=>$data['user_phone']])->find();
        if(!$userInfo1){
            return '该账号未注册!';
        }
        $userInfo = User::where(['login_password'=>$password,'user_phone'=>$data['user_phone']])->find();
        if (!$userInfo) {
            return '密码错误!';
        }else{
            return true;
        }
    }

    /**
     * 验证登录手机号
     * @param $value
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function checkLoginPhone($value) {
        // 用户信息
        $userInfo = User::where(['user_phone'=>$value])->find();
        if (!$userInfo) {
            return '请先注册账号';
        } else {
            // 保存用户信息不用重复查找
            request()->userInfo = $userInfo;
            return true;
        }
    }
    protected $scene = [
        'open'  =>  ['type'=>'require|between:1,3','openid'=>'require'],
        'forget'  =>  ['user_phone','password'=> 'require','sms_code'=> 'require'],
        'sms'  =>  ['user_phone'=>'require|checkLoginPhone','sms_code'=> 'require'],
    ];

}