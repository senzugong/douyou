<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 10:28
 */

namespace app\api\controller;

use app\api\validate\WaveValidate;
use app\common\model\Btc;
use app\common\model\BtcBasic;
use app\common\model\BtcPost;
use app\common\model\GamePostdata;
use app\common\model\MoneyLog;
use app\common\model\TurntableLog;
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
    /**
     * 购买涨跌
     * @param Request $request
     * @param WaveValidate $validate
     * @return \think\Response
     */
    public function buy(Request $request, WaveValidate $validate) {
        // 验证
        if (!$validate->scene('buy')->check($request->post())) {
            return $this->response($validate->getError(), 304);
        }
        $type = $request->post('type_id',1);
        $userInfo = $request->userInfo;
        $result = BtcPost::where(['user_id'=>$userInfo['user_id'],'status'=>0])->find();
        if($result){
            return $this->response('你已参与了该期，等待开奖!',304);
        }
        $btc_id = Db::table('dw_btc')->order('add_time desc')->find();
        // 开启事务
        Db::startTrans();
        try {
            // 扣除抖金
            $userInfo->dw_money = bcsub($userInfo->dw_money, $request->post('money'), 4);
            $userInfo->save();
            // 获取赔率
            // 添加购买记录
            // 添加日志
            MoneyLog::create([
                'user_id'=> $userInfo->user_id,
                'log_content'=> '猜涨跌',
                'type'=> 1,
                'log_status'=>5,
                'chance_money'=> $request->post('money'),
                'dw_money'=> $userInfo->dw_money,
                'add_time'=> time(),
            ]);
            BtcPost::create([
                'user_id'=> $userInfo->user_id,
                'btc_id'=>$btc_id['btc_id']+2,
                'type'=>$type,
                'dw_money'=>$request->post('money'),
                'add_time'=>time()
            ]);

            // 提交数据
            Db::commit();
            // 成功返回数据
            return $this->response();
        } catch (\Exception $exception) {
            // 回退
            Db::rollback();
            // 失败返回
            return $this->response('交易失败', 405);
        }
    }

    /**
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function show_btc(Request $request){
        $userInfo = $request->userInfo;
        $list['btc_result'] = Btc::order('add_time desc')->find();//比特币结果
        $list['next_time'] = bcsub($list['btc_result']['add_time']+180,time());
        $rise = BtcPost::where(['status'=>0,'type'=>1])->sum('dw_money');//涨

        $fall = BtcPost::where(['status'=>0,'type'=>2])->sum('dw_money'); //跌
        $best_money = BtcPost::whereTime('add_time','today')
            ->where('status = 1')
            ->field("sum(dw_money) as dw_money,user_id")->group('user_id')->select();

        $list['best_money']['best_dw'] = !isset($best_money[0]) ? 0 :$best_money[0]['dw_money'];
        $list['best_money']['raword_num'] = !isset($best_money[0]) ? 0 :BtcPost::whereTime('add_time','today')
            ->where(['user_id'=>$best_money[0]['user_id'],'status'=>1])->count();
        $list['best_money']['user_name'] = !isset($best_money[0]) ? '' : Db::table('dw_users')->where(['user_id'=>$best_money[0]['user_id']])->value('user_name');
        $list['best_money']['user_avatar'] = !isset($best_money[0]) ? '' : Db::table('dw_users')->where(['user_id'=>$best_money[0]['user_id']])->value('user_avatar');
        $list['best_money']['user_avatar'] = $list['best_money']['user_avatar'] ? Config::get('image_url') .$list['best_money']['user_avatar']: '';
        $list['best_money']['best_dw'] =  $list['best_money']['best_dw']? bcmul($list['best_money']['best_dw'],0.8,2):0;
        $list['best_money']['raword_num'] = $list['best_money']['raword_num']?$list['best_money']['raword_num']:0;
        $list['best_money']['user_name'] =$list['best_money']['user_name'] ?$list['best_money']['user_name'] :$userInfo['user_name'];
        if($userInfo['user_avatar']){
            $list['best_money']['user_avatar'] =  $list['best_money']['user_avatar'] ? $list['best_money']['user_avatar'] : Config::get('image_url'). $userInfo['user_avatar'];
        }
        $list['best_money']['user_avatar'] =  $list['best_money']['user_avatar'] ? $list['best_money']['user_avatar'] : '';
       if($rise && $fall){
           $list['risa'] = bcmul(bcdiv($rise,$rise+$fall,2),100) ;
           $list['fall'] = bcsub(100,$list['risa']);
       }else{
           if(!$rise && $fall){
               $list['risa'] = 30;
               $list['fall'] = 70;
           }
           if($rise && !$fall){
               $list['risa'] = 75;
               $list['fall'] = 25;
           }
           if(!$rise && !$fall){
               $list['risa'] = 50;
               $list['fall'] = 50;
           }
       }
       $list['user_postbtc'] = BtcPost::where(['user_id'=>$userInfo['user_id'],'status'=>0])->find();
       if( $list['user_postbtc']){
           $add_time = Btc::where(['btc_id'=>$list['user_postbtc']['btc_id']-1])->value('add_time');//比特币结果
           if($add_time){
               $list['user_postbtc']['game_time'] = bcsub($add_time+180,time());
           }else{
               $list['user_postbtc']['game_time'] = $list['next_time'] +180;
           }

       }
       //今日战绩
        $win = BtcPost::where(['user_id'=>$userInfo['user_id'],'status'=>1])->whereTime('add_time','today')->select();
       $win_num = 0;
        foreach ($win as &$v) {
            $win_num = bcadd($win_num,$v['dw_money'], 2);
        }
        if(!$win_num){
            $list['win_num'] = 0;
        }else{
            $list['win_num'] = bcmul($win_num,0.8,2);
        }
        //猜中的数量
        $list['win_count'] = BtcPost::where(['user_id'=>$userInfo['user_id'],'status'=>1])->whereTime('add_time','today')->count();
        //排行
        $list['rank'] = 0;
        foreach($best_money as $k=>$v){
            if($v['user_id'] == $userInfo['user_id'] ){
                $list['rank'] = $k +1;
            }
        }

        return $this->response($list);


    }

    /**
     * 获取历史战绩
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function history(Request $request) {
        // 分页
        $page = $request->param('page', 1);
        $userInfo = $request->userInfo;
        $list = BtcPost::where(['user_id'=> $userInfo->user_id])
            ->page($page)
            ->select();

        return $this->response($list);
    }

    /**
     * 统计收益
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function count_profit(Request $request) {
        $userInfo = $request->userInfo;
        // 获取赔率
        $btcBasic = BtcBasic::find();
        // 购买记录
        $btcPost = BtcPost::whereTime('add_time', 'today')
            ->where(['user_id'=> $userInfo->user_id, 'status'=> 1])
            ->field('dw_money')
            ->select();
        $turntableLog = TurntableLog::whereTime('add_time', 'today')
            ->where(['user_id'=> $userInfo->user_id, 'status'=> 1])
            ->field('prize_money')
            ->select();
        $gamePostdata = GamePostdata::whereTime('add_time', 'today')
            ->where(['user_id'=> $userInfo->user_id, 'status'=> 2])
            ->field('win_money')
            ->select();
        // 今日收益
        $money = 0;
        foreach ($btcPost as $v) {
            $money = bcadd($money, bcmul($v['dw_money'], $btcBasic['odds'], 4), 4);
        }
        foreach ($turntableLog as $v) {
            $money = bcadd($money, $v['prize_money'], 4);
        }
        foreach ($gamePostdata as $v) {
            $money = bcadd($money, $v['win_money'], 4);
        }

        return $this->response(['profit'=> $money]);
    }

    /**
     * @param Request 最新开奖结果
     * @param WaveValidate $validate
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function new_result(Request $request ,WaveValidate $validate ){
        // 验证
        if (!$validate->scene('new')->check($request->post())) {
            return $this->response($validate->getError(), 304);
        }
        $btc_id = $request->post('btc_id');
        $userInfo = $request->userInfo;
        $result = BtcPost::where(['btc_id'=>$btc_id,'user_id'=>$userInfo['user_id']])->find();
        if(!$result){
            return $this->response('你未购买该期!',304);
        }
        if($result['status']==1){
            $result['win_dy'] = bcmul($result['dw_money'],0.8,2);
        }
        return $this->response($result);
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