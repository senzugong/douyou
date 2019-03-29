<?php

namespace app\admin\controller;

use controller\BasicAdmin;
use service\LogService;
use service\NodeService;
use think\Db;

/**
 * 系统登录控制器
 * class Login
 * @package app\admin\controller
 * @author sucaihuo <416148489@qq.com>
 * @date 2017/02/10 13:59
 */
class Login extends BasicAdmin
{

    /**
     * 默认检查用户登录状态
     * @var bool
     */
    public $checkLogin = false;

    /**
     * 默认检查节点访问权限
     * @var bool
     */
    public $checkAuth = false;

    /**
     * 控制器基础方法
     */
    public function _initialize()
    {
        if (session('user') && $this->request->action() !== 'out') {
            $this->redirect('@admin');
        }
    }

    /**
     * 用户登录
     * @return string
     */
    public function index()
    {
        if ($this->request->isGet()) {
            $this->assign('title', '用户登录');
            return $this->fetch();
        } else {
            $username = $this->request->post('username', '', 'trim');
            $password = $this->request->post('password', '', 'trim');
            if (config('verify_type') == 1) {
                $code = input("param.code");
            }
            $verify = new \Verify();
            if (config('verify_type') == 1) {
                if (!$code) {
                    // return json(['code' => -4, 'data' => '', 'msg' => '请输入验证码']);
                    $this->error('请输入验证码');
                }
                if (!$verify->check($code)) {
                    //return json(['code' => -4, 'data' => '', 'msg' => '验证码错误']);
                    $this->error('验证码错误');
                }
            }
//            if (config('verify_type') == 0) {
//
//                $GtSdk = new \Geetestlib(config('gee_id'), config('gee_key'));
//                $user_id = session('user_id');
//                if (session('gtserver') == 1) {
//                    $result = $GtSdk->success_validate(input('param.geetest_challenge'), input('param.geetest_validate'), input('param.geetest_seccode'), $user_id);
//                    //极验服务器状态正常的二次验证接口
//                    if (!$result) {
//                        //   $this->error('请先拖动验证码到相应位置');
//                        $this->error('请先拖动验证码到相应位置');
//                    }
//                }else{
//                    if (!$GtSdk->fail_validate(input('param.geetest_challenge'), input('param.geetest_validate'), input('param.geetest_seccode'))) {
//                        //极验服务器状态宕机的二次验证接口
//                        $this->error('请先拖动验证码到相应位置');
//                    }
//                }
//
//            }
            (empty($username) || strlen($username) < 4) && $this->error('登录账号长度不能少于4位有效字符!');
            (empty($password) || strlen($password) < 6) && $this->error('登录密码长度不能少于4位有效字符!');
            $user = Db::name('SystemUser')->where('username', $username)->where('is_deleted',0)->find();
            if($user['status'] == 0){
                $this->error('账号已经禁用!');
            }
            empty($user) && $this->error('登录账号不存在，请重新输入!');
            ($user['password'] !== md5($password)) && $this->error('登录密码与账号不匹配，请重新输入!');
            Db::name('SystemUser')->where('id', $user['id'])->update(['login_at' => ['exp', 'now()'], 'login_num' => ['exp', 'login_num+1']]);
            session('user', $user);
            !empty($user['authorize']) && NodeService::applyAuthNode();
            LogService::write('系统管理', '用户登录系统成功');
            $this->success('登录成功，正在进入系统...', '@admin');
        }
    }
//验证码
    public function checkVerify()
    {
        $verify = new \Verify();
        $verify->imageH = 32;
        $verify->imageW = 100;
        $verify->codeSet = '0123456789';
        $verify->length = 4;
        $verify->useNoise = false;
        $verify->fontSize = 14;
        return $verify->entry();
    }
    public function getverify(){
        $GtSdk = new \Geetestlib(config('gee_id'), config('gee_key'));
        $user_id = "web";
        $status = $GtSdk->pre_process($user_id);
        session('gtserver',$status);
        session('user_id',$user_id);
        echo $GtSdk->get_response_str();
    }
    /**
     * 退出登录
     */
    public function out()
    {
        LogService::write('系统管理', '用户退出系统成功');
        session('user', null);
        $this->success('退出登录成功！', 'login/index');
    }

}
