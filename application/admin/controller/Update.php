<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/1
 * Time: 15:23
 */

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use service\LogService;
use think\Db;

/**
 * Class Update
 *
 * @package app\admin\controller
 */
class Update extends BasicAdmin
{
    public $table = 'dw_app_model';

    public function index() {
        // 设置页面标题
        $this->title = 'BTC涨跌设置列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = Db::name($this->table);
        // 应用搜索条件
        foreach (['set_name'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $db->where($key, 'like', "%{$get[$key]}%");
            }
        }
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
     * 发布新版本
     * @return array|string
     */
    public function add() {
        return $this->_form($this->table,'index', '新增成功','');
    }
    /**
     * 删除记录
     */
    public function del()
    {
        if (DataService::update($this->table)) {
            LogService::write('系统管理', '删除版本更新记录成功');
            $this->success("更新记录删除成功！", '');
        }
        LogService::write('系统管理', '删除版本更新记录失败');
        $this->error("更新记录删除失败，请稍候再试！");
    }
}