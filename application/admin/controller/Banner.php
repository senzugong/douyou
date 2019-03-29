<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/29
 * Time: 10:42
 */

namespace app\admin\controller;

use controller\BasicAdmin;
use app\common\model\Banner as BannerModel;
use service\DataService;
use service\LogService;

/**
 * Class Banner
 * 滚动横幅
 * @package app\admin\controller
 */
class Banner extends BasicAdmin
{
    public $table = 'dw_banner';
    /**
     * 列表
     * @return array|string
     */
    public function index() {
        // 设置页面标题
        $this->title = '横幅列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = BannerModel::order('banner_id asc');
        // 应用搜索条件
        foreach (['title', 'type', 'status'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $key == 'title' ? $db->where($key, 'like', "%{$get[$key]}%") : $db->where($key, $get[$key]);
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
     * 新增
     * @return array|string
     */
    public function add() {
        return $this->_form($this->table,'form', '新增成功','');
    }

    /**
     * 编辑
     * @return array|string
     */
    public function edit() {
        if ($this->request->isPost() && $this->request->post('banner_id')) {
            $this->BannerModel = BannerModel::get($this->request->post('banner_id'));
            $this->img_url = $this->BannerModel->img_url;
        }
        return $this->_form($this->table,'form', '编辑成功','banner/index','banner_id');
    }
    protected function _form_result($result) {
        if ($this->request->action() == 'edit' && $this->request->isPost() && $result !== false &&$this->img_url != $this->request->post('img_url')) {
            @unlink(realpath(ROOT_PATH . $this->img_url));
        }
    }

    /**
     * 设置状态
     */
    public function setstatus()
    {
        if (DataService::update($this->table)) {
            if(input('status') == 1){
                LogService::write('系统管理', '横幅禁用成功');
                $this->success("横幅禁用成功！", '');
            }
            LogService::write('系统管理', '横幅启用成功');
            $this->success("横幅启用成功！", '');
        }
        LogService::write('系统管理', '操作失败');
        $this->error("操作失败，请稍候再试！");

    }
}