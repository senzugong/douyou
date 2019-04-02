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
     * 充值抖金
     * @return \think\Response
     */
    public function user_recharge(Request $request,WalletValidate $walletValidate) {
        // 验证数据
        if (!$walletValidate->scene('add_dw')->check(array_merge($request->post()))) {
            return  $this->response( $walletValidate->getError() ,304);
        }
        $userInfo = $request->userInfo;
        $wallet_id = $request->post('wallet_id');//钱包地址
        $dw_num = $request->post('dw_num'); //抖金数量
        $coin_num = $request->post('coin_num'); //货币数量
        $ratio = $request->post('ratio'); //兑换比例
        $user_wallet = UserWallet::where(['wallet_id'=>$wallet_id])->find();
        $coin = WalletType::where(['id'=>$user_wallet['wallet_type_id']])->find();
        $data = [
            'user_id'=>$userInfo['user_id'],
            'wallet_address'=>$user_wallet['wallet_address'],
            'type'=>1,
            'dw_money'=>$dw_num,
            'coin'=>$coin['wallet_name'],
            'coin_num'=>$coin_num,
            'coin_address'=>$coin['coin_address'],
            'ratio'=>$ratio,
            'status'=>0,
            'add_time'=>time(),
        ];
        ChangeLog::create($data);
        $data ['add_time'] =$this->getTime(time());
        return $this->response($data);
    }
    /**
     * 用户提现抖金
     * @return \think\Response
     */
    public function user_reflect(Request $request,WalletValidate $walletValidate){
        // 验证数据
        if (!$walletValidate->scene('reflect')->check(array_merge($request->post()))) {
            return  $this->response( $walletValidate->getError() ,304);
        }
        $userInfo = $request->userInfo;
        $wallet_id = $request->post('wallet_id');//钱包地址
        $dw_num = $request->post('dw_num'); //抖金数量
        $coin_num1 = bcadd($dw_num,bcmul($dw_num,0.05,4),4); //货币数量(体现扣取5%的手续费)
        $ratio = 1; //兑换比例
        $user_wallet = UserWallet::where(['wallet_id'=>$wallet_id])->find();
        $coin = WalletType::where(['id'=>$user_wallet['wallet_type_id']])->find();
        $data = [
            'user_id'=>$userInfo['user_id'],
            'wallet_address'=>$user_wallet['wallet_address'],
            'type'=>2,//体现
            'dw_money'=>$dw_num,
            'coin'=>$coin['wallet_name'],
            'coin_num'=>$dw_num,
            'coin_address'=>$coin['coin_address'],
            'ratio'=>$ratio,
            'status'=>0,
            'add_time'=>time(),
        ];
        Db::startTrans();
        try {
            ChangeLog::create($data);
            $data ['add_time'] =$this->getTime(time());
            $change_dw =bcsub($userInfo['dw_money'],$coin_num1,2);
            $userInfo->save(['dw_money'=>$change_dw]);
            Db::commit();
            // 成功返回数据
            return $this->response($data);
        }catch (\Exception $exception) {
        // 回退
        Db::rollback();
       // 失败返回
       return $this->response('交易失败', 405);
        }
    }
    /**
     * 当前的兑换比例(保留四位小数)
     * @return \think\Response
     */
    public function ratio(Request $request) {
////         验证数据
//        if (!$walletValidate->scene('recharge')->check(array_merge($request->post()))) {
//            return  $this->response( $walletValidate->getError() ,304);
//        }
        // 是兑换那种币种
        $userInfo = $request->userInfo;
        $dw_num = $request->post('dw_num'); //抖金数量
        if(!$dw_num){
            $list['wallet'] = UserWallet::where(['user_id'=>$userInfo['user_id'],'status'=>1])->field('wallet_id,wallet_type_id,wallet_address')->find();
            if(!$list){
                return $this->response('请输入抖金数量!',304);
            }
            $list['wallet'] ['wallet_name'] = WalletType::where(['id'=> $list['wallet'] ['wallet_type_id']])->value('wallet_name');
            return $this->response($list);
        }
        $wallet_id = $request->post('wallet_id',0); //抖金数量
        if($wallet_id){
            $wallet_type_id =  UserWallet::where(['user_id'=>$userInfo['user_id'],'wallet_id'=>$wallet_id])->value('wallet_type_id');
            $list['wallet'] = UserWallet::where(['user_id'=>$userInfo['user_id'],'wallet_id'=>$wallet_id])->field('wallet_id,wallet_type_id,wallet_address')->find();
        }else{
            $wallet_type_id = UserWallet::where(['user_id'=>$userInfo['user_id'],'status'=>1])->value('wallet_type_id');
            $list['wallet'] = UserWallet::where(['user_id'=>$userInfo['user_id'],'status'=>1])->field('wallet_id,wallet_type_id,wallet_address')->find();

        }
        $list['wallet']['wallet_name'] = WalletType::where(['id'=> $list['wallet']['wallet_type_id']])->value('wallet_name');
        if($wallet_type_id ==1)
        {//兑换比特币
            $url = "https://data.block.cc/api/v1/price?symbol_name=bitcoin";
            $data = Curl::get($url);
            $list['coin_price'] =  bcdiv($data["data"][0]['price_usd'],1,4);//当前的比特币的价格
            $list['need_dw'] = bcdiv($dw_num,$list['coin_price'],4).'BTC';//需要支付多少
            $list['coin_address'] = WalletType::where(['id'=>1,'status'=>0])->value('coin_address');
        }elseif($wallet_type_id ==2)
        {//兑换eth
            $url = "https://data.block.cc/api/v1/price?symbol_name=Ethereum";
            $data = Curl::get($url);
            $list['coin_price'] =  bcdiv($data["data"][0]['price_usd'],1,4);//当前的比特币的价格
            $list['need_dw'] = bcdiv($dw_num,$list['coin_price'],4).'ETH';//需要支付多少
            $list['coin_address'] = WalletType::where(['id'=>2,'status'=>0])->value('coin_address');
        }elseif($wallet_type_id ==3)
        {//兑换usdt
            $url = "https://data.block.cc/api/v1/price?symbol_name=Tether";
            $data = Curl::get($url);
            $list['coin_price'] =  bcdiv($data["data"][0]['price_usd'],1,4);//当前的比特币的价格
            $list['need_dw'] = $dw_num.'USDT';//需要支付多少
            $list['coin_address'] = WalletType::where(['id'=>3,'status'=>0])->value('coin_address');
        }elseif($wallet_type_id ==4)
        {//兑换rmz

        }
        // 获取当前货币价格
        return $this->response($list);
    }
    /**
     * 充值流程
     * @return \think\Response
     */
    public function process() {
        $result = <<<EOF
EOF;
        return $this->response($result);
    }
    /**
     * 兑换比例
     * @return \think\Response
     */
    public function change_ratio() {
        $result = <<<EOF
EOF;
        return $this->response($result);
    }
    /**
     * 常见问题
     * @return \think\Response
     */
    public function common_problem() {
        $result = <<<EOF
EOF;
        return $this->response($result);
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
        if($userInfo['is_examine'] !=1){
            return $this->response('未完成高级认证!', 304);
        }
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