<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/14
 * Time: 13:41
 */

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use service\LogService;
use think\Db;

/**
 * Class Group
 * 业务员分组
 * @package app\admin\controller
 */
class Group extends BasicAdmin
{
    public $table = 'dw_group';
    public function index() {
        // 设置页面标题
        $this->title = '奖励列表';
        // 获取到所有GET参数
        // $get = $this->request->get();
        // 实例Query对象
        $db = Db::table($this->table)->order('group_id desc');
        // 应用搜索条件
        // foreach (['user_id', 'type', 'wallet_address', 'coin_address'] as $key) {
        //     if (isset($get[$key]) && $get[$key] !== '') {
        //         $db->where($key, $get[$key]);
        //     }
        // }
        // 实例化并显示
        return parent::_list($db);
    }
    public function _data_filter(&$data) {
        if ($this->request->action() == 'index') {
            foreach ($data as &$v) {
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            }
        }
    }
    /**
     * 新增
     * @return array|string
     */
    public function add() {
        return $this->_form($this->table,'index', '新增成功','');
    }

    /**
     * 编辑
     * @return array|string
     */
    public function edit() {
        return $this->_form($this->table,'index', '编辑成功','');
    }
    /**
     * 删除记录
     */
    public function del()
    {
        if (DataService::update($this->table)) {
            LogService::write('系统管理', '分组删除成功');
            $this->success("分组删除成功！", '');
        }
        LogService::write('系统管理', '分组删除失败');
        $this->error("分组删除失败，请稍候再试！");
    }
    /**
     * 状态
     */
    public function setstatus() {
        if (DataService::update($this->table)) {
            if(input('status') == 1){
                LogService::write('系统管理', '分组禁用成功');
                $this->success("禁用成功！", '');
            }
            LogService::write('系统管理', '分组启用成功');
            $this->success("启用成功！", '');
        }
        LogService::write('系统管理', '分组禁用失败');
        $this->error("禁用失败，请稍候再试！");
    }
}