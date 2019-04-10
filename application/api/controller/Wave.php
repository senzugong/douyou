<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 10:28
 */

namespace app\api\controller;
use app\common\library\Curl;
use app\common\model\UsdtLog;
use controller\BasicApi;
use think\Config;
use think\Db;
use think\Request;

/**
 * Class Wave
 * 猜涨跌控制器
 * @package app\api\controller
 */
class Wave extends BasicApi
{
    /**实时购买
     * @param Request $request
     */
    public function buy(Request $request){
        $result = $this->get_open();
        if(!$result){
            return $this->response('已休市!',304);
        }
        $userInfo = $request->userInfo;
        $data['style'] = $request->post('style',1);//涨
        $data['type'] = 1;
        $data['buy_price'] = $request->post('buy_price');
        if(!$data['buy_price']){
            return $this->response('当前btc价格未传!',304);
        }
        $data['add_time'] = time();
        $data['usdt_fee'] = $request->post('usdt_fee',50);
        $data['sx_fee'] = bcmul($data['usdt_fee'],0.02,4);
        $data['order_fee'] = $data['usdt_fee'];
        $data['user_id'] = $userInfo['user_id'];
        Db::startTrans();
        try {
            Db::table('dw_btc_order')->insert($data);
            $fee = bcadd($data['usdt_fee'],$data['sx_fee'],4);
            $userInfo->save(['dw_usdt'=> bcsub($userInfo->dw_usdt, $fee, 4)]);
            // 添加日志
            UsdtLog::create([
                'user_id'=> $userInfo->user_id,
                'log_content'=> '实时猜涨跌',
                'type'=> 1,
                'log_status'=>5,
                'chance_usdt'=> $fee,
                'dw_usdt'=> $userInfo->dw_usdt,
                'add_time'=> time(),
            ]);
            // 提交
            Db::commit();
            return $this->response();
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('下单失败',304);
        }

    }
    /**点位购买
     * @param Request $request
     */
    public  function buy_point(Request $request){
        $result = $this->get_open();
        if(!$result){
            return $this->response('已休市!',304);
        }
        $userInfo = $request->userInfo;
        $data['style'] = $request->post('style',1);//涨 2跌
        $data['type'] = 2;
        $data['buy_price'] = $request->post('buy_price');
        if(!$data['buy_price']){
            return $this->response('当前btc价格未传!',304);
        }
        $data['time_id'] = $request->post('time_id',1);
        $data['add_time'] = time();
        $data['usdt_fee'] = $request->post('usdt_fee',50);
        $data['buy_quiz'] = $request->post('buy_quiz',1);
        $usdt =  bcmul($data['usdt_fee'],$data['buy_quiz'],4);
        $data['sx_fee'] = bcmul($usdt,0.02,4);
        $data['order_fee'] = bcmul($data['usdt_fee'], $data['buy_quiz'],4);
        $data['user_id'] = $userInfo['user_id'];
        $data['btc_point'] = $request->post('btc_point','3/3');
        $rise_point = explode('/',$data['btc_point']);
        if($data['style'] == 1){
            $data['rise_price'] =  bcadd($data['buy_price'],$rise_point[0],4);
            $data['fill_price'] =  bcsub($data['buy_price'],$rise_point[1],4);
        }else{
            $data['rise_price'] =  bcsub($data['buy_price'],$rise_point[0],4);
            $data['fill_price'] =  bcadd($data['buy_price'],$rise_point[1],4);
        }
        $time = Db::table('dw_basic_time')->where(['id'=>$data['time_id']])->value('settletment_time');
        $data['end_time'] = bcadd($data['add_time'],$time);
        Db::startTrans();
        try {
            Db::table('dw_btc_order')->insert($data);
            $fee = bcadd($usdt,$data['sx_fee'],4);
            $userInfo->save(['dw_usdt'=> bcsub($userInfo->dw_usdt, $fee, 4)]);
            // 添加日志
            UsdtLog::create([
                'user_id'=> $userInfo->user_id,
                'log_content'=> '实时猜涨跌',
                'type'=> 1,
                'log_status'=>5,
                'chance_usdt'=> $fee,
                'dw_usdt'=> $userInfo->dw_usdt,
                'add_time'=> time(),
            ]);
            // 提交
            Db::commit();
            return $this->response();
        } catch (\Exception $exception) {
            // 回滚
            Db::rollback();
            return $this->response('下单失败',304);
        }
    }

    /**
     * @param Request $request持仓记录
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public  function order_list(Request $request){
        $userInfo = $request->userInfo;
        $type = $request->post('type',1);
        $list = Db::table('dw_btc_order')->where(['user_id'=>$userInfo['user_id'],'type'=>$type,'is_win'=>0])->order('order_id desc')->select();
        foreach($list as &$v){
          $v['add_time'] = $this->getTime($v['add_time']);
        }
        return $this->response($list);

    }

    /**
     * @param Request $request交易历史记录
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function history_order(Request $request)
    {
        $userInfo = $request->userInfo;
        $type = $request->post('type',1);
        $page = $request->post('page',1);
        $list = Db::table('dw_btc_order')->where("user_id={$userInfo['user_id']} and type=$type and is_win !=0")->order('order_id desc')->page($page)->select();
        foreach($list as &$v){
            $v['add_time'] = $this->getTime($v['add_time']);
            $v['end_time'] =$v['end_time']? $this->getTime($v['end_time']):'';
        }
        return $this->response($list);
    }
    /**下注信息
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
   public function basic_usdt(Request $request){
     $type = $request->post('type',1);//实时  2点位
     $list['usdt'] = Db::table('dw_basic_usdt')->where(['type'=>$type])->field('id,usdt_num')->select();
     if($type==1){
         $list['time'] = Db::table('dw_basic_time')->select();
     }else{
         $list['quiz'] = Db::table('dw_basic_quiz')->field('id,quiz_num')->select();
         $list['point'] = Db::table('dw_basic_point')->select();
     }
     $list['recharge'] = 2;
     $list['btc_price'] = Db::table('dw_btc')->order('btc_id desc')->value('btc_price');
       $week = date("w");//今天周几
       if($week == 0){
           $list['opentime'] = Db::table('dw_basic_opentime')->where(['id'=>7])->find();
       }else{
           $list['opentime'] = Db::table('dw_basic_opentime')->where(['id'=>$week])->find();
       }

       $time = time();
       $start_time = strtotime(date('Y-m-d').' '.$list['opentime']['start_time']);

       $end_time = strtotime(date('Y-m-d').' '.$list['opentime']['end_time']);
       if($time>$start_time && $time< $end_time){
           $list['is_open'] = 1;
       }else{
           $list['is_open'] = 0;
       }
       return $this->response($list);
   }

    /**btc的k线图
     * @param Request $request
     * @return \think\Response
     */
   public function btc_list(Request $request){
       $type = $request->post('type','1min');
       $size = $request->post('size','1000');
       $url = "http://api.zb.cn/data/v1/kline?market=btc_usdt&type=$type&size=$size";
       // 获取数据
       $data = Curl::get($url);
       return $this->response($data);
   }


    public function get_open(){
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


    /**
     * 返回猜涨跌规则
     * @return \think\Response
     */
    public function rule() {
        $result = <<<EOF
 <div class="rule-box"><div class="rule-tile padding-bottom-20"><span>参与资格</span></div><div class="rule-text padding-bottom-23">活动仅限蚪游账户用户参加。</div><div class="rule-tile padding-bottom-20"><span>活动时间</span></div><div class="rule-text padding-bottom-23">2019年3月1日至2019年3月31</div><div class="rule-tile padding-bottom-20"><span>活动规则</span></div><div class="rule-text color-active padding-bottom-5">1. 参与规则</div><div class="rule-text padding-bottom-30">活动期间，BTC涨跌的竞猜只能竞猜下一期涨跌，如您在40期内，只能投41期竞猜，以此类推。每次竞猜<span class="color-active">最低使用20蚪金，最高使用200蚪金，</span>每次做完竞猜后不可修改，蚪金使用后无法退还。</div><div class="rule-text color-active padding-bottom-5">2. 开奖规则</div><div class="rule-text padding-bottom-30">在当前竞猜期数结束时的3分钟左右，竞猜结果揭晓。若竞猜成功，则按参与蚪金所在竞猜方的蚪金池的比列，瓜分当期所有竞猜蚪金。所获蚪金一般在当期竞猜结果揭晓后立即到账。</div><div class="rule-text color-active padding-bottom-5">3. 特别须知</div><div class="rule-text padding-bottom-30">1) 禁止利用竞猜活动进行赌博活动等违法违规行为，若在活动前后发现违法违规行为，不仅限于冒用欺诈、赌博、传播色情、冒用他人身份等用户，蚪游有权取消活动参与资格及领奖资格。</div><div class="rule-text padding-bottom-30">2) 请避免违规刷蚪金的行为（包括但不限于虚假交易、恶意拆单、退款），如发现刷蚪金行为，蚪游有权取消活动参与资格及领奖资格。</div><div class="rule-text">3) 大盘不涨不跌视为上涨。</div></div>
EOF;
        return $this->response($result);
    }
}