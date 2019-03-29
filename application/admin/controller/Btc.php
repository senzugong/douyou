<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/13
 * Time: 17:39
 */

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use service\LogService;
use think\Db;

class Btc extends BasicAdmin {

    public $table = 'dw_btc_set';

    /**
     * BTC设置
     * @return array|string
     */
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

    /**
     * 添加期数
     * @return array|string
     */
    public function add() {
        return $this->_form($this->table,'index', '新增成功','');
    }
    protected function _form_filter(&$data) {
        if ($this->request->action() == 'add' && $this->request->isPost()) {
            if ($data['rise_status'] == 1) {
                $data['is_rise'] = 1;
            } else {
                $data['is_fall'] = 1;
            }
            unset($data['rise_status']);
        }
    }
    /**
     * 脉宝停用/启用
     */
    public function setstatus() {
        // 修改post的值，0是启用，1是停用
        $status = $this->request->post('status', '');
        $this->request->post(['status'=>$status==1?0:1]);
        $this->request->post(['id'=>input('set_id')]);
        if (DataService::update($this->table)) {
            if(input('status') == 1){
                LogService::write('系统管理', '脉宝奖励禁用成功');
                $this->success("禁用成功！", '');
            }
            LogService::write('系统管理', '脉宝奖励启用成功');
            $this->success("启用成功！", '');
        }
        LogService::write('系统管理', '脉宝奖励禁用失败');
        $this->error("禁用失败，请稍候再试！");
    }
}