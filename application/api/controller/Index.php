<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 15:16
 */

namespace app\api\controller;

use app\api\validate\IndexValidate;
use app\common\library\Curl;
use app\common\model\Banner;
use app\common\model\Btc;
use app\common\model\BtcPost;
use app\common\model\GameResult;
use app\common\model\MoneyLog;
use app\common\model\RawardSet;
use app\common\model\TurntableLog;
use app\common\model\UserWeek;
use controller\BasicApi;
use think\Config;
use think\Db;
use think\Request;

/**
 * Class Index
 * 主页控制器
 * @package app\api\controller
 */
class Index extends BasicApi
{
    /**
     * 用户签到情况
     * @param Request $request
     * @return \think\Response
     */
    public function signInfo(Request $request) {
        // 用户信息
        $userInfo = $request->userInfo;
        // 昨天签到情况
        $signYesterday = $userInfo->userWeek()
            ->whereTime('add_time', 'yesterday')
            ->find();
        // 今天签到情况
        $signToday = $userInfo->userWeek()
            ->whereTime('add_time', 'today')
            ->find();
        // 今天签到情况
        $today_sign = 0;
        // 签到天数
        $dayNum = 0;
        if ($signToday) {
            $dayNum = $signToday->reward_num;
            $today_sign = 1; // 已签到
        } elseif ($signYesterday) {
            $dayNum = $signYesterday->reward_num;
        }
        $data = [
            'today_sign'=> $today_sign,
            'day_num'=> $dayNum
        ];
        return $this->response($data);
    }

    /**
     * 签到
     * @param Request $request
     * @param IndexValidate $validate
     * @return \think\Response
     * @throws \think\exception\DbException
     */
    public function sign(Request $request, IndexValidate $validate) {
        // 验证
        if (!$validate->scene('sign')->check([])) {
            return $this->response($validate->getError(), 304);
        }
        // 用户信息
        $userInfo = $request->userInfo;
        // 昨天签到情况
        $signYesterday = $userInfo->userWeek()
            ->whereTime('add_time', 'yesterday')
            ->find();
        // 签到天数
        $reward_num = 1;
        if ($signYesterday) {
            $reward_num = $signYesterday->reward_num >= 7 ? 1 : $signYesterday->reward_num + 1;
        }
        // 签到奖励设置
        $rawardSet = RawardSet::get($reward_num);
        Db::startTrans();
        try {
            // 签到
            UserWeek::create([
                'user_id'=> $userInfo->user_id,
                'reward_id'=> '',
                'reward_num'=> $reward_num,
                'reward_money'=> $rawardSet->raward_money,
                'add_time'=> time(),
            ]);
            // 用户得到抖金
            $userInfo->save(['dw_usdt'=> bcadd($userInfo->dw_usdt, $rawardSet->raward_money, 4)]);
            // 添加日志1USDT购买  2USDT提币 3转盘 4号码竞猜 5实时猜涨跌 6点位猜涨跌 7签到
            MoneyLog::create([
                'user_id'=> $userInfo->user_id,
                'log_content'=> '签到',
                'type'=> 2,
                'log_status'=> 7,
                'chance_usdt'=> $rawardSet->raward_money,
                'dw_usdt'=> $userInfo->dw_usdt,
                'add_time'=> time(),
            ]);
            // 提交数据
            Db::commit();
            $data = [
                'chance_money'=> $rawardSet->raward_money,
                'day_num'=> $reward_num,
                'today_sign'=> 1
            ];
            // 返回数据
            return $this->response($data);
        } catch (\Exception $exception) {
            // 回滚数据
            Db::rollback();
            // 返回数据
            return $this->response('签到失败', 307);
        }
    }

    /**
     * @param Request $request 首页的btc行情展示
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(Request $request){
        $url = "http://api.zb.cn/data/v1/ticker?market=btc_usdt";
        $data = Curl::get($url);
        $price_now = $data['ticker']['sell'];//当前的btc价格（出售）
        $btc_price = Db::table('dw_btc')->order('btc_id desc')->value('btc_price');//数据库中的btc价格
        $list['recharge'] =  bcdiv(bcsub($price_now,$btc_price),$price_now,4)*100;
        if(!$list['recharge'] || !$price_now){
            $list['recharge'] = 0;
        }
        //累计竞猜人数
        $list['user_count'] =   Db::table('dw_btc_order')->count();
        return $this->response($list);

    }

    /**首页banner
     * @param Request $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index_banner(Request $request){
        $banner_list['index'] = Banner::where("status = 0 and type IN (1,2)")->select();
        $banner_list['icon'] = Banner::where("status = 0 and type = 3")->select();
        foreach($banner_list as &$v){
            $v['img_url'] = $v['img_url'] ? Config::get('image_url').$v['img_url'] : '';
        }
        return $this->response($banner_list);
    }
}