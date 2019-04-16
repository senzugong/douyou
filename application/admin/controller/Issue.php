<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 10:14
 */

namespace app\admin\controller;

use controller\BasicAdmin;
use think\Db;
use app\common\model\User;

/**
 * Class Issue
 * @package app\admin\controller
 */
class Issue extends BasicAdmin
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
                // 总发行USDT
                $issue = Db::table('dw_usdt_issue')
                    ->where(['user_id'=> $val['user_id'], 'type'=> 1])
                    ->field('sum(usdt_num) usdt_num')
                    ->find();
                // 收回USDT
                $recover = Db::table('dw_usdt_issue')
                    ->where(['user_id'=> $val['user_id'], 'type'=> 2])
                    ->field('sum(usdt_num) usdt_num')
                    ->find();
                $val['issue_usdt'] = $issue['usdt_num'] ?: '0';
                $val['recover_usdt'] = $recover['usdt_num'] ?: '0';
            }
        }
    }
    public function edit(){
        if ($this->request->isPost()) {
            // 业务员
            $user = User::get($this->request->post('user_id'));
            if (!$user) {
                $this->error('业务员不存在');
            }
            Db::startTrans();
            try {
                // 发布USDT数量
                $usdt_num = $this->request->post('usdt_num');
                // 发布类型
                $type = $this->request->post('type', 1);
                if (!empty($usdt_num) && is_numeric($usdt_num)) {
                    // 记录发布信息
                    Db::table('dw_usdt_issue')
                        ->insert([
                            'usdt_num' =>$usdt_num,
                            'type' => $type,
                            'user_id' => $user['user_id'],
                            'admin_id' => session('user.id'),
                            'add_time' => time(),
                        ]);
                    // 根据类型计算USDT数量
                    if ($type == 1) {
                        // 发布
                        $user_usdt = bcadd($user['dw_usdt'], $usdt_num, 4);
                    } else {
                        // 收回
                        $user_usdt = bcsub($user['dw_usdt'], $usdt_num, 4);
                    }
                    // 修改分组和USDT
                    $user->save([
                        'group_id'=> $this->request->post('group_id'),
                        'dw_usdt'=> $user_usdt,
                    ]);
                } else {
                    // 修改分组
                    $user->save(['group_id'=> $this->request->post('group_id')]);
                }
                // 提交
                Db::commit();
                $result = true;
            } catch (\Exception $exception) {
                // 回滚
                Db::rollback();
                $result = false;
            }
            // 返回结果
            $result ? $this->success('修改成功') : $this->error('修改失败');
        }
        $this->success('编辑');
    }
}