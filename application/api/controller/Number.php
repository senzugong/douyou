<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19
 * Time: 10:02
 */

namespace app\api\controller;

use app\api\validate\NumberBuy;
use app\common\model\GamePostdata;
use app\common\model\GameResult;
use app\common\model\GameType;
use app\common\model\MoneyLog;
use app\common\model\UsdtLog;
use controller\BasicApi;
use think\Db;
use think\Request;

/**
 * Class Number
 * 号码竞猜
 * @package app\api\controller
 */
class Number extends BasicApi
{
    /**
     * 用户历史战绩
     * @param Request $request
     * @return \think\Response
     */
    public function history(Request $request) {
        // 分页
        $page = $request->param('page', 1);
        $userInfo = $request->userInfo;
        // 当天购买数
        $total = $userInfo->userGameByHistory()
            ->count();
        // 当天中奖次数
        $win = $userInfo->userGameByHistory()
            ->whereTime('add_time', 'today')
            ->where(['status'=> 2]) // 中奖
            ->count();
        // 历史战绩
        $historyList = $userInfo->userGameByHistory()
            ->limit(5)
            ->page($page)
            ->order('id desc')
            ->select();
        foreach($historyList as &$v){
            $v['add_time'] = $this->getTime($v['add_time']);
//            $v['periods'] = $v['result_id'];
        }
        return $this->response(['total'=> $total,'win'=> $win,'list'=> $historyList]);
    }

    /**
     * 购买奖项
     * @param Request $request
     * @param NumberBuy $numberBuy
     * @return \think\Response
     */
    public function buy(Request $request, NumberBuy $numberBuy) {

        // 获取开奖信息
        $info = $this->getOpenInfo();
        $userInfo = $request->userInfo;
        $result = Db::table('dw_game_postdata')->where(['user_id'=>$userInfo['user_id'],'period_id'=>$info['period_id']])->find();
        if($result){
            return $this->response('该期数您已购买!', 304);
        }
        // 购入封盘时间
        $request->post(['halt_time'=> $info['halt_time']]);
        // 验证
        // 验证
        if (!$numberBuy->check($request->post())) {
            return $this->response($numberBuy->getError(), 304);
        }
        // 开启事务
        Db::startTrans();
        try {
            // 扣除USDT
            $userInfo->dw_usdt = bcsub($userInfo->dw_usdt, $request->post('money'), 4);
            $userInfo->save();
            // 获取赔率
            $gameType = GameType::get($request->post('type_id'));
            // 添加购买记录
            GamePostdata::create([
                'user_id'=> $userInfo->user_id,
                'period_id'=> $info['period_id'], // 期数
                'type_id'=> $request->post('type_id'), // 购买类型
                'post_money'=> $request->post('money'),
                'win_money'=> bcmul($request->post('money'), $gameType->type_power,4), // 可赢的钱
                'add_time'=> time(),
            ]);
            // 添加日志
            UsdtLog::create([
                'user_id'=> $userInfo->user_id,
                'log_content'=> '号码竞猜',
                'type'=> 1,
                'log_status'=>4,
                'chance_usdt'=> $request->post('money'),
                'dw_usdt'=> $userInfo->dw_usdt,
                'add_time'=> time(),
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
     * 获取奖项类型
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function type() {
        // 获取类型
        $list = GameType::select();

        return $this->response($list);
    }

    /**
     * 获取开奖结果记录
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function result(Request $request) {
        // 分页
        $page = $request->param('page', 1);
        $list = GameResult::page($page)
            ->order('periods desc')
            ->select();
        foreach ($list as &$v){
            $v['add_time'] = $this->getTime($v['add_time']);
            $v['periods'] = $v['result_id'];
        }
        return $this->response($list);
    }

    /**
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_result(Request $request){
        $period_id = $request->param('period_id');
        $userInfo = $request->userInfo;
        if(!$period_id){
            return $this->response('参数不全!', 304);
        }
        $result = Db::table('dw_game_postdata')->where(['user_id'=>$userInfo['user_id'],'period_id'=>$period_id])->field('id,user_id,period_id,type_id,win_money,status')->find();
        if($result['status'] == 2){
            $data = [
                'type_id'=>$result['type_id'],
                'win_money'=>$result['win_money'],
                'period_id'=>$result['period_id'],
            ];
            return $this->response($data);
        }elseif($result['status'] == 1){
            $data = [
                'type_id'=>$result['type_id'],
                'win_money'=>0,
                'period_id'=>$result['period_id'],
            ];
            return $this->response($data);
        }elseif($result['status'] == 0){
            return $this->response('该期还未开奖',304);
        }else{
            return $this->response('你未购买该期',304);
        }
    }
    /**
     * 获取最新的开奖结果
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function newest(Request $request) {
        // 最新开奖结果
        $newest = GameResult::order('periods desc')
            ->find();
        $newest['periods'] = $newest['result_id'];
        $info = $this->getOpenInfo();
        $userInfo = $request->userInfo;
        $result = Db::table('dw_game_postdata')->where(['user_id'=>$userInfo['user_id'],'period_id'=>$info['period_id']])->find();
        if($result){
            $is_partake = 1;
        }else{
            $is_partake = 0;
        }
        $count = Db::table('dw_game_postdata')->where(['status'=> 0])->count();
        $rise_count = Db::table('dw_game_postdata')->where(['type_id'=>1,'status'=> 0])->count();
        $fill_count = Db::table('dw_game_postdata')->where(['type_id'=>2,'status'=> 0])->count();
        if($rise_count > 0 && $fill_count >0){
            $rise = bcdiv($rise_count,$count,2)*100;
            $fill = bcdiv($fill_count,$count,2)*100;
        }elseif($rise_count > 0 && $fill_count ==0){
            $rise = 100;
            $fill = 0;
        }elseif($rise_count == 0 && $fill_count >0){
            $rise = 0;
            $fill = 100;
        }else{
            $rise = 50;
            $fill = 50;
        }
        $data = [
            'result'=> $newest,
            'periods'=> $info['period_id'], // 期数
            'open_time'=> $info['open_time'], // 开奖时间
            'halt_time'=> $this->getTime($info['halt_time']), // 截止时间
            'single'=>$rise,
            'two'=>$fill,
            'is_partake'=>$is_partake
        ];

        return $this->response($data);
    }

    /**
     * 获取当前的期数和开奖时间
     * @return array
     */
    protected function getOpenInfo() {
//        // 开奖时间
//        $idleTime = date('His');
//        if ($idleTime > '073030' || $idleTime <= '031030') {
//            // 开奖时间确定
//            $time = substr($idleTime, -4);
//            $openTime = 0;
//            $periodNum = 0;
//            if ($time > 1030 && $time <= 3030) {
//                $openTime = strtotime(date('Y-m-d H:30:30'));
//                $periodNum = 1;
//            } elseif ($time > 3030 && $time <= 5030) {
//                $openTime = strtotime(date('Y-m-d H:50:30'));
//                $periodNum = 2;
//            } elseif ($time > 5030 || $time <= 1030) {
//                // 00:10:30不开奖
//                if (substr($idleTime, 0, 2) === '00') {
//                    $openTime = strtotime(date('Y-m-d H:30:30'));
//                    $periodNum = 1;
//                } else {
//                    $openTime = $time > 5030 ? strtotime(date('Y-m-d H:10:30', mktime(date('H')+1))) : strtotime(date('Y-m-d H:10:30'));
//                    $periodNum = 0;
//                }
//            }
//            // 当前期数后缀
//            if ($idleTime <= '031030') {
//                $periodStaff = substr($idleTime, 0, 2) * 3 + $periodNum;
//            } else {
//                $periodStaff = (substr($idleTime, 0, 2) - 4) * 3 + $periodNum;
//            }
//            $periodStaff = substr('00'.$periodStaff, -3);
//        } else {
//            $openTime = strtotime(date('Y-m-d 07:30:30'));
//            $periodStaff = '010';
//        }
        // 当前期数
        $result = Db::table('dw_game_result')->order('result_id desc')->find();
        $openTime =  $result['add_time'] + 20*60;
        $date = [
            'period_id'=> $result['result_id'] + 1,
            'open_time'=> $openTime,
            'halt_time'=> $openTime - 60, // 截止时间
        ];
        return $date;
    }

    /**
     * 获取规则
     * @return \think\Response
     */
    public function rule() {
        $result = <<<EOF
 <div class="rule-box"><div class="rule-tile padding-bottom-20"><span>参与资格</span></div><div class="rule-text padding-bottom-23">活动仅限蚪游账户用户参加。</div><div class="rule-tile padding-bottom-20"><span>活动时间</span></div><div class="rule-text padding-bottom-23">2019年3月1日至2019年3月31</div><div class="rule-tile padding-bottom-20"><span>活动规则</span></div><div class="rule-text color-active padding-bottom-5">1. 参与规则</div><div class="rule-text padding-bottom-30">活动期间，BTC涨跌的竞猜只能竞猜下一期涨跌，如您在40期内，只能投41期竞猜，以此类推。每次竞猜<span class="color-active">最低使用20蚪金，最高使用200蚪金，</span>每次做完竞猜后不可修改，蚪金使用后无法退还。</div><div class="rule-text color-active padding-bottom-5">2. 开奖规则</div><div class="rule-text padding-bottom-30">在当前竞猜期数结束时的3分钟左右，竞猜结果揭晓。若竞猜成功，则按参与蚪金所在竞猜方的蚪金池的比列，瓜分当期所有竞猜蚪金。所获蚪金一般在当期竞猜结果揭晓后立即到账。</div><div class="rule-text color-active padding-bottom-5">3. 特别须知</div><div class="rule-text padding-bottom-30">1) 禁止利用竞猜活动进行赌博活动等违法违规行为，若在活动前后发现违法违规行为，不仅限于冒用欺诈、赌博、传播色情、冒用他人身份等用户，蚪游有权取消活动参与资格及领奖资格。</div><div class="rule-text padding-bottom-30">2) 请避免违规刷蚪金的行为（包括但不限于虚假交易、恶意拆单、退款），如发现刷蚪金行为，蚪游有权取消活动参与资格及领奖资格。</div><div class="rule-text">3) 大盘不涨不跌视为上涨。</div></div>
EOF;
        return $this->response($result);
    }
}