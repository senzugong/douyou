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
use think\Db;
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
        $end_time = date('Y-m', strtotime("$start_time+1month"));
        //$end_time = strtotime($start_time)*24*3600*30 ;
            // 获取用户账单记录
        $data['list'] = $userInfo->userMoneyLog()
            ->whereTime('add_time', 'between',[$start_time,$end_time])
            ->page($page)
            ->order('add_time desc')
            ->select();
        $data['all_count'] = $userInfo->userMoneyLog()
            ->whereTime('add_time', 'between',[$start_time,$end_time])
            ->count();
        if ($page == 1) {
            //支出
            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time);
            $expenditure =$userInfo->userMoneyLog()
                ->whereTime('add_time', 'between',[$start_time,$end_time])
                ->where("type=1")
                ->select();
            $all_expenditure = 0.00;
            foreach($expenditure as &$v){
                $all_expenditure = bcadd($all_expenditure,$v['chance_money'],2) ;
            };
            $data['expenditure'] = $all_expenditure;
            $add_income = 0.00;
            $income = $userInfo->userMoneyLog()
                ->whereTime('add_time', 'between',[$start_time,$end_time])
                ->where("type = 2")
                ->select();
            foreach($income as &$v){
                $add_income = bcadd($add_income ,$v['chance_money'],2) ;
            };
            $data['income'] = $add_income;
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
            $data['change_log']['service_charge'] = bcmul($data['change_log']['dw_money'],0.05,2);
        }
        $data['bill_info']['add_time'] = $this->getTime($data['bill_info']['add_time']);
        return $this->response($data);
    }
}