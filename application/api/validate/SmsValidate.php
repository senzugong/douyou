<?php

namespace app\api\validate;
use think\Validate;
use think\Db;

class SmsValidate extends Validate
{

    protected $rule = [
        ['phone','require|checkPhone|checkPhoneUnique','联系电话不能为空|联系电话格式错误|联系电话已存在'],
        ['type','require|number','短信类型不能为空|短信类型错误']
    ];

    protected function checkPhone($value){
        $phone_preg = '/^0{0,1}(13[0-9]|15[0-9]|166|173|176|177|178|18[0-9]|19[89])[0-9]{8}$/';
        if(preg_match($phone_preg, $value)){
            return true;
        }else{
            return '手机号码格式错误';
        }
    }

    protected function checkPhoneUnique($value,$rule,$data){
        if(in_array($data['type'],[1,6])){
            $re = Db::name('dw_users')->where(['user_phone'=>$value])->find();
            if($re){
                return '联系电话已存在';
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    protected $scene = [
        'edit'  =>  ['phone','type','sms_code'=>'require|number|between:100000,999999'],
    ];


}