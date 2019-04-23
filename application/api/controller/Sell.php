<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/19
 * Time: 10:36
 */

namespace app\api\controller;

use app\common\library\JgPush;
use app\common\model\UsdtLog;
use app\common\model\UsdtMall;
use app\common\model\UsdtOrder;
use controller\BasicApi;
use think\Config;
use think\Db;
use think\Request;
use app\common\model\Message;
use app\common\model\User;

/**
 * Class Sell
 * 出售USDT（发布购买USDT）
 * @package app\api\controller
 */
class Sell extends BasicApi
{
    /**
     * 发布收购USDT的挂单列表
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(Request $request) {
        // 分页
        $page = $request->param('page', 1);
        // 获取列表
        $mall = UsdtMall::alias('a')
            ->join('dw_users b', 'a.user_id = b.user_id')
            ->where("a.status IN (0,1)")
            ->where(['a.type'=> 2])
            ->field('b.user_name,b.user_avatar,a.*')
            ->order('a.add_time desc')
            ->page($page)
            ->select();
        foreach($mall as &$v){
            $v['user_avatar'] = Config::get('image_url').$v['user_avatar'];
            $v['surplus_usdt'] = bcsub($v['usdt_num'],$v['over_usdt'],4);
        }
        // 总条数
        $total = UsdtMall::where(['type'=> 2])->count();
        // 整合数据
        $data = [
            'total'=> $total,
            'list'=> $mall,
        ];
        return $this->response($data);
    }

    /**
     * 发布收购USDT
     * @param Request $request
     * @return \think\Response
     */
    public function upper(Request $request) {
        $result = $this->get_open();
        if(!$result){
            return $this->response('已休市!',304);
        }
        // 用户信息
        $userInfo = $request->userInfo;
        if(!$userInfo['pay_password']){
            return $this->response('请先设置支付密码!', 304);
        }
        $count = UsdtMall::where("'user_id'={$userInfo['user_id']}  and status !=3")->count();
        if($count >=2 ){
            return $this->response('当前只能发布两条数据', 304);
        }
        // 发布数量
        $usdt_num = $request->post('usdt_num');
        if($usdt_num <1){
            return $this->response('出售usdt数量最低为1', 304);
        }
        $usdt_price = $request->post('usdt_price',6.70); //usdt单价
        $mix_rmb = $request->post('mix_rmb',$usdt_price); //最小的交易金额
        $max_rmb = $request->post('max_rmb',bcmul($usdt_num,$usdt_price, 4)); //最大的交易金额
        if($mix_rmb >= $max_rmb){
            return $this->response('最大交易额小于等于最小交易额!', 304);
        }
        // 支付方式
        $wx_gathering = $request->post('wx_gathering',''); //微信id
        $bank_gathering = $request->post('bank_gathering',''); //银行id
        $zfb_gathering = $request->post('zfb_gathering',''); //支付宝id
        if(!$wx_gathering && !$bank_gathering && !$zfb_gathering){
            return $this->response('支付方式必选一个', 304);
        }
        // 发布挂单
        Db::startTrans();
        try {
            UsdtMall::create([
                'user_id'=> $userInfo['user_id'],
                'usdt_num'=> $usdt_num,
                'usdt_price'=> $usdt_price, // 价格
                'mix_rmb'=> $mix_rmb, // 最小交易量
                'max_rmb'=> $max_rmb, // 最大交易量
                'wx_gathering' => $wx_gathering,
                'bank_gathering' => $bank_gathering,
                'zfb_gathering' => $zfb_gathering,
                'type'=> 2, // 1发布出售USDT  2发布购买
                'add_time'=> time(),
            ]);
            // 提交
            Db::commit();
            return $this->response();
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('发布失败', 306);
        }
    }

    /**
     * 下单出售USDT
     * @param Request $request
     * @return \think\Response
     * @throws \think\exception\DbException
     */
    public function shopping(Request $request) {
        $result = $this->get_open();
        if(!$result){
            return $this->response('已休市!',304);
        }
        // 用户信息
        $userInfo = $request->userInfo;
        if(!$userInfo['pay_password']){
            return $this->response('请先设置支付密码!', 304);
        }
        $mall_id = $request->post('mall_id');
        $money = $request->post('money');
        // 发布单
        $usdtMall = UsdtMall::get($mall_id);
        if (!$mall_id || !$money || !$usdtMall) {
            return $this->response('参数错误!', 304);
        }
        if($money > bcsub($usdtMall['usdt_num'],$usdtMall['over_usdt'],4))
        {
            return $this->response('剩余USDT库存不足!', 304);
        }
        // 用户是否有充足的USDT
        if ($userInfo['dw_usdt'] < $money) {
            return $this->response('账户USDT不足!', 304);
        }
        // 订单号
        $order_sn = sha1($userInfo['user_id'] . $mall_id . $money . microtime());
        // 下单出售USDT
        Db::startTrans();
        try {
            // 购买数量扣减
            $overNum = bcadd($usdtMall['over_usdt'], $money, 4);
            $usdtMall->save([
                'over_usdt'=> $overNum,
                'status'=> $overNum >= $usdtMall['usdt_num'] ? 2 : 1,
            ]);
            // 扣除用户的USDT
            $userInfo->save([
                'dw_usdt'=> bcsub($userInfo['dw_usdt'], $money, 4),
            ]);
            // USDT日志
            UsdtLog::create([
                'user_id'=> $userInfo['user_id'],
                'log_content'=> '场外交易出售USDT',
                'usdt_charge_type'=> 2,
                'type'=> 1, // 1 支出 2转入
                'log_status'=> 1, // 1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到 8USDT充值
                'chance_usdt'=> $money,
                'dw_usdt'=> $userInfo['dw_usdt'],
                'add_time'=> time(),
            ]);
            // 生成订单
            $order_id = UsdtOrder::insertGetId([
                'order_sn' => $order_sn,
                'mall_id' => $mall_id,
                'mall_user_id' => $usdtMall['user_id'],
                'user_id' => $userInfo['user_id'],
                'order_money' => bcmul($money,$usdtMall['usdt_price'],4),
                'order_usdt_num' => $money,
                'gathering_id' => $request->post('gathering_id'),
                'pay_type' => $request->post('pay_type'),
                'add_time'=>time()
            ]);
            // 消息通知
            Message::create([
                'user_id'=> $usdtMall['user_id'],
                'trigger_id'=> $order_id,
                'title'=> '您发布收购USDT已被下单',
                'content'=> "您发布收购USDT已被下单（{$order_sn}），赶紧去看看吧！",
                'msg_type'=> 4, // 类型 1幸运转盘 2号码竞猜 3猜涨跌 4场外交易 5活动的消息 6提币的消息
                'add_time'=> time(),
            ]);
            // 推送消息
            JgPush::send($usdtMall['user_id'], '您发布出售USDT已被下单');
            // 提交
            Db::commit();
            return $this->response();
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('购买失败', 306);
        }
    }

    /**
     * 是否休市
     * @return int
     */
    protected function get_open(){
        $week = date("w");//今天周几
        if($week == 0){
            $list = Db::table('dw_basic_opentime')->where(['id'=>7])->find();
        }else{
            $list = Db::table('dw_basic_opentime')->where(['id'=>$week])->find();
        }
        $time = time();
        $start_time = strtotime(date('Y-m-d').' '.$list['start_time']);

        $end_time = strtotime(date('Y-m-d').' '.$list['end_time']);
        if($time>$start_time && $time< $end_time){
            return 1;
        }else{
            return 0;
        }
    }
}