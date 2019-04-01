<?php

namespace app\api\validate;
use think\Validate;
use think\Db;

class UserValidate extends Validate
{

    protected $rule = [
        ['user_avatar','require|file'],
        ['user_name','require|checkUsername'],
        ['true_name','require'],
        ['card_num','require|checkCard'],
        ['is_read','require|accepted'], // 验证某个字段是否为为 yes, on, 或是 1。这在确认"服务条款"是否同意
        ['password','require|checkPassword'],
        ['user_phone','require'], // 手机验证码
        ['sms_code','require'], // 手机验证码
        ['pay_password','require|checkPayNum'],
        ['step','require|between:1,2'], // 修改支付密码步骤数
        ['new_paypwd','requireIf:step,2|different:pay_password'],
        'images'=> 'require|array|length:3', // 数组的长度用length验证
    ];
    protected $message = [
        'user_avatar.require'=> '头像不能为空!',
        'user_avatar.file'=> '头像必须为文件!',
        'user_name.require'=> '用户名不能为空!',
        'true_name.require'=> '真实名字不能为空!',
        'card_num.require'=> '身份证号码不能为空!',
        'is_read.require'=> '蚪游协议不能为空!',
        'password.require'=> '登录密码不能为空!',
        'user_phone'=> '验证手机号不能为空',
        'sms_code'=> '验证码不能为空!',
        'pay_password.require'=> '支付密码不能为空!',
        'step'=> '流程出错',
        'new_paypwd.requireIf'=> '支付密码不能为空!',
        'new_paypwd.different'=> '支付密码不能与原来相同',
        'images'=> '图片上传错误!',

    ];
    /**
     * 验证用户名
     * @param $value
     * @return bool|string
     */
    protected function checkUsername($value)
    {
        if(strlen($value) > 32)
        {
            return '用户名格式不正确!';
        }
        $userInfo = request()->userInfo;
        if($userInfo['user_name'] == $value){
            return '请填写不一样的用户名!';
        }
        return true;
    }

    /**
     * 支付操作验证
     * @param $value
     * @return bool|string
     */
    protected function checkPayNum($value)
    {
        return CommonValidate::validate('payPassword', $value);
    }
    /**
     * 验证身份证号码
     * @param $value
     * @return bool|string
     */
    function checkCard($value)
    {
        $id = strtoupper($value);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if(!preg_match($regx, $id))
        {
            return '身份证格式错误';
        }
        if(15==strlen($id)) //检查15位
        {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth))
            {
                return '身份证格式错误';
            } else {
                return TRUE;
            }
        } else {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth)) //检查生日日期是否正确
            {
                return '身份证格式错误';
            }
            else
            {
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ( $i = 0; $i < 17; $i++ )
                {
                    $b = (int) $id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id,17, 1))
                {
                    return '身份证格式错误';
                } //phpfensi.com
                else
                {
                    return TRUE;
                }
            }
        }
    }
    protected function isCard($value){
        if( strlen($value) == 18)
        {
            return '身份证位数不正确!';
        }
        $userInfo = request()->userInfo;
        $result = Db::table('dw_user_attestation')->where("user_id = {$userInfo['user_id']} and status IN (0,1)")->find();
        if($result){
            return '请勿重复提交!';
        }
        $code_num  = '/(^\d{18}$)|(^\d{17}([0-9]|X)$)/';
        if(preg_match($code_num, $value)){
            return true;
        }else{
            return '身份证格式不正确!';
        }
    }
    /**
     * 验证密码
     * @param $value
     * @return bool|string
     */
    protected function checkPassword($value){
        if(strlen($value) < 6)
        {
            return '密码格式错误!';
        }else{
            return true;
        }
    }
    protected $scene = [
        'avatar'  =>  ['user_avatar'],
        'user_name'  =>  ['user_name'],
        'attestation'  =>  ['true_name','card_num','is_read'],
        'password'  =>  ['user_phone', 'sms_code', 'password'],
        'pay_password'  =>  ['step', 'pay_password', 'new_paypwd'],
        'retrieve_pay'  =>  ['user_phone', 'sms_code', 'pay_password'=>'require'],
        'examine' =>['images'],
    ];






}