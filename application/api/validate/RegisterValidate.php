<?php

namespace app\api\validate;
use app\common\model\User;
use think\Validate;
use think\Db;

class RegisterValidate extends Validate
{

    protected $rule = [
        ['user_phone','require|checkPhone|checkPhoneUnique'],
        ['sms_code','require|number|between:100000,999999'],
        ['password','require|checkPassward'],
        ['invite_code','require|checkInviteCode'],
    ];
    protected $message = [
        'user_phone.require'=> '登录手机号不能为空!',
        'sms_code.require'=> '短信验证码不能为空!',
        'sms_code.number'=> '短信验证码错误!',
        'sms_code.between'=> '短信验证码错误!',
        'password.require' => '密码不能为空!',
    ];
    protected function checkPhone($value){
        $phone_preg = '/^0{0,1}(13[0-9]|15[0-9]|173|176|177|178|18[0-9])[0-9]{8}$/';
        if(preg_match($phone_preg, $value)){
            return true;
        }else{
            return '手机号码格式错误!';
        }
    }
    protected function checkPassward($value){
        if(empty($value) || strlen($value) < 6)
        {
            return '密码格式错误!';
        }else{
            return true;
        }
    }
    protected function checkPhoneUnique($value){
        $re = Db::table('dw_users')->where(['user_phone'=>$value])->find();

        if($re){
            return '手机号码已存在!';
        }else{
            return true;
        }
    }
    protected function checkInviteCode($value) {
        $user = User::where(['invite_code'=> $value])->find();
        return !$user ? '邀请码不存在' : true;
    }


}