<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 15:35
 */

namespace app\api\controller;

use app\common\model\Sms as massage;
use app\api\validate\SmsValidate;
use controller\BasicApi;
use think\Db;
use think\Request;
use think\response\Json;
use think\Config;
/**
 * Class Turntable
 * 转盘控制器
 * @package app\api\controller
 */
class Sms extends BasicApi
{
    /*
 * 发送不同类型的短信接口
 */
    public function send_msg(Request $request, SmsValidate $SmsValidate) {

        // 验证数据
        if (!$SmsValidate->check($request->post())) {

            return  $this->response( $SmsValidate->getError() ,304);
        }
        $phone = $request->post('phone');
        $type = $request->post('type');
        if($type != 1 ){
            $result = Db::table('dw_users')->where(['user_phone'=>$phone])->find();
            if(!$result){
                return $this->response('该手机号码未注册!', 406);
            }
        }
        if (Config::get('SMS_DEBUG')) {
            $code = substr($phone,-6); //手机号后6位
        } else {
            $code = rand(100000, 999999); //随机 6 位数的验证码
        }
        $param = array('code' => $code);
        $smscode = Config::get('smscode'); //短信模板 ID
        $res = $this->sendSms($phone, $smscode, $param);
        if($res['Code']=='OK')
        {
            $sms = massage::phone($phone,$type)->find();
            if($sms){// 判断手机好是否以前发送过验证码
                $sms->save(['sms_code'=>$code,'expire_time'=>time()+10*60]);
            }else{
                massage::create(['sms_type'=>$type,'phone'=>$phone,'sms_code'=>$code,'expire_time'=>time()+10*60]);
            }
            return $this->response();
        }else{
            return $this->response('请勿频繁操作!', 406);
        }
    }

    /**
     * 用户验证验证码
     */
    public function sms_validate(Request $request, SmsValidate $SmsValidate)
    {
        // 验证数据
        if (!$SmsValidate->scene('edit')->check($request->post())) {

            return  $this->response( $SmsValidate->getError() ,304);
        }
        $phone =$request->post('phone');//短信验证号码
        $sms_code = $request->post('sms_code');
        $type =$request->post('type');
        $re = $this->checkSms($phone,$sms_code,$type);//电话号码  验证码  注册类型
        if($re == 1){
            return $this->response();
        }else{
            return $this->response( $re, 406);
        }
    }


}