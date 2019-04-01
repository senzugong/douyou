<?php

namespace app\api\controller;

use app\common\library\Curl;
use app\common\model\Btc;
use app\common\model\BtcBasic;
use app\common\model\BtcPost;
use app\common\model\BtcSet;
use app\common\model\GamePostdata;
use app\common\model\GameResult;
use app\common\model\MoneyLog;
use app\common\model\UsdtMall;
use app\common\model\UsdtOrder;
use think\Db;
use think\Log;
use Workerman\Lib\Timer;
use app\common\model\User;
use Workerman\Worker as WorkerMan;

class Worker {
    /**
     * @var int 定时时间
     */
    protected $timer = 15;
    /**
     * @var int 进程数
     */
    protected $processes = 1;
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
        $worker->onWorkerStart = function($worker)
        {
            // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
            Timer::add($this->timer, function($worker, $sup){
                try {
                    // 定时产生RMZ
                    $sup->main();
                } catch (\Exception $exception) {
                    // 运行错误处理
                    Log::write("HotWorker Exception Message: ".$exception->getMessage()."\tFile: ".$exception->getFile()."\tLine: ".$exception->getLine(),'error');
                } catch (\Error $error) {
                    // 运行错误处理
                    Log::write("HotWorker Error Message: ".$error->getMessage()."\tFile: ".$error->getFile()."\tLine: ".$error->getLine(),'error');
                }
            }, [$worker, $this]);
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
    public function main() {
        // 重庆时时乐
        $this->getLottery();
        // 定时处理订单
        if (!isset($this->lastTime['order']) || $this->lastTime['order'] + 60 <= time()) {
            $this->lastTime['order'] = time();
            $this->autoOrder();
            $this->btc_result();
        }
    }

    /**
     * 号码竞菜开奖结果
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getLottery() {
        // url地址
        $url = 'http://api3.apicp.cn/api?token=1f930c2e53b82998&code=cqssc&rows=1&format=json';
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
            foreach ($list as $value) {
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
                        case 0:
                            // 中奖处理
                            GamePostdata::where(['period_id'=> $value['expect'], 'type_id'=> 2])->update(['status'=>2]);
                            // 未中奖
                            GamePostdata::where(['period_id'=> $value['expect'], 'type_id'=> 1])->update(['status'=>1]);
                            $gamePostdata = GamePostdata::where(['period_id'=> $value['expect'], 'type_id'=> 2])->select();
                            foreach ($gamePostdata as $value) {
                                Db::startTrans();
                                try {
                                    // 修改用户账户
                                    $user = User::get($value->user_id);
                                    $user->dw_money = bcadd($user->dw_money, $value->win_money, 4);
                                    $user->save();
                                    // 添加日志
                                    MoneyLog::create([
                                        'user_id'=> $user->user_id,
                                        'log_content'=> '号码竞猜',
                                        'type'=> 2,
                                        'log_status'=>4,
                                        'chance_money'=> $value->win_money,
                                        'dw_money'=> $user->dw_money,
                                        'add_time'=> time(),
                                    ]);
                                    // 提交
                                    Db::commit();
                                } catch (\Exception $exception) {
                                    // 回滚
                                    Db::rollback();
                                }
                            }
                            break;
                        case 1:
                            // 中奖处理
                            GamePostdata::where(['period_id'=> $value['expect'], 'type_id'=> 1])->update(['status'=>2]);
                            // 未中奖
                            GamePostdata::where(['period_id'=> $value['expect'], 'type_id'=> 2])->update(['status'=>1]);
                            $gamePostdata = GamePostdata::where(['period_id'=> $value['expect'], 'type_id'=> 1])->select();
                            foreach ($gamePostdata as $value) {
                                Db::startTrans();
                                try {
                                    // 修改用户账户
                                    $user = User::get($value->user_id);
                                    $user->dw_money = bcadd($user->dw_money, $value->win_money, 4);
                                    $user->save();
                                    // 添加日志
                                    MoneyLog::create([
                                        'user_id'=> $user->user_id,
                                        'log_content'=> '号码竞猜',
                                        'type'=> 2,
                                        'log_status'=>4,
                                        'chance_money'=> $value->win_money,
                                        'dw_money'=> $user->dw_money,
                                        'add_time'=> time(),
                                    ]);
                                    // 提交
                                    Db::commit();
                                } catch (\Exception $exception) {
                                    // 回滚
                                    Db::rollback();
                                }
                            }
                            break;
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
                // 提交
                Db::commit();
            } catch (\Exception $exception) {
                // 回滚
                Db::rollback();
            }
        }
    }

    /**btc定时开奖
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function btc_result()
    {
        //找当前分钟
        $minute = date('i', time());
        //后台设置的开奖分钟数
        $min = Db::table('dw_btc_basic')->where("id =1")->find();
        if ($minute % $min['interval_time'] == 0) {
            //找最新的一条开奖结果
            $btc_id = Db::table('dw_btc')->order('add_time desc')->find();
            //找后台最新一条数据是否开启了涨跌设置
            $result = BtcSet::where(['btc_id' => $btc_id['btc_id'] + 1])->find();
            if ($result) {
                //随机的涨幅度
                $change_number = rand(10001, 10010);
                if ($result['is_rise'] == 1) {//开启了涨的风控
                    $rise_price = bcsub(bcdiv($change_number, 10000, 4),1,4);
                    $data = [
                        'btc_price' => bcadd($btc_id['btc_price'],bcmul($btc_id['btc_price'], $rise_price, 4),4),
                        'change' => '+' . bcmul($rise_price, 100, 4),
                        'dispaly_name' => 'okex交易所',
                        'add_time' => time()
                    ];
                    $get_btc_id = Db::table('dw_btc')->insertGetId($data);
                    $btc_post = BtcPost::where(['btc_id' => $get_btc_id])->select();
                    foreach ($btc_post as &$v) {
                        if ($v['type'] == 1) {
                            $dw_money = Db::table('dw_users')->where(['user_id' => $v['user_id']])->value('dw_money');
                            $change_money = bcadd($v['dw_money'],bcmul($v['dw_money'], $min['odds'], 2),2);
                            MoneyLog::create([
                                'user_id' => $v['user_id'],
                                'log_content' => '猜涨跌',
                                'type' => 2,
                                'log_status'=>5,
                                'chance_money' => $change_money,
                                'dw_money' => bcadd($dw_money, $change_money, 2),
                                'add_time' => time(),
                            ]);
                            User::where(['user_id'=>$v['user_id']])->update(['dw_money'=> bcadd($dw_money, $change_money, 2)]);
                            BtcPost::where(['id' => $v['id']])->update(['status' => 1]);
                        } else {
                            BtcPost::where(['id' => $v['id']])->update(['status' => 2]);
                        }
                    }
                } elseif ($result['is_fall'] == 1) {//开启了跌的风控
                    $rise_price = bcsub(bcdiv($change_number, 10000, 4),1,4);
                    $data = [
                        'btc_price' =>bcsub($btc_id['btc_price'],bcmul($btc_id['btc_price'], $rise_price, 4),4),
                        'change' => '-' . bcmul($rise_price,100,2),
                        'dispaly_name' => 'okex交易所',
                        'add_time' => time()
                    ];
                    $get_btc_id = Db::table('dw_btc')->insertGetId($data);
                    $btc_post = BtcPost::where(['btc_id' => $get_btc_id])->select();
                    foreach ($btc_post as &$v) {
                        if ($v['type'] == 2) {
                            $dw_money = Db::table('dw_users')->where(['user_id' => $v['user_id']])->value('dw_money');
                            $change_money = bcadd($v['dw_money'],bcmul($v['dw_money'], $min['odds'], 2),2);
                            MoneyLog::create([
                                'user_id' => $v['user_id'],
                                'log_content' => '猜涨跌',
                                'type' => 2,
                                'log_status'=>5,
                                'chance_money' => $change_money,
                                'dw_money' => bcadd($dw_money, $change_money, 2),
                                'add_time' => time(),
                            ]);
                            User::where(['user_id'=>$v['user_id']])->update(['dw_money'=> bcadd($dw_money, $change_money, 2)]);
                            BtcPost::where(['id' => $v['id']])->update(['status' => 1]);
                        } else {
                            BtcPost::where(['id' => $v['id']])->update(['status' => 2]);
                        }
                    }
                }
            } else {//自动获取开奖接口
                    $url = "http://api.zb.cn/data/v1/ticker?market=btc_usdt";
                    $data = Curl::get($url);
                    $coin_price = $data["ticker"]['buy'];//当前的比特币的价格
                    $change = bcsub($coin_price, $btc_id['btc_price'], 4); //幅度
                    if ($change == 0) {
                        $data = [
                            'btc_price' => $result['btc_price'],
                            'change' => 0.00,
                            'dispaly_name' => 'okex交易所',
                            'add_time' => time()
                        ];
                        $get_btc_id = Db::table('dw_btc')->insertGetId($data);
                        $btc_post = BtcPost::where(['btc_id' => $get_btc_id])->select();
                        foreach ($btc_post as &$v) {
                            $dw_money = Db::table('dw_users')->where(['user_id' => $v['user_id']])->value('dw_money');
                            MoneyLog::create([
                                'user_id' => $v['user_id'],
                                'log_content' => '猜涨跌',
                                'type' => 2,
                                'log_status'=>5,
                                'chance_money' => $v['dw_money'],
                                'dw_money' => bcadd($dw_money, $v['dw_money'], 2),
                                'add_time' => time(),
                            ]);
                            User::where(['user_id'=>$v['user_id']])->update(['dw_money'=> bcadd($dw_money, $v['dw_money'], 2)]);
                            BtcPost::where(['id' => $v['id']])->update(['status' => 2]);
                        }
                    } elseif ($change > 0) {
                        $change = '+' . bcmul(bcdiv($change, $btc_id['btc_price'], 4),100,2);
                        $data = [
                            'btc_price' => $coin_price,
                            'change' => $change,
                            'dispaly_name' => 'okex交易所',
                            'add_time' => time()
                        ];
                        $get_btc_id = Db::table('dw_btc')->insertGetId($data);
                        $btc_post = BtcPost::where(['btc_id' => $get_btc_id])->select();
                        foreach ($btc_post as &$v) {
                            if ($v['type'] == 1) {
                                $dw_money = Db::table('dw_users')->where(['user_id' => $v['user_id']])->value('dw_money');
                                $change_money = bcadd($v['dw_money'],bcmul($v['dw_money'], $min['odds'], 2),2);
                                MoneyLog::create([
                                    'user_id' => $v['user_id'],
                                    'log_content' => '猜涨跌',
                                    'type' => 2,
                                    'log_status'=>5,
                                    'chance_money' => $change_money,
                                    'dw_money' => bcadd($dw_money, $change_money, 2),
                                    'add_time' => time(),
                                ]);
                                User::where(['user_id'=>$v['user_id']])->update(['dw_money'=>bcadd($dw_money, $change_money, 2)]);
                                BtcPost::where(['id' => $v['id']])->update(['status' => 1]);
                            } else {
                                BtcPost::where(['id' => $v['id']])->update(['status' => 2]);
                            }
                        }
                    } else {
                        $change = bcmul(bcdiv($change, $btc_id['btc_price'], 4),100,2);
                        $data = [
                            'btc_price' => $coin_price,
                            'change' => $change,
                            'dispaly_name' => 'okex交易所',
                            'add_time' => time()
                        ];
                        $get_btc_id = Db::table('dw_btc')->insertGetId($data);
                        $btc_post = BtcPost::where(['btc_id' => $get_btc_id])->select();
                        foreach ($btc_post as &$v) {
                            if ($v['type'] == 2) {
                                $dw_money = Db::table('dw_users')->where(['user_id' => $v['user_id']])->value('dw_money');
                                $change_money = bcadd($v['dw_money'],bcmul($v['dw_money'], $min['odds'], 2),2);
                                MoneyLog::create([
                                    'user_id' => $v['user_id'],
                                    'log_content' => '猜涨跌',
                                    'type' => 2,
                                    'log_status'=>5,
                                    'chance_money' =>$change_money,
                                    'dw_money' => bcadd($dw_money, $change_money, 2),
                                    'add_time' => time(),
                                ]);
                                User::where(['user_id'=>$v['user_id']])->update(['dw_money'=>bcadd($dw_money, $change_money, 2)]);
                                BtcPost::where(['id' => $v['id']])->update(['status' => 1]);
                            } else {
                                BtcPost::where(['id' => $v['id']])->update(['status' => 2]);
                            }
                        }

                    }
                }
            }
    }
}