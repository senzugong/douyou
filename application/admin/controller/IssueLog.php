<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/16
 * Time: 16:21
 */

namespace app\admin\controller;

use controller\BasicAdmin;
use think\Db;

/**
 * Class IssueLog
 * 发行日志
 * @package app\admin\controller
 */
class IssueLog extends BasicAdmin
{
    public $table = 'dw_usdt_issue';
    public function index() {
        // 设置页面标题
        $this->title = '发行列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = Db::table($this->table)
            ->alias('a')
            ->join('system_user b', 'b.id=a.admin_id','left')
            ->join('dw_users c', 'c.user_id =a.user_id')
            ->field('a.*,b.username as admin_name, c.user_name, c.true_name, c.user_phone')
            ->order('a.id desc');
        // 应用搜索条件
        foreach (['user_phone'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $db->where('c.'.$key, $get[$key]);
            }
        }
        // 实例化并显示
        return parent::_list($db);
    }
    protected function _data_filter(&$data) {
        if ($this->request->action() == 'index') {
            foreach ($data as &$val) {
                $val['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
            }
        }
    }
}