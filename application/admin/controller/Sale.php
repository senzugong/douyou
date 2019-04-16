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
use service\LogService;
use think\Db;
use app\common\model\User;

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
        $db = Db::name($this->table)
            ->alias('a')
            ->join('dw_group b', 'b.group_id = a.group_id and b.status=1', 'left')
            ->where('role_id', 1)
            ->field('a.*,b.group_name');
        // 应用搜索条件
        foreach (['user_name', 'user_phone', 'group_id'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $key == 'group_id' ? $db->where('a.'.$key, $get[$key]) :$db->where($key, 'like', "%{$get[$key]}%");
            }
        }
        // 分组
        $group = Db::table('dw_group')->where(['status'=> 1])->select();
        $this->assign('group', $group);
        // 实例化并显示
        return parent::_list($db);
    }

    /**
     * 分组用户
     * @return array|string
     */
    public function edit() {
        $this->assign('group', []);
        return $this->_form($this->table,'index', '编辑成功','');
    }

    /**
     * 添加USDT
     */
    // public function add_usdt() {
    //     if ($this->request->isPost()) {
    //         // 增加的usdt
    //         $regulation = $this->request->post('regulation');
    //         $user_id = $this->request->post('user_id');
    //         $type = $this->request->post('type');
    //         Db::startTrans();
    //         try {
    //             // 增加用户usdt
    //             $user = Db::table($this->table)->where(['user_id'=> $user_id])->find();
    //             $dw_usdt = bcadd($user['dw_usdt'], $regulation, 4);
    //             Db::table($this->table)->where(['user_id'=> $user_id])->update(['dw_usdt'=> $dw_usdt]);
    //             // 记录USDT发行记录
    //             Db::table('dw_usdt_issue')
    //                 ->insert([
    //                     'usdt_num'=> $regulation,
    //                     'type'=> $type,
    //                     'user_id'=> $user_id,
    //                     'add_time'=> time(),
    //                 ]);
    //             LogService::write('系统管理', $user['user_name'].'发行USDT成功');
    //             Db::commit();
    //         } catch (\Exception $exception) {
    //             Db::rollback();
    //         }
    //         $this->success('新增成功');
    //     }
    //     $this->success('新增成功');
    // }

    /**
     * 邀请用户
     * @return array|string
     */
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
        if ($action == 'index') {
            foreach ($data as &$val) {
                $val['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
                $val['group_name'] = $val['group_name'] ? :'未分组';
                // 添加邀请数
                $val['invite_num'] = User::where(['invite_user'=> $val['user_id']])->count();
                // 所辖用户收入支出
                $pay = User::alias('a')
                    ->join('dw_usdt_log b','b.user_id=a.user_id')
                    ->where(['invite_user'=> $val['user_id'], 'type'=> 1])
                    ->field('sum(chance_usdt) as chance_usdt')
                    ->find();
                $income = User::alias('a')
                    ->join('dw_usdt_log b','b.user_id=a.user_id')
                    ->where(['invite_user'=> $val['user_id'], 'type'=> 2])
                    ->field('sum(chance_usdt) as chance_usdt')
                    ->find();
                $val['pay_usdt'] = $pay['chance_usdt'] ?: '0';
                $val['income_usdt'] = $income['chance_usdt'] ?: '0';
            }
        }
        if ($action == 'user') {
            foreach ($data as &$val) {
                $val['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
                // 获取用户消费金额
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