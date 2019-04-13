<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/13
 * Time: 10:12
 */

namespace app\admin\controller;

use app\common\model\UsdtLog;
use controller\BasicAdmin;
use think\Db;

/**
 * Class Sale
 * 业务员管理
 * @package app\admin\controller
 */
class Sale extends BasicAdmin
{
    public $table = 'dw_users';
    public function index() {
        // 设置页面标题
        $this->title = '业务员列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = Db::name($this->table)->where('role_id', 1);
        // 应用搜索条件
        foreach (['user_name', 'user_phone'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $db->where($key, 'like', "%{$get[$key]}%");
            }
        }
        // 实例化并显示
        return parent::_list($db);
    }
    public function user() {
        // 设置页面标题
        $this->title = '业务员列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        if (empty($get['invite_user'])) {
            $this->error('参数错误!');
        }
        // 实例Query对象
        $db = Db::name($this->table)->where(['invite_user'=> $get['invite_user'], 'role_id'=> 0]);
        // 应用搜索条件
        foreach (['user_name', 'user_phone'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $db->where($key, 'like', "%{$get[$key]}%");
            }
        }
        $this->assign('invite_user', $get['invite_user']);
        // 实例化并显示
        return parent::_list($db);
    }
    public function _data_filter(&$data) {
        $action = $this->request->action();
        if ($action == 'index' || $action == 'user') {
            foreach ($data as &$val) {
                $val['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
                // 获取用户消费金额
                if ($action == 'user') {
                    // 日期处理
                    $start = $this->request->get('start_date');
                    $end = $this->request->get('end_date');
                    $start = $start ? $start . '00:00:00' : '2000-01-01 00:00:00';
                    $end = $end ? $end . '24:00:00' : date('Y-m-d H:i:s');
                    // 支出
                    $pay_usdt = UsdtLog::where(['user_id'=> $val['user_id'], 'type'=> 1])
                        ->whereTime('add_time', 'between', [$start, $end])
                        ->field('sum(dw_usdt) as pay')
                        ->find();
                    $val['pay_usdt'] = $pay_usdt['pay'] ? $pay_usdt['pay'] : 0;
                    // 收入
                    $income_usdt = UsdtLog::where(['user_id'=> $val['user_id'], 'type'=> 2])
                        ->whereTime('add_time', 'between', [$start, $end])
                        ->field('sum(dw_usdt) as pay')
                        ->find();
                    $val['income_usdt'] = $income_usdt['pay'] ? $income_usdt['pay'] : 0;
                }
            }
        }
    }
}