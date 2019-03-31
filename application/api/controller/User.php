<?php
/**
 * 文件描述
 * Create on : 2017/8/11 10:04
 * Autor email :416148489@qq.com
 * Create by sucaihuo
 */
namespace app\api\controller;
use app\api\validate\UserSuggest;
use app\common\model\Examine;
use app\common\model\MoneyLog;
use app\common\model\Token;
use app\common\model\UsdtOrder;
use app\common\model\UserProposal;
use app\common\model\UserWallet;
use controller\BasicApi;
use app\api\validate\UserValidate;
use app\common\model\UserAttestation;
use think\Config;
use think\Request;

class User extends BasicApi
{
    /**
     * 用户详情
     * @param Request $request
     * @return \think\Response
     */
    public function detail(Request $request) {
        $userInfo = $request->userInfo;
        // 详情
        $userInfo['user_avatar'] = $userInfo['user_avatar'] ? Config::get('image_url') . $userInfo['user_avatar'] : '';
        $userInfo['order_num'] = UsdtOrder::where("(mall_user_id = {$userInfo['user_id']} or user_id = {$userInfo['user_id']}) and status IN (1,2)")->count();
        if(strlen($userInfo['card_num']) ==15){
            $userInfo['card_num'] = substr_replace($userInfo['card_num'], '****', 4, 7);
        }elseif(strlen($userInfo['card_num']) ==18){
            $userInfo['card_num'] = substr_replace($userInfo['card_num'], '****', 4, 10);
        }else{
            $userInfo['card_num'] = '';
        }
        $result = UserWallet::where(['user_id'=>$userInfo['user_id'],'status'=>1])->find();
        if($result){
            $userInfo['is_wallet'] = 1;
        }else{
            $userInfo['is_wallet'] = 0;
        }
        //收入
        $rise = MoneyLog::where(['type'=>2,'user_id'=>$userInfo['user_id']])->whereTime('add_time', 'today')->select();
        $all_expenditure = 0.00;
        foreach($rise as &$v){
            $all_expenditure = bcadd($all_expenditure,$v['chance_money'],2) ;
        };
        //支出
        $fill = MoneyLog::where(['type'=>1,'user_id'=>$userInfo['user_id']])->whereTime('add_time', 'today')->select();
        $all_fill = 0.00;
        foreach($fill as &$v){
            $all_fill = bcadd($all_fill,$v['chance_money'],2) ;
        };
        $userInfo['today_money'] = bcsub($all_expenditure,$all_fill,2);
        return $this->response($userInfo);
    }

    /**
     * 用户头像修改
     * @param Request $request
     * @param UserValidate $userValidate
     * @return \think\Response
     */
    public function  update_avatar(Request $request,UserValidate $userValidate)
    {
        // array_merge只能合并数组，其他报错
        $requestFile = $request->file() ?: [];
        // 验证数据
        if (!$userValidate->scene('avatar')->check(array_merge($request->post(), $requestFile))) {

            return  $this->response( $userValidate->getError() ,304);
        }
        $file = $request->file('user_avatar');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload/image/avatar');
        $data['user_avatar'] = 'public/upload/image/avatar/' . $info->getSaveName();
        // 用户信息
        $userInfo = $request->userInfo;
        // 旧的头像（要在修改前取出旧的头像路径，否则将被覆盖）
        $imgOld = $userInfo->user_avatar;
        // 更新头像地址
        if(!$userInfo['user_avatar']){
            $data['complete_rate'] = $userInfo['complete_rate'] +10;
        }
        $result = $userInfo->save($data);

        // 将旧头像删除
        if ($result && file_exists(ROOT_PATH . $imgOld)) {
            unlink(ROOT_PATH . $imgOld);
        }
        return $this->response();
    }

    /**
     * 用户名更改
     * @param Request $request
     * @param UserValidate $userValidate
     * @return \think\Response
     */
    public function  update_username(Request $request,UserValidate $userValidate)
    {
        // 验证数据
        if (!$userValidate->scene('user_name')->check($request->post())) {
            return  $this->response( $userValidate->getError() ,304);
        }
        $data['user_name'] = $request->post('user_name');
        $userInfo = $request->userInfo;
        if(!$userInfo['user_name']) {
            $data['complete_rate'] = $userInfo['complete_rate'] + 10;
        }
            $result = $userInfo->save($data);

        return $this->response();
       }

    /**
     * 用户认证
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function  user_attestation(Request $request,UserValidate $userValidate)
    {
        // 验证数据
        if (!$userValidate->scene('attestation')->check($request->post())) {
            return  $this->response( $userValidate->getError() ,304);
        }
        $data['true_name'] = $request->post('true_name');
        $data['card_num'] = $request->post('card_num');
        $userInfo = $request->userInfo;
        if($userInfo['is_attestation'] == 1){
            return $this->response('已认证，请勿重复提交!');
        }
//        $result = UserAttestation::where(['user_id' => $userInfo['user_id'] , 'status' =>1])->find();
//        if($result){
//            $data['status'] = 0;
//             UserAttestation::where(['user_id' => $userInfo['user_id']])->update($data);
//             $userInfo->save(['is_attestation'=>1]);
//        }else{
            $data['user_id'] =$userInfo['user_id'];
            $userInfo->save(['is_attestation'=>1,'true_name'=>$data['true_name'],'card_num'=>$data['card_num'] ]);
//        }
        return $this->response();
       }
    /**
     * 用户高级认证
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function  user_examine(Request $request,UserValidate $userValidate)
    {
        $userInfo = $request->userInfo;
        if($userInfo['is_attestation'] !=1)
        {
            return  $this->response( '您还未通过实名认证!' ,304);
        }
        if($userInfo['is_examine']==2)
        {
            return  $this->response( '请等待后台审核!' ,304);
        }
        if($userInfo['is_examine']==1)
        {
            $examine = Examine::where(['user_id'=>$userInfo['user_id']])->find();
            $examine['img1'] = $examine['img1'] ? Config::get('image_url') .$examine['img1'] : '';
            $examine['img2'] = $examine['img2'] ? Config::get('image_url') .$examine['img2'] : '';
            $examine['img3'] = $examine['img3'] ? Config::get('image_url') .$examine['img3'] : '';
            return  $this->response( $examine);
        }
        // 审核状态
        $examineStatus = $userInfo->userExamine()->where(['status'=> 0])->find();
        if ($examineStatus) {
            return  $this->response('请等待管理员审核' , 308);
        }
        // array_merge只能合并数组，其他报错
        $requestFile = $request->file() ?: [];
        // 验证
        if (!$userValidate->scene('examine')->check(array_merge($request->post(), $requestFile))) {
            return $this->response($userValidate->getError(), 304);
        }
        // 其他信息
        $data = [
            'user_id'=> $userInfo->user_id,
            'add_time'=> time(),
        ];
        // 图片
        $files = $request->file('images');
        if (!empty($files) && is_array($files)) {
            foreach ($files as $key=>$file) {
                $info = $file->move(ROOT_PATH . 'public/upload/image/examine');
                $data['img'.($key + 1)] = 'public/upload/image/examine/' . $info->getSaveName();
            }
        }
        // 保存
        Examine::create($data);
        $userInfo->save(['is_examine'=>2]);
        return  $this->response( );
    }
    /**
     * 更改支付密码
     * @param Request $request
     * @param UserValidate $userValidate
     * @return \think\Response
     */
    public function pay_password(Request $request,UserValidate $userValidate)
    {
        $userInfo = $request->userInfo;
        if($userInfo['is_attestation']==0)
        {
            return  $this->response( '您还未通过认证!' ,304);
        }
        // 验证数据
        if (!$userValidate->scene('pay_password')->check($request->post())) {
            return  $this->response( $userValidate->getError() ,304);
        }
        // 第二步更新密码
        if ($request->post('step') == 2) {
            $data = ['pay_password'=> md5(md5($request->post('new_paypwd')))];
            if(!$userInfo['pay_password']) {
                $data['complete_rate'] = $userInfo['complete_rate'] + 10;
            }
            $userInfo->save($data);
        }
        return $this->response();
    }

    /**
     * 找回支付密码
     * @param Request $request
     * @param UserValidate $userValidate
     * @return \think\Response
     */
    public function retrieve_pay(Request $request,UserValidate $userValidate) {
        $userInfo = $request->userInfo;
        if($userInfo['is_attestation']==0)
        {
            return  $this->response( '您还未通过认证!' ,304);
        }
        // 验证数据
        if (!$userValidate->scene('retrieve_pay')->check($request->post())) {
            return  $this->response( $userValidate->getError() ,304);
        }
        // 手机验证码
        $result = $this->checkSms($request->post('user_phone'), $request->post('sms_code'),4);
        if($result != 1){
            return $this->response( $result, 406);
        }
        // 修改成新密码
        $userInfo->save(['pay_password'=> md5(md5($request->post('pay_password')))]);
        return $this->response();
    }

    /**
     * 更改登录密码
     * @param Request $request
     * @param UserValidate $userValidate
     * @return \think\Response
     */
    public function login_password(Request $request,UserValidate $userValidate)
    {
        // 验证数据
        if (!$userValidate->scene('password')->check($request->post())) {
            return  $this->response( $userValidate->getError() ,304);
        }
        $user_phone = $request->post('user_phone');
        $sms_code = $request->post('sms_code');
        $result = $this->checkSms($user_phone,$sms_code,3);
        if($result != 1){
            return $this->response( $result, 406);
        }
        $password = $request->post('password');
        $userInfo = $request->userInfo;
        $userInfo->save(['login_password'=> md5(md5($password))]);
        return $this->response();
    }

    /**
     * 反馈
     * @param Request $request
     * @param UserSuggest $suggest
     * @return \think\Response
     */
    public function suggest(Request $request,UserSuggest $suggest)
    {
        // array_merge只能合并数组，其他报错
        $requestFile = $request->file() ?: [];
        // 验证
        if (!$suggest->check(array_merge($request->post(), $requestFile))) {
            return $this->response($suggest->getError(), 304);
        }
        // 用户信息
        $userInfo = $request->userInfo;
        // 反馈信息
        $data = [
            'user_id'=> $userInfo->user_id,
            'proposal_type'=> $request->post('proposal_type'),
            'proposal_content'=> $request->post('proposal_content'),
            'add_time'=> time(),
        ];
        // 图片
        $files = $request->file('images');
        if (!empty($files) && is_array($files)) {
            foreach ($files as $key=>$file) {
                $info = $file->move(ROOT_PATH . 'public/upload/image/proposal');
                $data['img'.($key + 1)] = 'public/upload/image/proposal/' . $info->getSaveName();
            }
        }
        // 保存反馈
        UserProposal::create($data);
        return $this->response('4444');
    }

    /**
     * 且验证支付密码，用户页面跳转验证
     * @param Request $request
     * @param UserValidate $userValidate
     * @return \think\Response
     */
    public function check_pay(Request $request, UserValidate $userValidate) {
        // 验证
        if (!$userValidate->scene('pay_check')->check($request->post())) {
            return $this->response($userValidate->getError(), 304);
        }
        return $this->response();
    }
    /**
     * 退出登录
     * @param Request $request
     * @return \think\Response
     */
    public function login_out(Request $request)
    {
        $userInfo = $request->userInfo;
        Token::checkToken($userInfo->user_id)->save(['token'=>'']);
        return $this->response([]);
    }
}