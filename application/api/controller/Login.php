<?php

namespace app\api\controller;

use app\common\library\Curl;
use controller\BasicApi;
use app\api\validate\LoginValidate;
use app\common\model\User;
use app\common\model\Token;
use think\Config;
use think\Db;
use think\Request;
/**
 * 系统登录控制器
 * class Login
 * @package app\admin\controller
 * @author
 * @date 2017/02/10 13:59
 */
class Login extends BasicApi
{
    /**
     * 用户登录
     * @return string
     */
    public function login(Request $request, LoginValidate $loginValidate )
    {
        // 验证数据
        if (!$loginValidate->check($request->post())) {

            return  $this->response( $loginValidate->getError() ,304);
        }
        $user_phone = $request->post('user_phone');
        $password = $request->post('password');
        $password = md5(md5($password));
        $user = User::where(['user_phone'=>$user_phone,'login_password'=>$password])->find();
         //重新登录(更新token)
         $token = $this->createToken();
         $user_token = Token::checkToken($user['user_id'])->find();
         if($user_token){
             $user_token->save($token);
         }else{
             $token['user_id'] = $user['user_id'];
             Token::create($token);
         }
        $user['token'] =  $token['token'];
        $user['user_avatar'] = $user['user_avatar'] ? Config::get('image_url') .$user['user_avatar'] : '';
        $user['add_time'] = $this->getTime($user['add_time']);
        return $this->response($user);
    }

    /**
     * 验证码登录
     * @param Request $request
     * @param LoginValidate $loginValidate
     * @return \think\Response
     */
    public function sms_login(Request $request, LoginValidate $loginValidate )
    {
        // 验证数据
        if (!$loginValidate->scene('sms')->check($request->post())) {
            return  $this->response( $loginValidate->getError() ,304);
        }
        // 验证验证码
        $result = $this->checkSms($request->post('user_phone'), $request->post('sms_code'), 8);
        if($result != 1){
            return $this->response( $result, 406);
        }
        $user = $request->userInfo;
         //重新登录(更新token)
         $token = $this->createToken();
         $user_token = Token::checkToken($user['user_id'])->find();
         if($user_token){
             $user_token->save($token);
         }else{
             $token['user_id'] = $user['user_id'];
             Token::create($token);
         }
        $user['token'] =  $token['token'];
        $user['user_avatar'] = $user['user_avatar'] ? Config::get('image_url') .$user['user_avatar'] : '';
        $user['add_time'] = $this->getTime($user['add_time']);
        return $this->response($user);
    }

    /**
     * @param Request $request
     * @param LoginValidate $loginValidate
     * @return \think\Response三方授权登录
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function open_login(Request $request, LoginValidate $loginValidate)
    {
        // 验证数据
        if (!$loginValidate->scene('open')->check($request->post())) {

            return  $this->response( $loginValidate->getError() ,304);
        }
        $type = $request->post('type');
        $openid = $request->post('openid');
        if($type ==1){
            $user = User::where(['wx_openid'=>$openid])->find();
        }elseif($type ==2){
            $user = User::where(['qq_openid'=>$openid])->find();
        }elseif($type ==3){
            $user = User::where(['xl_openid'=>$openid])->find();
        }
        if(!$user){
            //未绑定过微信 qq 新浪
            $user_phone = $request->post('user_phone');
            $sms_code = $request->post('sms_code');
            // 绑定手机号
            if (!$user_phone) {
                return $this->response( '请绑定手机号', 701);
            }
            $result = $this->checkSms($user_phone,$sms_code,7);
            if($result != 1){
                return $this->response( $result, 406);
            }
            $user = User::where(['user_phone'=>$user_phone])->find();
            if(!$user){
                // $user = User::create([
                //     'user_phone' => $user_phone,
                //     'login_password'=>  '',
                //     'wx_openid'=> '',
                //     'qq_openid'=> '',
                //     'xl_openid'=> '',
                //     'add_time' => time(),
                //     'last_ip' => $_SERVER["REMOTE_ADDR"],
                // ]);
                return $this->response( '请注册账号登录!', 602);
            }
            // 更新openid
            if($type ==1){
                $user->save(['wx_openid'=>$openid]);
            }elseif($type ==2){
                $user->save(['qq_openid'=>$openid]);
            }elseif($type ==3){
                $user->save(['xl_openid'=>$openid]);
            }
        }
        //重新登录(更新token)
        $token = $this->createToken();
        $user_token = Token::checkToken($user['user_id'])->find();
        if($user_token){
            $user_token->save($token);
        }else{
            $token['user_id'] = $user['user_id'];
            Token::create($token);
        }
        $user['token'] =  $token['token'];
        $user['user_avatar'] = $user['user_avatar'] ? Config::get('image_url') .$user['user_avatar'] : '';
        $user['add_time'] = $this->getTime($user['add_time']);
        return $this->response($user);
    }

    /**
     * 忘记密码
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function forget_password(Request $request, LoginValidate $loginValidate)
    {
        // 验证数据
        if (!$loginValidate->scene('forget')->check($request->post())) {

            return  $this->response( $loginValidate->getError() ,304);
        }
        $user_phone = $request->post('user_phone');
        $sms_code = $request->post('sms_code');
        $result = $this->checkSms($user_phone,$sms_code,2);
        if($result != 1){
            return $this->response( $result, 406);
        }
        $password = $request->post('password');
        if(empty($password) || strlen($password) < 6)
        {
            return  $this->response( '密码格式错误' ,304);
        }
       User::where(['user_phone'=>$user_phone])->update(['login_password'=>md5(md5($password))]);
       return $this->response();
    }

    /**
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function user_detail(Request $request){
        $user_id = $request->param('user_id');
        $length = strlen($user_id);
        $number = substr($user_id,2,$length-6);
        $user_detail = User::where(['user_id'=>$number])->find();
        $user_detail['token'] = Token::where(['user_id'=>$number])->value('token');
        return $this->response($user_detail);
    }

    /**版本更新
     * @param Request $request
     * @return \think\Response
     */
    public function app_update(Request $request)
    {
        $type = $request->param('type');
        $app_model = $request->param('app_model');
        if(!$type || !$app_model)
        {
            return  $this->response( '参数不全!' ,304);
        }
        $reuslt1 = Db::table('dw_app_model')->where(['type'=>$type])->value('app_model');
        if(!$reuslt1){
            return  $this->response( '未发布新版本!' ,304);
        }
        if($app_model == $reuslt1)
        {
            return  $this->response( '您已经是最新的版本了!' ,304);
        }else{
            $reuslt = Db::table('dw_app_model')->where(['type'=>$type])->find();
            return  $this->response($reuslt);
        }
    }
    /**
     * 前端数据请求
     */
    public function btc_require(Request $request){
        $type = $request->post('type','1min');
        $size = $request->post('size',40);
        $url = "http://api.bitkk.com/data/v1/kline?market=btc_usdt&type=$type&size=$size";
        $data = Curl::get($url);
        return  $this->response($data);
    }
}
