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
            ->whereTime('add_time', 'today')
            ->count();
        // 当天中奖次数
        $win = $userInfo->userGameByHistory()
            ->whereTime('add_time', 'today')
            ->where(['status'=> 2]) // 中奖
            ->count();
        // 历史战绩
        $historyList = $userInfo->userGameByHistory()
            ->page($page)
            ->select();

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
        $request->post(['halt_time'=> $info['halt_time']]);
        // 验证
        if (!$numberBuy->check($request->post())) {
            return $this->response($numberBuy->getError(), 304);
        }
        // 开启事务
        Db::startTrans();
        try {
            $userInfo = $request->userInfo;
            // 扣除抖金
            $userInfo->dw_money = bcsub($userInfo->dw_money, $request->post('money'), 4);
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
            MoneyLog::create([
                'user_id'=> $userInfo->user_id,
                'log_content'=> '号码竞猜',
                'type'=> 1,
                'log_status'=>4,
                'chance_money'=> $request->post('money'),
                'dw_money'=> $userInfo->dw_money,
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

        return $this->response($list);
    }

    /**
     * 获取最新的开奖结果
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function newest() {
        // 最新开奖结果
        $newest = GameResult::order('periods desc')
            ->find();
        $info = $this->getOpenInfo();
        $data = [
            'result'=> $newest,
            'periods'=> $info['period_id'], // 期数
            'open_time'=> $info['open_time'], // 开奖时间
            'halt_time'=> $info['halt_time'], // 截止时间
        ];

        return $this->response($data);
    }

    /**
     * 获取当前的期数和开奖时间
     * @return array
     */
    protected function getOpenInfo() {
        // 开奖时间
        $idleTime = date('His');
        if ($idleTime > '073030' || $idleTime <= '031030') {
            // 开奖时间确定
            $time = substr($idleTime, -4);
            $openTime = 0;
            $periodNum = 0;
            if ($time > 1030 && $time <= 3030) {
                $openTime = strtotime(date('Y-m-d H:30:30'));
                $periodNum = 1;
            } elseif ($time > 3030 && $time <= 5030) {
                $openTime = strtotime(date('Y-m-d H:50:30'));
                $periodNum = 2;
            } elseif ($time > 5030 || $time <= 1030) {
                // 00:10:30不开奖
                if (substr($idleTime, 0, 2) === '00') {
                    $openTime = strtotime(date('Y-m-d H:30:30'));
                    $periodNum = 1;
                } else {
                    $openTime = $time > 5030 ? strtotime(date('Y-m-d H:10:30', mktime(date('H')+1))) : strtotime(date('Y-m-d H:10:30'));
                    $periodNum = 0;
                }
            }
            // 当前期数后缀
            if ($idleTime <= '031030') {
                $periodStaff = substr($idleTime, 0, 2) * 3 + $periodNum;
            } else {
                $periodStaff = (substr($idleTime, 0, 2) - 4) * 3 + $periodNum;
            }
            $periodStaff = substr('00'.$periodStaff, -3);
        } else {
            $openTime = strtotime(date('Y-m-d 07:30:30'));
            $periodStaff = '010';
        }
        // 当前期数
        $period = date('Ymd').$periodStaff;
        $date = [
            'period_id'=> $period,
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