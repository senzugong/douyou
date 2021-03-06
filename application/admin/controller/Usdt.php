<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 12:10
 */

namespace app\admin\controller;


use controller\BasicAdmin;
use service\DataService;
use service\LogService;
use service\ToolsService;
use think\Db;
class Usdt extends BasicAdmin
{
    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'dw_usdt_log';

    /**
     * 函数描述 文章列表
     * @autor sucaihuo email:416148489@qq.com
     */
    public function index(){

        $this->title = 'USDT交易列表';

        $get = $this->request->get();
        // 实例Query对象
        $db = Db::name($this->table)->alias('a')
            ->join('dw_users b','b.user_id=a.user_id')
            ->order('a.add_time', 'desc')
            ->field('a.*,b.user_name,b.true_name,b.user_phone');
        // 应用搜索条件
        foreach (['true_name' ,'user_phone'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $get[$key]=trim($get[$key]);
                if ($key == 'true_name') {
                    $db->where("b." . $key, 'like', "%{$get[$key]}%");
                }elseif($key == 'user_phone'){
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
        $this->request->post(['id'=> $this->request->post('log_id')]);
        if (DataService::update($this->table)) {
            LogService::write('系统管理', '删除用户USDT交易记录成功');
            $this->success("交易记录删除成功！", '');
        }
        LogService::write('系统管理', '删除用户USDT交易记录失败');
        $this->error("交易记录删除失败，请稍候再试！");
    }
}