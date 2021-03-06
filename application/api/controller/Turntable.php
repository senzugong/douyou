<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 15:35
 */

namespace app\api\controller;

use app\api\validate\TurntableDraw;

use app\common\model\RawardAddress;
use app\common\model\TurntableLog;
use app\common\model\TurntableSet;
use app\common\model\UsdtLog;
use controller\BasicApi;
use think\Config;
use think\Db;
use think\Request;

/**
 * Class Turntable
 * 转盘控制器
 * @package app\api\controller
 */
class Turntable extends BasicApi
{
    /**
     * 全部奖品列表
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function prize(Request $request) {
        // 获取奖品列表
        $list['prize_list'] = TurntableSet::where(['status'=> 0])
            ->field('id, prize, prize_money, prize_img, sort_order')
            ->order('sort_order asc')
            ->select();
        foreach($list['prize_list'] as &$v){
            $v['prize_img'] =$v['prize_img'] ? Config::get('image_url').$v['prize_img'] : '';
        }
        $userInfo = $request->userInfo;
        $list['free_num'] = $userInfo->turntableLog()
            ->where(['type'=> 0, 'status'=> 1, 'turntable_id'=> 6])
            ->count();
        return $this->response($list);
    }

    /**
     * 获取中奖名单
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function record(Request $request) {
        // 分页
        $page = $request->param('page', 1);
        // 获取中奖记录
        $list = TurntableLog::alias('a')
            ->join('dw_turntable_set b', 'b.id=a.turntable_id')
            ->join('dw_users u', 'u.user_id=a.user_id')
            ->where(['a.status'=> 1])
            ->field('u.user_name,u.user_phone, a.add_time, b.prize_money,b.prize, b.id as prize_id')
            ->limit(10)
            ->page($page)
            ->order('a.add_time desc')
            ->select();
        foreach($list as &$v){
            if(!$v['user_name']){
                $v['user_name'] = $v['user_phone'];
            }
            if($v['prize_money'] > 0){
                $v['prize'] =$v['prize_money'].$v['prize'];
            }
        }
        return $this->response($list);
    }

    /**
     * 幸运大转盘抽奖
     * @param Request $request
     * @param TurntableDraw $turntableDraw
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function draw(Request $request, TurntableDraw $turntableDraw) {
        // 验证数据
        if (!$turntableDraw->scene('draw')->check($request->post())) {

            return  $this->response( $turntableDraw->getError() ,304);
        }
        $userInfo = $request->userInfo;
        // 抽奖消耗金额
        $drawNum = $userInfo->turntableLog()
            ->whereTime('add_time', 'month')
            ->count();
        // 每次抽奖要花费0.5个抖金
        $money = bcmul($drawNum,0.5,2) ;
        // 根据消耗的金额设置奖项的范围
        $offset = 5;
        if ($money >= 500 && $money < 1000) {
            $offset = 4;
        } elseif ($money >= 1000 && $money < 2000) {
            $offset = 3;
        } elseif ($money > 2000) {
            $offset = 1;
        }
        // 获取奖品
        $drawList = TurntableSet::where(['status'=> 0])
            ->field('id, prize, prize_money, prize_num, prize_probability, prize_img, sort_order')
            ->where('id', '>=', $offset)
            ->order('sort_order asc')
            ->select();
        foreach($drawList as &$v){
            if($v['id'] == 6){
                $v['prize_img'] =Config::get('image_url'). 'public/upload/image/gift/9.png';
            }else{
                $v['prize_img'] =$v['prize_img'] ? Config::get('image_url').$v['prize_img'] : '';
            }

        }
        // 当月奖品中奖情况
        $drawWin = TurntableLog::where(['status'=> 1])
            ->whereTime('add_time', 'month')
            ->field('turntable_id, count(1) as turntable_num')
            ->group('turntable_id')
            ->select();
        // 奖品数量消耗统计
        $drawNum = array_column($drawWin, 'turntable_num', 'turntable_id');
        $proArr = [];
        foreach ($drawList as $key=>$value) {
            // 当前奖品已经抽完
            if (isset($drawNum[$value['id']]) && $drawNum[$value['id']] != 0 && $value['prize_num'] >= $drawNum[$value['id']]) {
                unset($drawList[$key]);
            } else {
                // 概率转换成整数
                $proArr[$key] = bcmul($value['prize_probability'], 10000);
            }
        }
        // 中奖奖品
        $result = [];
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //返回随机整数
        $randNum = mt_rand(1, $proSum);
        //概率数组循环
        $position = 0;
        foreach ($proArr as $key => $proCur) {
            $position += $proCur;
            if ($randNum <= $position) {
                $result = $drawList[$key];
                break;
            }
        }
        Db::startTrans();
        try {
            // 是否有免费次数
            $freeNum = $userInfo->turntableLog()
                ->where(['type'=> 0, 'status'=> 1, 'turntable_id'=> 6])
                ->find();
            if (!$freeNum) {
                // 扣除抽奖金额0.5抖金
                $userInfo->save(['dw_usdt' => bcsub($userInfo->dw_usdt, 0.5, 2)]);
                // 添加日志
                UsdtLog::create([
                    'user_id'=> $userInfo->user_id,
                    'log_content'=> '号码竞猜',
                    'type'=> 1,
                    'log_status'=>3,
                    'chance_usdt'=> 0.5,
                    'dw_usdt'=> $userInfo->dw_usdt,
                    'add_time'=> time(),
                ]);
            } else {
                // 领取免费次数
                $freeNum->save(['type'=> 1]);
            }
            // 奖项处理
            switch ($result['id']) {
                case 1:
                    // mac笔记本
                    // 中奖金额
                    $result['win_money'] = 0;
                    // 中奖
                    $type = 0;
                    $prizeStatus = 1;
                    break;
                case 2:
                    // iPhoneXr
                    // 中奖金额
                    $result['win_money'] = 0;
                    // 中奖
                    $type = 0;
                    $prizeStatus = 1;
                    break;
                case 3:
                    // 蚪金红包 价值888元
                    $winMoney = TurntableSet::where(['id'=>3])->value('prize_money');
                    $userInfo->save(['dw_usdt'=> bcadd($userInfo->dw_usdt, $winMoney, 4)]);
                    // 添加日志
                    UsdtLog::create([
                        'user_id'=> $userInfo->user_id,
                        'log_content'=> '号码竞猜',
                        'type'=> 1,
                        'log_status'=>3,
                        'chance_usdt'=> $winMoney,
                        'dw_usdt'=> $userInfo->dw_usdt,
                        'add_time'=> time(),
                    ]);
                    // 中奖金额
                    $result['win_money'] = $winMoney;
                    // 中奖
                    $type = 1;
                    $prizeStatus = 1;
                    break;
                case 4:
                    // 蚪金红包 价值222元
                    $winMoney = TurntableSet::where(['id'=>4])->value('prize_money');
                    $userInfo->save(['dw_usdt'=> bcadd($userInfo->dw_usdt, $winMoney, 4)]);
                    // 添加日志
                    UsdtLog::create([
                        'user_id'=> $userInfo->user_id,
                        'log_content'=> '号码竞猜',
                        'type'=> 1,
                        'log_status'=>3,
                        'chance_usdt'=> $winMoney,
                        'dw_usdt'=> $userInfo->dw_usdt,
                        'add_time'=> time(),
                    ]);
                    // 中奖金额
                    $result['win_money'] = $winMoney;
                    // 中奖
                    $type = 1;
                    $prizeStatus = 1;
                    break;
                case 5:
                    // 蚪金红包 价值20元
                    $winMoney = TurntableSet::where(['id'=>5])->value('prize_money');
                    $userInfo->save(['dw_usdt'=> bcadd($userInfo->dw_usdt, $winMoney, 4)]);
                    // 添加日志
                    UsdtLog::create([
                        'user_id'=> $userInfo->user_id,
                        'log_content'=> '号码竞猜',
                        'type'=> 1,
                        'log_status'=>3,
                        'chance_usdt'=> $winMoney,
                        'dw_usdt'=> $userInfo->dw_usdt,
                        'add_time'=> time(),
                    ]);
                    // 中奖金额
                    $result['win_money'] = $winMoney;
                    // 中奖
                    $type = 1;
                    $prizeStatus = 1;
                    break;
                case 6:
                    // 免费1次
                    // 中奖金额
                    $result['win_money'] = 0;
                    // 中奖
                    $type = 0;
                    $prizeStatus = 1;
                    break;
                case 7:
                    // 蚪金随机红包 5-50蚪金
                    $winMoney = TurntableSet::where(['id'=>7])->value('prize_money');
                    $userInfo->save(['dw_usdt'=> bcadd($userInfo->dw_usdt, $winMoney, 4)]);
                    // 添加日志
                    UsdtLog::create([
                        'user_id'=> $userInfo->user_id,
                        'log_content'=> '号码竞猜',
                        'type'=> 1,
                        'log_status'=>3,
                        'chance_usdt'=> $winMoney,
                        'dw_usdt'=> $userInfo->dw_usdt,
                        'add_time'=> time(),
                    ]);
                    // 中奖金额
                    $result['win_money'] = $winMoney;
                    // 中奖
                    $type = 1;
                    $prizeStatus = 1;
                    break;
                case 8:
                    // 中奖金额
                    $result['win_money'] = 0;
                    // 不中奖
                    $type = 0;
                    $prizeStatus = 0;
                    break;
            }
            // 抽奖记录
            TurntableLog::create([
                'user_id'=> $userInfo->user_id,
                'turntable_id'=> $result['id'], // 抽中奖项
                'prize_money'=> $result['prize_money'], // 礼物价值
                'type'=> $type,
                'status'=> $prizeStatus,
                'add_time'=> time(),
            ]);
            // 提交数据
            Db::commit();
            return $this->response($result);
        } catch (\Exception $exception) {
            // 回滚数据
            Db::rollback();

            return $this->response('抽奖失败', 502);
        }
    }
    /**
     * 转动转盘记录
     * @param Request $request
     * @return \think\Response
     */
    public function history(Request $request) {
        // 分页
        $page = $request->param('page', 1);
        $list = $request->userInfo
            ->turntableLog()
            ->page($page)
            ->order('add_time desc')
            ->select();
        foreach($list as &$v){
         $v['game_raword']  = TurntableSet::where(['id'=>$v['turntable_id']])->find();
         $v['add_time']  = $this->getTime( $v['add_time']);
        }
        return $this->response($list);
    }

    /**
     * @param Request $request
     * @return \think\Response
     */
    public function set_free(Request $request){
        $userInfo = $request->userInfo;
        $free_password = $request->post('is_free',1);
        if($free_password == 1){
            if($userInfo['free_password'] ==1){
                return $this->response('您已设置了免密支付', 304);
            }
            $userInfo->save(['free_password'=>1]);
        }else{
            if($userInfo['free_password'] ==0){
                return $this->response('您还未开启免密支付', 304);
            }
            $userInfo->save(['free_password'=>0]);
        }
        return $this->response();
    }

    /** 中奖地址
     * @param Request $request
     * @param TurntableDraw $turntableDraw
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function address(Request $request,TurntableDraw $turntableDraw)
    {
        // 验证数据
        if (!$turntableDraw->scene('address')->check($request->post())) {

            return  $this->response( $turntableDraw->getError() ,304);
        }
        $userInfo = $request->userInfo;
        $win_id = $request->post('win_id'); //日志id
        $win = TurntableLog::where(['win_id'=>$win_id])->find();
        if(!in_array($win['turntable_id'],[1,2])){
            return $this->response('该奖项不用添加地址',304);
        }
        if($win['status'] == 0 || $win['type'] == 1){
                return $this->response('该抽奖未中奖或已领取',304);
        }
        $address_detail = $request->post('address_detail'); //地址详情
        $mobile = $request->post('mobile'); //手机号码
        $name = $request->post('name'); //收货人
        RawardAddress::create([
            'win_id'=>$win_id,
            'user_id'=>$userInfo['user_id'],
            'address_detail'=>$address_detail,
            'mobile'=>$mobile,
            'name'=>$name,
            'add_time'=>time()
        ]);
        TurntableLog::where(['win_id'=>$win_id])->update(['type'=>1]);
        return $this->response();

    }
}