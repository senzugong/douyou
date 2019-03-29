<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 15:06
 */

namespace app\api\controller;

use app\api\validate\WalletValidate;
use app\common\model\ChangeLog;
use app\common\model\MoneyLog;
use controller\BasicApi;
use think\Request;

class Bill extends BasicApi
{
    /**
     * 用户账单列表
     * @param Request $request
     * @return \think\Response
     */
    public function bill_list(Request $request)
    {
        $userInfo = $request->userInfo;
        $page = $request->param('page', 1);
        $start_time = $request->param('start_time',date('Y-m-1'));
        $end_time = $request->param('end_time',time());
            // 获取用户账单记录
        $data['list'] = $userInfo->userMoneyLog()
            ->whereTime('add_time', 'between',[$start_time,$end_time])
            ->page($page)
            ->order('add_time desc')
            ->select();
        if ($page == 1) {
            //支出
            $data['expenditure'] = $userInfo->userMoneyLog()
                ->whereTime('add_time', 'between',[$start_time,$end_time])
                ->where("type = 1")
                ->sum('chance_money');
            //
            $data['income'] = $userInfo->userMoneyLog()
                ->whereTime('add_time', 'between',[$start_time,$end_time])
                ->where("type = 2")
                ->sum('chance_money');
        }
        foreach($data['list'] as $v){
            $v['add_time'] = date('m月d日 H:i', $v['add_time']);
        }
        return $this->response($data);
    }
    /**
     * 用户账单详情
     * @param Request $request
     * @return \think\Response
     */
    public function bill_detail(Request $request,WalletValidate $walletValidate)
    {
        // 验证数据
        if (!$walletValidate->scene('bill')->check($request->post())) {
            return  $this->response( $walletValidate->getError() ,304);
        }
        $bill_id = $request->post('bill_id');
        $data['bill_info'] = MoneyLog::where(['log_id'=>$bill_id])->find();
        if($data['bill_info']['charge_type'] == 2)//充值提现
        {
           $data['change_log'] = ChangeLog::where(['log_id'=>$data['bill_info']['changelog_id']])->find();
            $data['change_log']['add_time'] = $this->getTime($data['change_log']['add_time']);
        }
        $data['bill_info']['add_time'] = $this->getTime($data['bill_info']['add_time']);
        return $this->response($data);
    }
}