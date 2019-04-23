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
use think\Db;

/**
 * Class Raward
 * 签到奖励设置
 * @package app\admin\controller
 */
class Point extends BasicAdmin
{
    public $table = 'dw_slip_point';

    /**
     * 列表
     * @return array|string
     */
    public function index() {
        // 设置页面标题
        $this->title = '滑点设置';
        // 获取到所有GET参数
        // $get = $this->request->get();
        // 实例Query对象
        $db = Db::table($this->table);
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
                 $v['time'] = 1;
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
}