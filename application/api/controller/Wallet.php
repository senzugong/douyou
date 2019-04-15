<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 15:27
 */

namespace app\api\controller;
use app\common\model\ChangeLog;
use app\common\library\Curl;
use app\api\validate\WalletValidate;
use app\common\model\UserWallet;
use app\common\model\WalletType;
use controller\BasicApi;
use think\Request;
use think\db;
/**
 * Class Wallet
 * 用户钱包管理
 * @package app\api\controller
 */
class Wallet extends BasicApi
{
    /**
     * 钱包列表
     * @param Request $request
     * @return \think\Response
     */
    public function card(Request $request) {
        // 获取用户钱包列表
        $list = $request->userInfo->userWallet;
        foreach ($list as &$v){
            $v['coin_name'] = WalletType::where(['id'=>$v['wallet_type_id']])->value('wallet_name');
        }
        return $this->response($list);
    }
    /**
     * 设置默认
     * @param Request $request
     * @return \think\Response
     */
    public function  default_card(Request $request,WalletValidate $walletValidate) {
        // 验证数据
        if (!$walletValidate->scene('wallet')->check(array_merge($request->post()))) {
            return  $this->response( $walletValidate->getError() ,304);
        }
        $wallet_id = $request->post('wallet_id');//钱包id
        $is_default = $request->post('is_default',1);//1默认 2是取消
        //找用户的全部钱包
        if($is_default == 1){
            $request->userInfo->userWallet()->where("wallet_id != $wallet_id")->update(['status'=>0]);
            UserWallet::where(['wallet_id'=>$wallet_id])->update(['status'=>1]);
        }else{
            UserWallet::where(['wallet_id'=>$wallet_id])->update(['status'=>0]);
        }

        return $this->response();
    }
    /**
     * 删除钱包
     * @param Request $request
     * @return \think\Response
     */
    public function  del_card(Request $request,WalletValidate $walletValidate) {
        // 验证数据
        if (!$walletValidate->scene('wallet')->check(array_merge($request->post()))) {
            return  $this->response( $walletValidate->getError() ,304);
        }
        $wallet_id = $request->post('wallet_id');//钱包id
        UserWallet::where(['wallet_id'=>$wallet_id])->delete();
        return $this->response();
    }
    /**
     * 钱包类型
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function type() {
        // 钱包类型
        $list = WalletType::select();
        return $this->response($list);
    }
    /**
     * 添加钱包地址
     * @param Request $request
     * @param WalletValidate $validate
     * @return \think\Response
     */
    public function add_card(Request $request, WalletValidate $validate) {
        // 验证
        if (!$validate->scene('add')->check($request->post())) {
            return $this->response($validate->getError(), 304);
        }
        $userInfo = $request->userInfo;
//        if($userInfo['is_examine'] !=1){
//            return $this->response('未完成高级认证!', 304);
//        }
        $wallet_address = $request->post('wallet_address');

        $result =UserWallet::where(['wallet_address'=>$wallet_address,'user_id'=>$userInfo['user_id']])->find();
        if($result){
            return $this->response('该钱包地址已存在!',304);
        }
        // 添加新的地址到钱包
        UserWallet::create([
            'user_id'=> $userInfo->user_id,
            'wallet_type_id'=> $request->post('wallet_type_id'),
            'wallet_address'=> $request->post('wallet_address'),
            'add_time'=> time(),
        ]);
        return $this->response();
    }

}