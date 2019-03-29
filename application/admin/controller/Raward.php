<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/29
 * Time: 15:41
 */

namespace app\admin\controller;

use app\common\model\RawardSet;
use controller\BasicAdmin;
use service\DataService;
use service\LogService;

/**
 * Class Raward
 * 签到奖励设置
 * @package app\admin\controller
 */
class Raward extends BasicAdmin
{
    public $table = 'dw_raward_set';

    /**
     * 列表
     * @return array|string
     */
    public function index() {
        // 设置页面标题
        $this->title = '奖励列表';
        // 获取到所有GET参数
        // $get = $this->request->get();
        // 实例Query对象
        $db = RawardSet::order('id asc');
        // 应用搜索条件
        // foreach (['user_id', 'type', 'wallet_address', 'coin_address'] as $key) {
        //     if (isset($get[$key]) && $get[$key] !== '') {
        //         $db->where($key, $get[$key]);
        //     }
        // }
        // 实例化并显示
        return parent::_list($db);
    }
    protected function _data_filter(&$data) {
        if ($this->request->action() == 'index') {
            foreach ($data as &$v) {
                $v['status'] = $v['status'] == 1 ? 0 : 1;
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            }
        }
    }

    /**
     * 编辑
     * @return array|string
     */
    public function edit() {
        return $this->_form($this->table,'index', '编辑成功','');
    }

    /**
     * 设置状态
     */
    public function setstatus()
    {
        $status = $this->request->post('status');
        $status = $status == 1 ? 0 : 1;
        $this->request->post(['status'=> $status]);
        if (DataService::update($this->table)) {
            if($status == 1){
                LogService::write('系统管理', '签到奖励禁用成功');
                $this->success("签到奖励禁用成功！", '');
            }
            LogService::write('系统管理', '签到奖励启用成功');
            $this->success("签到奖励启用成功！", '');
        }
        LogService::write('系统管理', '操作失败');
        $this->error("操作失败，请稍候再试！");

    }
}