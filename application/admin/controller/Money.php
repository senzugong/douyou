<?php
/**
 * 文件描述
 * Create on : 2017/8/9 18:31
 * Autor email :416148489@qq.com
 * Create by sucaihuo
 */

namespace app\admin\controller;

use controller\BasicAdmin;
use service\LogService;
use think\Db;
use service\DataService;
use service\ToolsService;
class Money extends BasicAdmin
{
    public $table = 'dw_money_log';
    /**
     * 函数描述 文章列表
     * @autor sucaihuo email:416148489@qq.com
     */
    public function index(){

        $this->title = '抖金交易列表';

        $get = $this->request->get();
        // 实例Query对象
        $db = Db::name($this->table)->alias('a')
            ->join('dw_users b','b.user_id=a.user_id')
            ->order('a.add_time', 'desc')
            ->field('a.*,b.user_name');
        // 应用搜索条件
        foreach (['user_name'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $get[$key]=trim($get[$key]);
                if ($key == 'user_name') {
                    $db->where("b." . $key, 'like', "%{$get[$key]}%");
                } else {
                    $db->where("a." . $key, 'like', "%{$get[$key]}%");
                }
            }
        }

        return parent::_list($db);
    }
    protected function _data_filter(&$data){
        foreach($data as $k=>$v){
            $data[$k]['add_time']=date('Y-m-d H:i:s',$v['add_time']);
        }
    }
    /**
     * 删除交易记录
     */
    public function del()
    {
        if (DataService::update($this->table)) {
            LogService::write('系统管理', '删除用户人脉值交易记录成功');
            $this->success("交易记录删除成功！", '');
        }
        LogService::write('系统管理', '删除用户人脉值交易记录失败');
        $this->error("交易记录删除失败，请稍候再试！");
    }
}