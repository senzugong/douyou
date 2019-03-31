<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 14:00
 */

namespace app\api\controller;

use app\common\library\Curl;
use app\api\validate\UsdtValidate;
use app\common\model\MoneyLog;
use app\common\model\UsdtChangelog;
use app\common\model\UsdtLog;
use app\common\model\UserWallet;
use app\common\model\WalletType;
use controller\BasicApi;
use think\Db;
use think\Request;
//usdt 钱包控制器
class Usdt extends BasicApi
{

    /**
     * 充值usdt
     * @return \think\Response
     */
    public function usdt_recharge(Request $request,UsdtValidate $UsdtValidate){
        // 验证数据
        if (!$UsdtValidate->scene('add_usdt')->check($request->post())) {
            return  $this->response( $UsdtValidate->getError() ,304);
        }
        $userInfo = $request->userInfo;
        $wallet_id = $request->post('wallet_id');//钱包地址
        $usdt_num = $request->post('usdt_num'); //usdt数量
        $coin_num = $request->post('coin_num'); //货币数量
        $ratio = $request->post('ratio'); //兑换比例
        $user_wallet = UserWallet::where(['wallet_id'=>$wallet_id])->find();
        $coin = WalletType::where(['id'=>$user_wallet['wallet_type_id']])->find();
        $data = [
            'user_id'=>$userInfo['user_id'],
            'wallet_address'=>$user_wallet['wallet_address'],
            'type'=>1,
            'usdt_num'=>$usdt_num,
            'coin'=>$coin['wallet_name'],
            'coin_num'=>$coin_num,
            'coin_address'=>$coin['coin_address'],
            'ratio'=>$ratio,
            'status'=>0,
            'add_time'=>time(),
        ];
        UsdtChangelog::create($data);
        $data ['add_time'] =$this->getTime(time());
        return $this->response($data);
    }
    /**
     * 用户提现usdt
     * @return \think\Response
     */
    public function usdt_reflect(Request $request,UsdtValidate $UsdtValidate){
        // 验证数据
        if (!$UsdtValidate->scene('reflect')->check($request->post())) {
            return  $this->response( $UsdtValidate->getError() ,304);
        }
        $userInfo = $request->userInfo;
        $wallet_id = $request->post('wallet_id');//钱包地址
        $usdt_num = $request->post('usdt_num'); //usdt数量
        $coin_num = bcmul($request->post('coin_num'),0.05,4); //货币数量(体现扣取5%的手续费)
        $ratio = $request->post('ratio'); //兑换比例
        $user_wallet = UserWallet::where(['wallet_id'=>$wallet_id])->find();
        $coin = WalletType::where(['id'=>$user_wallet['wallet_type_id']])->find();
        $data = [
            'user_id'=>$userInfo['user_id'],
            'wallet_address'=>$user_wallet['wallet_address'],
            'type'=>2,//体现
            'usdt_num'=>$usdt_num,
            'coin'=>$coin['wallet_name'],
            'coin_num'=>$coin_num,
            'coin_address'=>$coin['coin_address'],
            'ratio'=>$ratio,
            'status'=>0,
            'add_time'=>time(),
        ];
        Db::startTrans();
        try {
            UsdtChangelog::create($data);
            $data ['add_time'] =$this->getTime(time());
            $change_dw =bcsub($userInfo['dw_usdt'],$usdt_num,2);
            $userInfo->save(['dw_usdt'=>$change_dw]);
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
        $userInfo = $request->userInfo;
        $dw_num = $request->post('usdt_num'); //抖金数量
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
            $list['coin_name'] = 'BTC';
            $list['need_dw'] = bcdiv($dw_num,$list['coin_price'],4);//需要支付多少
            $list['coin_address'] = WalletType::where(['id'=>1,'status'=>0])->value('coin_address');
        }elseif($wallet_type_id ==2)
        {//兑换eth
            $url = "https://data.block.cc/api/v1/price?symbol_name=Ethereum";
            $data = Curl::get($url);
            $list['coin_price'] =  bcdiv($data["data"][0]['price_usd'],1,4);//当前的比特币的价格
            $list['coin_name'] = 'ETH';
            $list['need_dw'] = bcdiv($dw_num,$list['coin_price'],4);//需要支付多少
            $list['coin_address'] = WalletType::where(['id'=>2,'status'=>0])->value('coin_address');
        }elseif($wallet_type_id ==3)
        {//兑换usdt
            $url = "https://data.block.cc/api/v1/price?symbol_name=Tether";
            $data = Curl::get($url);
            $list['coin_price'] =  bcdiv($data["data"][0]['price_usd'],1,4);//当前的比特币的价格
            $list['coin_name'] = 'USDT';
            $list['need_dw'] = $dw_num;//需要支付多少
            $list['coin_address'] = WalletType::where(['id'=>3,'status'=>0])->value('coin_address');
        }elseif($wallet_type_id ==4)
        {//兑换rmz

        }
        // 获取当前货币价格
        return $this->response($list);
    }
    /**
     * 当前的usdt兑换抖金
     * @return \think\Response
     */
    public function ratio_dw(Request $request,UsdtValidate $UsdtValidate) {
        // 验证数据
        if (!$UsdtValidate->scene('ratio_dw')->check($request->post())) {
            return  $this->response( $UsdtValidate->getError() ,304);
        }
        $usdt_num = $request->post('usdt_num'); //usdt数量
        $userInfo = $request->userInfo;
        if($userInfo['dw_usdt'] < $usdt_num){
            return $this->response('usdt数量不足!', 304);
        }
        $dw_num = 0; //货币数量(兑换不扣的手续费)
        $dw_usdt1  = bcsub($usdt_num,$dw_num,2);
        $dw_usdt = bcsub($userInfo['dw_usdt'],$dw_usdt1,2); //剩余usdt数量
        $dw_money = bcadd($userInfo['dw_money'],$dw_usdt1,2); //剩余蚪金数量
        $data = [ //usdt的记录
            'user_id'=>$userInfo['user_id'],
            'log_content' =>'usdt兑换蚪金',
            'type'=>1, //支出
            'chance_usdt'=>$usdt_num,
            'dw_usdt'=>$dw_usdt,
            'add_time' =>time()

        ];
        $data2 = [//蚪金的记录
            'user_id'=>$userInfo['user_id'],
            'log_content' =>'usdt兑换蚪金',
            'type'=>2,//收入
            'log_status'=>6,
            'chance_money'=>$dw_usdt1,
            'dw_money'=>$dw_money,
            'add_time' =>time()

        ];
        Db::startTrans(); //启动事务
        try {
            UsdtLog::create($data);
            MoneyLog::create($data2);
            $userInfo->save(['dw_money'=>$dw_money,'dw_usdt'=>$dw_usdt]);
            Db::commit(); //提交事务
            return $this->response();
        } catch (\PDOException $e) {
            Db::rollback(); //回滚事务
            return $this->response('兑换失败!', 407);
        }
    }
    /**
     * 当前的抖金兑换usdt
     * @return \think\Response
     */
    public function ratio_usdt(Request $request,UsdtValidate $UsdtValidate) {
        // 验证数据
        if (!$UsdtValidate->scene('ratio_usdt')->check($request->post())) {
            return  $this->response( $UsdtValidate->getError() ,304);
        }
        $dw_num = $request->post('dw_num'); //usdt数量
        $userInfo = $request->userInfo;
        if($userInfo['dw_money'] < $dw_num){
            return $this->response('抖金数量不足!', 304);
        }
        $dw_num1 = 0; //货币数量(兑换不扣的手续费)
        $dw_num2 = bcsub($dw_num,$dw_num1,2); //抖金数量
        $dw_money = bcsub($userInfo['dw_money'],$dw_num2,2); //剩余抖金的数量
        $dw_usdt = bcadd($userInfo['dw_usdt'],$dw_num2,2); //剩余usdt数量
        $data = [ //usdt的记录
            'user_id'=>$userInfo['user_id'],
            'log_content' =>'抖金兑换usdt',
            'type'=>2, //收入
            'chance_usdt'=>$dw_num2,
            'dw_usdt'=>$dw_usdt,
            'add_time' =>time()

        ];
        $data2 = [//蚪金的记录
            'user_id'=>$userInfo['user_id'],
            'log_content' =>'抖金兑换usdt',
            'type'=>1,//支出
            'log_status'=>7,
            'chance_money'=>$dw_num,
            'dw_money'=>$dw_money,
            'add_time' =>time()

        ];
        Db::startTrans(); //启动事务
        try {
            UsdtLog::create($data);
            MoneyLog::create($data2);
            $userInfo->save(['dw_money'=>$dw_money,'dw_usdt'=>$dw_usdt]);
            Db::commit(); //提交事务
            return $this->response();
        } catch (\PDOException $e) {
            Db::rollback(); //回滚事务
            return $this->response('兑换失败!', 407);
        }
    }
}