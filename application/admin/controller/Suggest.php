<?php
/**
 * 文件描述
 * Create on : 2017/8/11 17:00
 * Autor email :416148489@qq.com
 * Create by sucaihuo
 */

namespace app\admin\controller;


use app\common\model\UserProposal;
use controller\BasicAdmin;
use service\DataService;
use service\LogService;
use think\Db;
class Suggest extends BasicAdmin
{
    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'dw_user_proposal';

    /**
     * 用户列表
     */
    public function index()
    {
        // 设置页面标题
        $this->title = '反馈列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = UserProposal::alias('a')
            ->join('dw_users b', 'b.user_id=a.user_id')
            ->field('a.id, a.status, a.status_msg, a.proposal_content, a.img1, a.img2, a.img3, a.add_time, b.user_name, b.user_phone')
            ->order('a.add_time', 'desc');
        // 应用搜索条件
        foreach (['user_name', 'user_phone'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $db->where($key, 'like', "%{$get[$key]}%");
            }
        }
        // 实例化并显示
        return parent::_list($db);
    }

    protected function _data_filter(&$data){
        if($this->request->action() == "index"){
            foreach($data as &$v){
                $v['add_time']=date('Y-m-d H:i:s',$v['add_time']);
            }
        }
    }

    /**
     * 回复
     */
    public function edit()
    {
        return $this->_form($this->table,'index', '反馈回复成功','suggest/index');
    }
    protected function _form_result(&$result) {
        // 写入日志
        if ($this->request->action() == 'edit') {
            if ($result) {
                LogService::write('系统管理', '用户反馈回复成功');
            } else {
                LogService::write('系统管理', '用户反馈回复失败');
            }
        }
    }
    /**
     * 删除反馈
     */
    public function del()
    {
        if (DataService::update($this->table)) {
            LogService::write('系统管理', '用户反馈删除成功');
            $this->success("反馈删除成功！", 'suggest/index');
        }
        LogService::write('系统管理', '用户反馈删除失败');
        $this->error("反馈删除失败，请稍候再试！");
    }
}