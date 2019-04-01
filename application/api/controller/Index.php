<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 15:16
 */

namespace app\api\controller;

use app\api\validate\IndexValidate;
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
            $userInfo->save(['dw_money'=> bcadd($userInfo->dw_money, $rawardSet->raward_money, 4)]);
            // 添加日志
            MoneyLog::create([
                'user_id'=> $userInfo->user_id,
                'log_content'=> '签到',
                'type'=> 2,
                'log_status'=> 7,
                'chance_money'=> $rawardSet->raward_money,
                'dw_money'=> $userInfo->dw_money,
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
     * @param Request $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(Request $request){
        $userInfo = $request->userInfo;
        $list['user_create'] =ceil((time()-$userInfo['add_time'])/(3600*24));
        //btc开奖结果
       $list['btc_result'] =Btc::order('btc_id desc')->find();
       $list['result_time'] = bcsub(bcadd($list['btc_result']['add_time'],200),time());
       //买涨的人数
       $count = BtcPost::whereTime('add_time', 'today')->count();
        $list['btc_result']['count_num'] = $count;
//        $rise_num = BtcPost::whereTime('add_time', 'today')->where("type=1")->count();
        $rise = BtcPost::where(['status'=>0,'type'=>1])->sum('dw_money');//涨
        $fall = BtcPost::where(['status'=>0,'type'=>2])->sum('dw_money'); //跌
        if($rise && $fall){
            $list['rise'] = bcmul(bcdiv($rise,$rise+$fall,2),100) ;
            $list['fall'] = bcsub(100,$list['risa']);
        }else{
            if(!$rise && $fall){
                $list['rise'] = 30;
                $list['fall'] = 70;
            }
            if($rise && !$fall){
                $list['rise'] = 75;
                $list['fall'] = 25;
            }
            if(!$rise && !$fall){
                $list['rise'] = 50;
                $list['fall'] = 50;
            }
       }

       //时时彩开奖结果
       $list['number_game'] = GameResult::order('result_id desc')->find();

       $list['game_count']  = GameResult::count();
       //转盘中奖记录
       $list['turntable_result'] =
           TurntableLog::alias('a')
           ->join('dw_turntable_set b','a.turntable_id = b.id')
           ->join('dw_users c','a.user_id= c.user_id')
           ->where("a.status = 1")->limit(20)->order('a.add_time desc')
           ->field('c.user_name,c.user_phone,b.prize,a.*')
           ->select();
       foreach($list['turntable_result'] as &$v){
           if(!$v['user_name']){
               $v['user_name'] = substr_replace($v['user_phone'],'****',3,4);
           }
       }
        return $this->response($list);

    }

    /**首页banner
     * @param Request $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index_banner(Request $request){
        $banner_list = Banner::where(['status'=>0])->select();
        foreach($banner_list as &$v){
            $v['img_url'] = $v['img_url'] ? Config::get('image_url').$v['img_url'] : '';
        }
        return $this->response($banner_list);
    }
}