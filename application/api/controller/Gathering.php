<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 10:28
 */

namespace app\api\controller;

use app\api\validate\GatheringValidate;
use app\common\model\UserBank;
use app\common\model\UserGathering;
use controller\BasicApi;
use think\Config;
use think\Db;
use think\Request;
/**
 * Class Wave
 * 添加支付方式控制器
 * @package app\api\controller
 */
class Gathering extends BasicApi
{
    /**
     * 添加收款方式
     * @param Request $request
     * @param UserValidate $userValidate
     * @return \think\Response
     */
    public function add_gathering(Request $request, GatheringValidate $GatheringValidate) {
        $userInfo = $request->userInfo;
        if($userInfo['is_examine'] !=1)
        {
            return  $this->response( '您还未通过高级认证!' ,304);
        }
        $type_id = $request->post('type_id');// 1银行卡  2微信 3支付宝
        if($type_id == 1)//银行卡
        {
            // 验证
            if (!$GatheringValidate->scene('bank')->check($request->post())) {
                return $this->response($GatheringValidate->getError(), 304);
            }
            $bank_name = $request->post('bank_name');
            $bank_branch = $request->post('bank_branch');
            $bank_number = $request->post('bank_number');
            $data = [
                'user_id'=>$userInfo['user_id'],
                'true_name'=>$userInfo['true_name'],
                'bank_branch'=>$bank_branch,
                'bank_name'=>$bank_name,
                'bank_number'=>$bank_number,
                'add_time'=>time(),
            ];
          $result = UserBank::create($data);
        }elseif(in_array($type_id,[2,3]))//微信2 3支付宝
        {
            // array_merge只能合并数组，其他报错
            $requestFile = $request->file() ?: [];
            // 验证
            if (!$GatheringValidate->scene('wx_gather')->check($request->post(),$requestFile)) {
                return $this->response($GatheringValidate->getError(), 304);
            }
            $data['gathering_name'] = $request->post('gathering_name');
            $file = $request->file('gathering_img');
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload/image/gathering');
            $data['gathering_img'] = 'public/upload/image/gathering/' . $info->getSaveName();
            $data['type'] = $type_id;
            $data['user_id'] = $userInfo['user_id'];
            $data['true_name'] =$userInfo['true_name'];
            $data['add_time'] =time();
            $result = UserGathering::create($data);
        }
        if($result){
            return $this->response();
        }else{
            return $this->response('添加失败，请联系管理员!',304);
        }

    }

    /** 设置默认
     * @param Request $request
     * @param GatheringValidate $GatheringValidate
     * @return \think\Response
     */
    public function set_default(Request $request,GatheringValidate $GatheringValidate){
        // 验证
        if (!$GatheringValidate->scene('default')->check($request->post())) {
            return $this->response($GatheringValidate->getError(), 304);
        }
        $userInfo = $request->userInfo;
        $type_id = $request->post('type_id');
        $gathering_id = $request->post('payment_id');
        $is_default = $request->post('is_default',1);
        if($is_default==1){
            if($type_id == 1){
                UserBank::where(['user_id'=>$userInfo['user_id'],'bank_id'=>$gathering_id])->update(['status'=>1]);
                UserBank::where("user_id={$userInfo['user_id']} and bank_id !=$gathering_id")->update(['status'=>0]);
            }else{
                UserGathering::where(['user_id'=>$userInfo['user_id'],'type'=>$type_id,'gathering_id'=>$gathering_id])->update(['status'=>1]);
                UserGathering::where("user_id={$userInfo['user_id']} and type =$type_id  and gathering_id !=$gathering_id")->update(['status'=>0]);
            }
        }else{
            if($type_id == 1){
                UserBank::where(['user_id'=>$userInfo['user_id'],'bank_id'=>$gathering_id])->update(['status'=>0]);
            }else{
                UserGathering::where(['user_id'=>$userInfo['user_id'],'type'=>$type_id,'gathering_id'=>$gathering_id])->update(['status'=>0]);
            }
        }

        return $this->response();
    }
    /** 删除
     * @param Request $request
     * @param GatheringValidate $GatheringValidate
     * @return \think\Response
     */
    public function del_gathering(Request $request,GatheringValidate $GatheringValidate){
        // 验证
        if (!$GatheringValidate->scene('default')->check($request->post())) {
            return $this->response($GatheringValidate->getError(), 304);
        }
        $userInfo = $request->userInfo;
        $type_id = $request->post('type_id');
        $gathering_id = $request->post('payment_id');
        if($type_id == 1){
            UserBank::where(['user_id'=>$userInfo['user_id'],'bank_id'=>$gathering_id])->delete();
        }else{
            UserGathering::where(['user_id'=>$userInfo['user_id'],'type'=>$type_id,'gathering_id'=>$gathering_id])->delete();
        }
        return $this->response();
    }
    /**
     * 收款列表
     * @param Request $request
     * @param UserValidate $userValidate
     * @return \think\Response
     */
    public function gathering_list(Request $request)
    {
        $userInfo = $request->userInfo;
        if($userInfo['is_examine']==0)
        {
            return  $this->response( '您还未通过高级认证!' ,304);
        }
        //银行卡列表
       $list['bank'] = $userInfo->userBank()
           ->select();
        foreach($list['bank'] as &$v){
            $v['type'] =1;
        }
        //微信支付宝列表
       $list['gathering'] = $userInfo->userGathering()
           ->select();
        foreach($list['gathering'] as &$v){
            $v['gathering_img'] =  Config::get('image_url').$v['gathering_img'];
        }
        return $this->response($list);
    }
}