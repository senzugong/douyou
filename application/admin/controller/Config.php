<?php

namespace app\admin\controller;

use controller\BasicAdmin;
use service\LogService;

/**
 * 后台参数配置控制器
 * Class Config
 * @package app\admin\controller
 * @author sucaihuo <416148489@qq.com>
 * @date 2017/02/15 18:05
 */
class Config extends BasicAdmin
{

    /**
     * 当前默认数据模型
     * @var string
     */
    public $table = 'SystemConfig';

    /**
     * 当前页面标题
     * @var string
     */
    public $title = '网站参数配置';

    /**
     * 显示系统常规配置
     */
    public function index()
    {
        if (!$this->request->isPost()) {
            $this->assign('title', $this->title);
            return view();
        }
        foreach ($this->request->post() as $key => $vo) {

            sysconf($key, $vo);
        }
        LogService::write('系统管理', '修改系统配置参数成功');
        $this->success('数据修改成功！', '');
    }

    /**
     * 文件存储配置
     */
    public function file()
    {
        $this->title = '文件存储配置';
        $alert = ['type' => 'success', 'title' => '操作提示', 'content' => '文件引擎参数影响全局文件上传功能，请勿随意修改！'];
        $this->assign('alert', $alert);
        return $this->index();
    }

}
