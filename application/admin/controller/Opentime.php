<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/13
 * Time: 14:50
 */

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use service\LogService;
use think\Db;

class Opentime extends BasicAdmin {
    /**
     * @var string  热度奖励设置表
     */
    public $table = 'dw_basic_opentime';

    /**
     * 热度列表
     * @return array|string
     */
    public function index() {
        // 设置页面标题
        $this->title = '开休市时间设置';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = Db::name($this->table);
        // 应用搜索条件
//        foreach (['type_name'] as $key) {
//            if (isset($get[$key]) && $get[$key] !== '') {
//                $db->where($key, 'like', "%{$get[$key]}%");
//            }
//        }
        // 实例化并显示
        return parent::_list($db);
    }
//    public function _data_filter(&$data) {
//        if ($this->request->action() == 'index') {
//            foreach ($data as &$v) {
//                $v['status'] = $v['status']==0 ? 1 : 0;
//            }
//        }
//    }
    public function edit() {
        return $this->_form($this->table,'index', '编辑成功','');
    }

    /**
     * 热度停用/启用
     */
    public function setstatus() {
        // 修改post的值，0是启用，1是停用
        $status = $this->request->post('status', '');
        $this->request->post(['status'=>$status==1?0:1]);
        $this->request->post(['id'=>input('type_id')]);
        if (DataService::update($this->table)) {
            if(input('status') == 1){
                LogService::write('系统管理', '热度奖励禁用成功');
                $this->success("禁用成功！", '');
            }
            LogService::write('系统管理', '热度奖励启用成功');
            $this->success("启用成功！", '');
        }
        LogService::write('系统管理', '热度奖励禁用失败');
        $this->error("禁用失败，请稍候再试！");
    }
}