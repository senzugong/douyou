<?php


namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use think\Db;

/**
 * 系统日志管理
 * Class User
 * @package app\admin\controller
 * @author sucaihuo <416148489@qq.com>
 * @date 2017/02/15 18:12
 */
class Log extends BasicAdmin
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'SystemLog';

    /**
     * 日志列表
     */
    public function index()
    {
        $this->title = '系统操作日志';
        $get = $this->request->get();
        $actions = Db::name($this->table)->group('action')->column('action');
        $db = Db::name($this->table)->order('id desc');
        foreach (['action', 'content', 'username'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $db->where($key, 'like', "%{$get[$key]}%");
            }
        }
        $this->assign('actions', $actions);
        return parent::_list($db);
    }



    /**
     * 日志删除操作
     */
    public function del()
    {
        if (DataService::update($this->table)) {
            $this->success("日志删除成功!", 'log/index');
        }
        $this->error("日志删除失败, 请稍候再试!");
    }

}
