<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/13
 * Time: 15:54
 */

namespace app\admin\controller;

use app\common\model\UsdtMall;
use controller\BasicAdmin;

/**
 * Class Mall
 * 场外交易发布
 * @package app\admin\controller
 */
class Mall extends BasicAdmin
{
    public $table = 'dw_usdt_mall';
    public function index() {
        // 设置页面标题
        $this->title = '场外交易列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = UsdtMall::alias('a')
            ->join('dw_users b', 'b.user_id=a.user_id')
            ->field('a.*,b.user_name,b.true_name')
            ->order('mall_id desc');
        // 应用搜索条件
        foreach (['user_id', 'status', 'user_phone'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                'user_id' == $key || 'status' == $key ? $db->where('a.'.$key, $get[$key]) :$db->where($key, $get[$key]);
            }
        }
        // 实例化并显示
        return parent::_list($db);
    }
}