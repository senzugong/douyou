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
use app\common\model\Message;
use app\common\model\Token;
use app\common\model\UsdtChangelog;
use app\common\model\UsdtLog;
use app\common\model\UsdtOrder;
use app\common\model\UserProposal;
use app\common\model\UserWallet;
use controller\BasicApi;
use app\api\validate\UserValidate;
use app\common\model\UserAttestation;
use think\Config;
use think\Db;
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
        $userInfo['order_num'] = UsdtOrder::where("(mall_user_id = {$userInfo['user_id']} or user_id = {$userInfo['user_id']}) and status IN (0,1)")->count();
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
        if($userInfo['is_examine'] == 1 || $userInfo['is_examine']==2){
            $examine_list =  Db::table('dw_user_examine')->where(['user_id'=>$userInfo['user_id']])->order('examine_id desc')->find();
            $userInfo['img1'] = $examine_list['img1'] ? Config::get('image_url') . $examine_list['img1'] : '';
            $userInfo['img2'] = $examine_list['img2'] ? Config::get('image_url') . $examine_list['img2'] : '';
            $userInfo['img3'] = $examine_list['img3'] ? Config::get('image_url') . $examine_list['img3'] : '';

        }
        //收入
        $rise = UsdtLog::where(['type'=>2,'user_id'=>$userInfo['user_id']])->whereTime('add_time', 'today')->select();
        $all_expenditure = 0.00;
        foreach($rise as &$v){
            $all_expenditure = bcadd($all_expenditure,$v['chance_usdt'],2) ;
        };
        //支出
        $fill = UsdtLog::where(['type'=>1,'user_id'=>$userInfo['user_id']])->whereTime('add_time', 'today')->select();
        $all_fill = 0.00;
        foreach($fill as &$v){
            $all_fill = bcadd($all_fill,$v['chance_usdt'],2) ;
        };
        $userInfo['today_money'] = bcsub($all_expenditure,$all_fill,2);
        // 可提现余额
        $userInfo['allow_usdt'] = bcdiv($userInfo['dw_usdt'], 1.05, 4);
        // 冻结资金
        $sell_freeze = '0.00';
        $usdtMall = $userInfo->usdtMall()
            ->whereIn('status', [0,1,2])
            ->where("type = 1")
            ->select();
        foreach ($usdtMall as &$val){
            $sell_freeze = bcadd($sell_freeze, $val['usdt_num'],2);
//            $sell_freeze = bcadd($sell_freeze, bcsub($val['usdt_num'], $val['over_usdt'], 2), 2);
        }
        $ids = Db::table('dw_usdt_mall')->where("status IN (1,2) and user_id ={$userInfo['user_id']}  and type = 1")->field('GROUP_CONCAT(mall_id) as ids')->find();
//        var_dump($ids);die;
        $success_usdt = '0.00';
        if($ids['ids']){
           $list = Db::table('dw_usdt_order')->where("mall_id IN ({$ids['ids']}) and status = 2")->select();
           foreach($list as &$v){
               $success_usdt = bcadd($success_usdt, $v['order_usdt_num'], 2);
           }
        }
        $userInfo['sell_freeze'] = bcsub($sell_freeze,$success_usdt,2);
        $fetch_freeze = '0.00';
        $usdtChange = UsdtChangelog::where(['user_id'=> $userInfo['user_id'], 'type'=> 2, 'status'=> 0])
            ->select();
        foreach ($usdtChange as $val) {
            $fetch_freeze = bcadd($fetch_freeze, $val['usdt_num'], 2);
        }
        $userInfo['fetch_freeze'] = $fetch_freeze;
        // 未读消息条数
        $userInfo['message_count'] = Message::where(['user_id'=> $userInfo['user_id'], 'is_read'=> 0])->count();
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
            if($imgOld !== 'erweima/avatar.png'){
                // 将旧头像删除
                if ($result && is_file(ROOT_PATH . $imgOld)) {
                    @unlink(ROOT_PATH . $imgOld);
                }
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
        $result = UserAttestation::where(['user_id'=>$userInfo['user_id'],'status'=>2])->find();
        if($result){
            $data['status'] = 0;
            Examine::where(['user_id'=>$userInfo['user_id']])->update($data);
        }else{
            Examine::create($data);
        }
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

    /**商家认证
     * @param Request $request
     * @return \think\Response
     */
    public function business(Request $request){
        $usdt_num = $request->post('usdt_num',200);
        $userInfo = $request->userInfo;
        if($usdt_num >$userInfo['dw_usdt']){
            return $this->response('您的余额不足！',304);
        }
        $change_dw = bcsub($userInfo['dw_usdt'],$usdt_num,4);
        $userInfo->save(['dw_usdt'=>$change_dw,'is_business'=>2,'business_usdt'=>$usdt_num]);
        // 添加日志
        UsdtLog::create([
            'user_id'=> $userInfo->user_id,
            'log_content'=> '商家认证扣除USDT',
            'type'=> 1,
            'log_status'=>10,
            'chance_usdt'=>$usdt_num,
            'dw_usdt'=> $userInfo->dw_usdt,
            'add_time'=> time(),
        ]);
        return $this->response();
    }

    /**
     * @param Request $request
     * @return \think\Response
     */
    public function business_usdt(Request $request){
        $business_usdt = Db::table('dw_business_usdt')->where(['id'=>1])->value('business_usdt');
        return $this->response($business_usdt);
    }
    /**商家认证取消
     * @param Request $request
     * @return \think\Response
     */
    public function cancel_business(Request $request){
        $userInfo = $request->userInfo;
       if($userInfo['is_business'] != 1){
           return $this->response('你还不是认证商家！',304);
       }
            $change_dw = bcadd($userInfo['dw_usdt'],$userInfo['business_usdt'],4);
            $business_usdt = $userInfo['business_usdt'];
            $userInfo->save(['dw_usdt'=>$change_dw,'is_business'=>0,'business_usdt'=>0]);
        // 添加日志
        UsdtLog::create([
            'user_id'=> $userInfo->user_id,
            'log_content'=> '商家取消返回USDT',
            'type'=> 2,
            'log_status'=>10,
            'chance_usdt'=>$business_usdt,
            'dw_usdt'=> $userInfo->dw_usdt,
            'add_time'=> time(),
        ]);
        return $this->response();
    }
    /**
     * @param Request $request邀请详情
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function invite(Request $request){
        $userInfo = $request->userInfo;
        //邀请的好友集合
        $ids = Db::table('dw_users')->where(['invite_user'=>$userInfo['user_id']])->field('GROUP_CONCAT(user_id) as ids')->find();
//        var_dump($ids['ids']);die;
        if($ids['ids']){
            $result = Db::table('dw_invite_reward')->where(['user_id'=>$userInfo['user_id']])->order('id desc')->find();
            if($result){
                $list1 = Db::table('dw_btc_order')->where(" order_id > {$result['order_id']} and user_id IN ({$ids['ids']})")->select();
            }else{
                $list1 = Db::table('dw_btc_order')->where("user_id IN ({$ids['ids']})")->select();
            }
            $reward = 0.0000;
            foreach($list1 as &$v){
                $reward = bcadd($reward,bcdiv($v['sx_fee'],2,4),4);
            }
        $list['invite_usdt']  =  $reward;
        }else{
            $list['invite_usdt'] = 0.0000;
        }
        $list['invite_count'] = Db::table('dw_users')->where(['invite_user'=>$userInfo['user_id']])->count();
        return $this->response($list);
    }

    /**
     * @param Request $request邀请好友列表
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function invite_list(Request $request){
        $userInfo = $request->userInfo;
        $list = Db::table('dw_users')->where(['invite_user'=>$userInfo['user_id']])->field('user_phone,user_name,user_avatar,add_time')->select();
        foreach ($list as &$v){
            $v['user_avatar'] =  $v['user_avatar'] ? Config::get('image_url') .  $v['user_avatar'] : '';
            if(!$v['user_name']){
                $v['user_name'] = substr_replace($v['user_phone'] , '****', 3, 4);
            }
            $v['add_time'] =  $this->getTime($v['add_time']) ;
        }
        return $this->response($list);


    }
    /**
     * @param Request $request领取
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function invite_reward(Request $request){
        $userInfo = $request->userInfo;
        $reward = $request->post('reward');
        $order = Db::table('dw_btc_order')->order('order_id desc')->find();
        if($reward <= 0){
            return $this->response('领取金额必须大于0',304);
        }
        Db::startTrans();
        try{
            $change_usdt = bcadd($reward,$userInfo['dw_usdt'],4);
            $userInfo->save(['dw_usdt'=>$change_usdt]);
            // 添加日志
            Db::table('dw_invite_reward')->insert([
                'user_id'=> $userInfo->user_id,
                'order_id'=>$order['order_id'],
                'add_time'=> time(),
            ]);
            // 添加日志
            UsdtLog::create([
                'user_id'=> $userInfo->user_id,
                'log_content'=> '邀请好友奖励',
                'type'=> 2,
                'log_status'=>9,
                'chance_usdt'=>$reward,
                'dw_usdt'=> $userInfo->dw_usdt,
                'add_time'=> time(),
            ]);
            Db::commit();
            return $this->response();
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('领取失败'.$exception->getMessage(),304);
        }
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