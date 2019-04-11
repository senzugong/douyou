<?php
/**
 * 文件描述
 * Create on : 2017/8/11 10:04
 * Autor email :416148489@qq.com
 * Create by sucaihuo
 */

namespace app\api\controller;

use controller\BasicApi;
use app\api\validate\RegisterValidate;
use think\Db;
use think\Request;
use app\common\model\User;

class Register extends BasicApi
{

    /**
     * 用户验证验证码
     */
    public function register(Request $request, RegisterValidate $RegisterValidate )
    {
        // 验证数据
        if (!$RegisterValidate->check($request->post())) {
            return  $this->response( $RegisterValidate->getError() ,304);
        }
        $phone = $request->post('user_phone');//手机号码
        $wx_openid = $request->post('wx_openid') ? $request->post('wx_openid'):'';
        $qq_openid = $request->post('qq_openid') ? $request->post('qq_openid'):'';
        $xl_openid = $request->post('xl_openid') ? $request->post('xl_openid'):'';
        $password =  $request->post('password');
        $sms_code = $request->post('sms_code');
        // 验证手机验证码
        $result = $this->checkSms($phone,$sms_code,1);
        if($result != 1){
            return $this->response( $result, 406);
        }
        $password = md5(md5($password)); //密码加密方式
        // 邀请人
        $inviteUser = User::where(['invite_code'=> $request->post('invite_code')])->value('user_id');
        $data = [
            'user_phone' => $phone,
            'user_avatar' => 'erweima/avatar.png',
            'user_name' => substr_replace($phone, '****', 3, 4),
            'login_password'=>$password,
            'wx_openid'=> $wx_openid,
            'qq_openid'=> $qq_openid,
            'xl_openid'=> $xl_openid,
            'invite_user'=> $inviteUser,
            'invite_code'=> $this->getOnlyCode(), // 邀请码
            'add_time' => time(),
            'last_ip' => $_SERVER["REMOTE_ADDR"],
        ];
        Db::startTrans(); //启动事务
        try {
            $dw_user = Db::table('dw_users')->insertGetId($data);
            if($dw_user) {
                $token_data = $this->createToken();
                $token_data['user_id'] = $dw_user;
                Db::table('dw_token')->insert($token_data);
                $data['reg_time'] = $this->getTime(time());
                $data['token'] = $token_data['token'];
                Db::commit(); //提交事务
                return $this->response($data);
            }
        } catch (\PDOException $e) {
            Db::rollback(); //回滚事务
            return $this->response('新增失败!', 407);
        }

    }

    /**
     * 唯一邀请码
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function getOnlyCode() {
        $code = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = strlen($code) - 1;
        $onlyCode = '';
        for ($i = 0; $i < 5; $i++) {
            $onlyCode .= $code[mt_rand(0, $length)];
        }
        // 判断邀请码是否重复
        $user = User::where(['invite_code'=> $onlyCode])->find();
        if ($user) {
            $onlyCode = $this->getOnlyCode();
        }
        return $onlyCode;
    }
}