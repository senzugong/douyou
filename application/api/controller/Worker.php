<?php

namespace app\api\controller;

use app\common\library\Curl;
use app\common\model\GamePostdata;
use app\common\model\GameResult;
use app\common\model\UsdtLog;
use app\common\model\UsdtMall;
use app\common\model\UsdtOrder;
use think\Db;
use think\Log;
use Workerman\Lib\Timer;
use app\common\model\User;
use Workerman\Worker as WorkerMan;

class Worker
{
    /**
     * @var int 定时时间
     */
    protected $timer = 8;
    /**
     * @var int 进程数
     */
    protected $processes = 3;
    /**
     * @var array 上次执行时间
     */
    protected $lastTime = [];

    /**
     * 架构函数
     * @access public
     */
    public function __construct()
    {
        $worker = new WorkerMan();
        $worker->count = $this->processes;
        $worker->onWorkerStart = function ($worker) {
            if ($worker->id == 0) {
                // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
                Timer::add($this->timer, function ($worker, $sup) {
                    try {
                        // 定时产生RMZ
                        $sup->main();
                    } catch (\Exception $exception) {
                        // 运行错误处理
                        Log::write("HotWorker Exception Message: " . $exception->getMessage() . "\tFile: " . $exception->getFile() . "\tLine: " . $exception->getLine(), 'error');
                    } catch (\Error $error) {
                        // 运行错误处理
                        Log::write("HotWorker Error Message: " . $error->getMessage() . "\tFile: " . $error->getFile() . "\tLine: " . $error->getLine(), 'error');
                    }
                }, [$worker, $this]);
            } elseif ($worker->id == 1) {
                // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
                Timer::add(1, function ($worker, $sup) {
                    try {
                        // 定时产生RMZ
                        $sup->getresult();
                    } catch (\Exception $exception) {
                        // 运行错误处理
                        Log::write("HotWorker Exception Message: " . $exception->getMessage() . "\tFile: " . $exception->getFile() . "\tLine: " . $exception->getLine(), 'error');
                    } catch (\Error $error) {
                        // 运行错误处理
                        Log::write("HotWorker Error Message: " . $error->getMessage() . "\tFile: " . $error->getFile() . "\tLine: " . $error->getLine(), 'error');
                    }
                }, [$worker, $this]);
            }elseif($worker->id == 2){
                // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
                Timer::add(1, function ($worker, $sup) {
                    try {
                        // 定时产生RMZ
                        $sup->getpoint();

                    } catch (\Exception $exception) {
                        // 运行错误处理
                        Log::write("HotWorker Exception Message: " . $exception->getMessage() . "\tFile: " . $exception->getFile() . "\tLine: " . $exception->getLine(), 'error');
                    } catch (\Error $error) {
                        // 运行错误处理
                        Log::write("HotWorker Error Message: " . $error->getMessage() . "\tFile: " . $error->getFile() . "\tLine: " . $error->getLine(), 'error');
                    }
                }, [$worker, $this]);
            }

        };
        // 运行worker
        WorkerMan::runAll();
    }

    /**
     * 定时入口
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function main()
    {
        $this->getbtc();
        // 重庆时时乐
        $this->getLottery();
        // 定时处理订单
        if (!isset($this->lastTime['order']) || $this->lastTime['order'] + 60 <= time()) {
            $this->lastTime['order'] = time();
            $this->autoOrder();
        }
    }
    /**
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function getbtc(){
        $result = Db::table('dw_btc')->whereTime('add_time','today')->find();
        if(!$result) {
            $btc_now = Db::table('dw_btc_now')->value('btc_now');
            $data = json_decode($btc_now, true);
            $btc_price = $data['ticker']['sell'];
            if (!$btc_price) {
                $url = 'http://api.zb.cn/data/v1/ticker?market=btc_usdt';
                // 获取数据
                $data = Curl::get($url);
                $btc_price = $data['ticker']['sell'];//当前btc价格
            }
            Db::table('dw_btc')->where(['btc_id'=>1])->update(['btc_price' => $btc_price, 'add_time' => time()]);
        }
    }
    /**实时开涨跌
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getresult()
    {
        // url地址
//        $url = 'http://api.zb.cn/data/v1/ticker?market=btc_usdt';
        $url = 'http://api.bitkk.com/data/v1/ticker?market=btc_usdt';
        // 获取数据
        $data = Curl::get($url);
        if(!is_array($data['ticker']) || !isset($data['ticker']) || count($data['ticker']) <= 3 ||  $data['ticker'] === 'false' ){
            $url1 = 'http://api.zb.cn/data/v1/ticker?market=btc_usdt';
//            $url1 = 'http://api.bitkk.com/data/v1/ticker?market=btc_usdt';
            // 获取数据
            $data1 = Curl::get($url1);
            $btc_price = $data1['ticker']['sell'];//当前btc价格
        }else{
            $btc_price = $data['ticker']['buy'];//当前btc价格
        }
//        if(!$btc_price){
//                $btc_now = Db::table('dw_btc_now')->value('btc_now');
//                $data = json_decode($btc_now,true);
//                $num = rand(10,99);
//                $type = rand(1,2);
//                if($type ==1){
//                    $btc_price = bcsub($data['ticker']['sell'],bcdiv($num,100,4),4);
//                }else{
//                    $btc_price = bcadd($data['ticker']['sell'],bcdiv($num,100,4),4);
//                }
//        }else{
            $result = $this->is_controller($btc_price);
            if($result){
                $data['ticker']['sell'] = $result;
        }
        Db::table('dw_btc_now')->where(['id'=>2])->update(['btc_now'=>json_encode($data),'add_time'=>time()]);
//        $time = time();
//        $list = Db::table('dw_btc_order')->where("end_time = $time and type = 1 and  is_win = 0")->select();
//        foreach ($list as &$v) {
//            $user = Db::table('dw_users')->where(['user_id' => $v['user_id']])->find();
//            $recharge = Db::table('dw_basic_time')->where(['id' => $v['time_id']])->value('proportion');//找赔率
//            if ($btc_price >= $v['buy_price']) {//中奖的人数
//                if ($v['style'] == 1) { //买涨
//                    Db::startTrans();
//                    try {
//                        $win = bcmul($v['usdt_fee'], bcdiv($recharge, 100, 2), 4);
//                        Db::table('dw_btc_order')->where(['order_id' => $v['order_id']])->update(['is_win' => 1,'result_price'=>$btc_price, 'win_price' => $win]);
//                        Db::table('dw_users')->where(['user_id' => $v['user_id']])->update(['dw_usdt' => bcadd(bcadd($win,$v['order_fee'],4), $user['dw_usdt'], 4)]);
//                        // 添加日志
//                        UsdtLog::create([
//                            'user_id' => $v['user_id'],
//                            'log_content' => '时间模式',
//                            'type' => 2,
//                            'log_status' => 5,
//                            'chance_usdt' => $win,
//                            'dw_usdt' => bcadd(bcadd($win,$v['order_fee'],4), $user['dw_usdt'], 4),
//                            'add_time' => time(),
//                        ]);
//                        // 提交
//                        Db::commit();
//                    } catch (\Exception $exception) {
//                        // 回滚
//                        Db::rollback();
//                    }
//                } else {
//                    Db::table('dw_btc_order')->where(['order_id' => $v['order_id']])->update(['result_price'=>$btc_price,'is_win' => 2]);
//                }
//            } elseif ($btc_price <= $v['buy_price']) {
//                if ($v['style'] == 2) { //买涨
//                    Db::startTrans();
//                    try {
//                        $win =bcmul($v['usdt_fee'], bcdiv($recharge, 100, 2), 4);
//                        Db::table('dw_btc_order')->where(['order_id' => $v['order_id']])->update(['is_win' => 1,'result_price'=>$btc_price, 'win_price' => $win]);
//                        Db::table('dw_users')->where(['user_id' => $v['user_id']])->update(['dw_usdt' => bcadd(bcadd($win,$v['order_fee'],4), $user['dw_usdt'], 4)]);
//                        // 添加日志
//                        UsdtLog::create([
//                            'user_id' => $v['user_id'],
//                            'log_content' => '时间模式',
//                            'type' => 2,
//                            'log_status' => 5,
//                            'chance_usdt' => $win,
//                            'dw_usdt' => bcadd(bcadd($win,$v['order_fee'],4), $user['dw_usdt'], 4),
//                            'add_time' => time(),
//                        ]);
//                        // 提交
//                        Db::commit();
//                    } catch (\Exception $exception) {
//                        // 回滚
//                        Db::rollback();
//                    }
//                } else {
//                    Db::table('dw_btc_order')->where(['order_id' => $v['order_id']])->update(['is_win' => 2,'result_price'=>$btc_price]);
//                }
//            } else {
//                Db::table('dw_btc_order')->where(['order_id' => $v['order_id']])->update(['is_win' => 2,'result_price'=>$btc_price]);
//            }
//
//        }
    }

    /**
     * @return mixed点位开奖定时
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function getpoint(){
        // url地址
        $btc_now = Db::table('dw_btc_now')->value('btc_now');
        $data = json_decode($btc_now,true);
        $btc_price = $data['ticker']['sell'];
        if(!$btc_price){
            $url = 'http://api.zb.cn/data/v1/ticker?market=btc_usdt';
            // 获取数据
            $data = Curl::get($url);
            $btc_price = $data['ticker']['sell'];//当前btc价格
        }
        $list = Db::table('dw_btc_order')->where(" type = 2 and  is_win = 0")->select();
        foreach ($list as &$v) {
            $user = Db::table('dw_users')->where(['user_id' => $v['user_id']])->find();
            if($v['style']==1) {//看涨
                if ($btc_price >= $v['rise_price']) {
                    Db::startTrans();
                    try {
                        $win = bcmul($v['order_fee'], 0.8, 4);
                        Db::table('dw_btc_order')->where(['order_id' => $v['order_id']])->update(['is_win' => 1, 'win_price' => $win,'result_price'=>$v['rise_price'], 'end_time' => time()]);
                        Db::table('dw_users')->where(['user_id' => $v['user_id']])->update(['dw_usdt' => bcadd(bcadd($win,$v['order_fee'],4), $user['dw_usdt'], 4)]);
                        // 添加日志
                        UsdtLog::create([
                            'user_id' => $v['user_id'],
                            'log_content' => '区间模式',
                            'type' => 2,
                            'log_status' => 6,
                            'chance_usdt' => $win,
                            'dw_usdt' =>bcadd(bcadd($win,$v['order_fee'],4), $user['dw_usdt'], 4),
                            'add_time' => time(),
                        ]);
                        // 提交
                        Db::commit();
                    } catch (\Exception $exception) {
                        // 回滚
                        Db::rollback();
                    }
                } elseif ($btc_price <= $v['fill_price']) {
                    Db::table('dw_btc_order')->where(['order_id' => $v['order_id']])->update(['is_win' => 2,'result_price'=>$v['fill_price'], 'end_time' => time()]);
                }
            }
            if($v['style']==2){//跌
                if ($btc_price <= $v['rise_price']) {
                    Db::startTrans();
                    try {
                        $win =bcmul($v['order_fee'], 0.8, 4);
                        Db::table('dw_btc_order')->where(['order_id' => $v['order_id']])->update(['is_win' => 1, 'win_price' => $win,'result_price'=>$v['rise_price'], 'end_time' => time()]);
                        Db::table('dw_users')->where(['user_id' => $v['user_id']])->update(['dw_usdt' => bcadd(bcadd($win,$v['order_fee'],4), $user['dw_usdt'], 4)]);
                        // 添加日志
                        UsdtLog::create([
                            'user_id' => $v['user_id'],
                            'log_content' => '区间模式',
                            'type' => 2,
                            'log_status' => 6,
                            'chance_usdt' =>$win,
                            'dw_usdt' => bcadd(bcadd($win,$v['order_fee'],4), $user['dw_usdt'], 4),
                            'add_time' => time(),
                        ]);
                        // 提交
                        Db::commit();
                    } catch (\Exception $exception) {
                        // 回滚
                        Db::rollback();
                    }
                } elseif ($btc_price >= $v['fill_price']) {
                    Db::table('dw_btc_order')->where(['order_id' => $v['order_id']])->update(['is_win' => 2, 'result_price'=>$v['fill_price'],'end_time' => time()]);
                }
            }

            }
        }
    /**
     * 号码竞猜开奖结果
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getLottery() {
        // url地址
        $url = 'http://api3.apicp.cn/api?token=3b3e66a8cccc70d5&code=cqssc&rows=1&format=json';
        $name = '重庆时时彩';
        // 获取数据
        $data = Curl::get($url);
        if(!is_array($data) || empty($data['data'])){
            $url = 'http://f.apiplus.net/cqssc-1.json';
            // 获取数据
            $data = Curl::get($url);
        }
        // 将数据放入数据库
        if (is_array($data) && isset($data['data'])) {
            // 彩票开奖信息
            $list = $data['data'];
            foreach ($list as &$value) {
                $game = GameResult::where(['periods'=> $value['expect']])->find();
                if (!$game) {
                    GameResult::create([
                        'periods'=> $value['expect'],
                        'game_name'=> $name,
                        'results'=> $value['opencode'],
                        'add_time'=> strtotime($value['opentime']), // 使用开奖时间
                    ]);
                    // 最后一个数字单双情况
                    $openCode = explode(',', $value['opencode']);
                    $end = $openCode[4] % 2;
                    switch ($end) {
                        case 0://双
                            // 未中奖处理
                            GamePostdata::where(['status'=>0, 'type_id'=> 1])->update(['status'=>1]);
                            $gamePostdata = GamePostdata::where(['status'=>0, 'type_id'=> 2])->select();
                            foreach ($gamePostdata as &$v) {
                                Db::startTrans();
                                try {
                                    // 中奖
                                    GamePostdata::where(['id'=>$v['id']])->update(['status'=>2]);
                                    // 修改用户账户
                                    $user = User::get($v->user_id);
                                    $user->dw_usdt = bcadd($user->dw_usdt, $v->win_money, 4);
                                    $user->save();
                                    // 添加日志
                                    UsdtLog::create([
                                        'user_id' =>$user->user_id,
                                        'log_content' => '号码竞猜',
                                        'type' => 2,
                                        'log_status' => 4,
                                        'chance_usdt' => $v->win_money,
                                        'dw_usdt' => $user->dw_usdt,
                                        'add_time' => time(),
                                    ]);
                                    // 提交
                                    Db::commit();
                                } catch (\Exception $exception) {
                                    // 回滚
                                    Db::rollback();
                                }
                            }
                            break;
                        case 1: //单
                            // 未中奖
                            GamePostdata::where(['status'=>0, 'type_id'=> 2])->update(['status'=>1]);
                            $gamePostdata = GamePostdata::where(['status'=>0, 'type_id'=> 1])->select();
                            foreach ($gamePostdata as $v) {
                                Db::startTrans();
                                try {
                                    // 中奖处理
                                    GamePostdata::where(['id'=>$v['id']])->update(['status'=>2]);
                                    // 修改用户账户
                                    $user = User::get($v->user_id);
                                    $user->dw_usdt = bcadd($user->dw_usdt, $v->win_money, 4);
                                    $user->save();
                                    // 添加日志
                                    UsdtLog::create([
                                        'user_id' =>$user->user_id,
                                        'log_content' => '号码竞猜',
                                        'type' => 2,
                                        'log_status' => 4,
                                        'chance_usdt' => $v->win_money,
                                        'dw_usdt' => $user->dw_usdt,
                                        'add_time' => time(),
                                    ]);
                                    // 提交
                                    Db::commit();
                                } catch (\Exception $exception) {
                                    // 回滚
                                    Db::rollback();
                                }
                            }break;
                    }
                }
            }
        }
    }

    /**
     * 自动处理订单
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function autoOrder () {
        // 超时付款取消订单 15min
        $unpaid = UsdtOrder::where(['status'=> 0])
            ->where('add_time', '<=', time() - 15 * 60)
            ->select();
        foreach ($unpaid as $item) {
            $usdtMall = UsdtMall::get($item->mall_id);
            // 已经取消挂卖的和没有出售过的不在自动处理范围，不能退款
            if (in_array($usdtMall['status'], [0, 4])) continue;
            // 取消订单
            Db::startTrans();
            try {
                // 取消
                $item->save(['status'=> 4]);
                // 已完成交易数量
                $over_usdt = bcsub($usdtMall->over_usdt, $item->order_usdt_num, 4);
                // 交易状态 0未成交 1已部分成交  2已全部成交 3取消
                $mallStatus = $over_usdt == 0 ? 0 : 1;
                $usdtMall->save([
                    'over_usdt'=> $over_usdt,
                    'status'=> $mallStatus,
                ]);
                // 发布收购
                if ($usdtMall['type'] == 2) {
                    // 下单用户
                    $orderUser = $item->orderUser;
                    $orderUser->save(['dw_usdt'=> bcadd($orderUser['dw_usdt'], $item['order_usdt_num'], 4)]);
                }
                // 提交
                Db::commit();
            } catch (\Exception $exception) {
                // 回滚
                Db::rollback();
            }
        }
    }
    /**
     * @return 是否开启了风控
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function is_controller($btc_price){
        //滑点控制
        $point = Db::table('dw_slip_point')->find();
        if($point['point'] != 0){
            $point_price = bcadd($btc_price,$point['point'],4);
            return $point_price;
        }
        //开启了滑点风控
        $result = Db::table('dw_btc_config')->where("status = 1")->find();
        if($result){
            $time = time()- rand(1,10);
            //吃大赔小
            $list = Db::table('dw_btc_order')->where("is_win = 0 and add_time <= $time  and type=2")->select();
            if(!$list){
                return 0;
            }
            $rise = Db::table('dw_btc_order')->where(" type=2 and is_win = 0 and add_time <= $time  and style = 1")->sum('order_fee');
            $fill = Db::table('dw_btc_order')->where(" type=2 and is_win = 0 and add_time <= $time  and style = 2")->sum('order_fee');
            if($rise == 0 && $fill != 0 ){
                $win_price = Db::table('dw_btc_order')->where("type=2 and is_win = 0 and add_time <= $time  and style = 2")->order('fill_price desc')->value('rise_price');
                $num = bcdiv(rand(80, 120),100,4);
                $btc_price1 = bcadd($btc_price,$num,4);
                if($btc_price1 <= $win_price){
                    return $btc_price;
                }else{
                    return $btc_price1;
                }
            }elseif($rise != 0 && $fill == 0 ){
                $win_price = Db::table('dw_btc_order')->where("type=2 and is_win = 0 and add_time <= $time and style = 1")->order('rise_price desc')->value('rise_price');
                $num = bcdiv(rand(80, 120),100,4);
                $btc_price1 = bcsub($btc_price,$num,4);
                if($btc_price1 >= $win_price){
                    return $btc_price;
                }else{
                    return $btc_price1;
                }
            }
            if($result['type'] == 1){
            if($rise > $fill){//吃大赔小
                $btc_price = Db::table('dw_btc_order')->where("type=2  and is_win = 0 and add_time <= $time and style = 1")->order('rise_price desc')->value('fill_price');
                $num = bcdiv(rand(80, 120),100,4);
                $btc_price = bcsub($btc_price,$num,4);
                return $btc_price;
            }else{
                $btc_price = Db::table('dw_btc_order')->where("type=2 and is_win = 0 and add_time <= $time  and style = 2")->order('fill_price desc')->value('fill_price');
                $num = bcdiv(rand(80, 120),100,4);
                $btc_price = bcadd($btc_price,$num,4);
                return $btc_price;
            }
            }else{//吃小赔大
                if($rise > $fill){
                    $btc_price = Db::table('dw_btc_order')->where("type=2 and is_win = 0 and add_time <= $time  and style =1")->order('fill_price desc')->value('rise_price');
                    $num = bcdiv(rand(80, 120),100,4);
                    $btc_price = bcadd($btc_price,$num,4);
                    return $btc_price;
                }else{
                    $btc_price = Db::table('dw_btc_order')->where("type=2 and is_win = 0 and add_time <= $time  and style = 2")->order('rise_price desc')->value('rise_price');
                    $num = bcdiv(rand(80, 120),100,4);
                    $btc_price = bcsub($btc_price,$num,4);
                    return $btc_price;
                }
            }
        }else{
            return 0;
        }
    }
}