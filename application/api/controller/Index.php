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
use app\common\model\RawardSet;
use app\common\model\UsdtLog;
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
        $ids = Db::table('dw_users')->where(['invite_user'=>$userInfo['user_id']])->group('user_id')->field('GROUP_CONCAT(user_id) as ids')->find();
        if($ids) {
            $reward = 0.0000;
            $list = Db::table('dw_btc_order')->where("user_id IN ({$ids['ids']})")->select();
            foreach($list as &$v){
                $reward = bcadd($reward,bcdiv($v['sx_fee'],2,4),4);
            }
        }else{
            $reward = 0.0000;
        }
        $invite_count = Db::table('dw_users')->where(['invite_user'=>$userInfo['user_id']])->count();
        $data = [
            'today_sign'=> $today_sign,
            'day_num'=> $dayNum,
            'invite_count'=>$invite_count,
            'reward'=>$reward
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
           UsdtLog::create([
                'user_id'=> $userInfo->user_id,
                'log_content'=> '蚪游签到',
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


}