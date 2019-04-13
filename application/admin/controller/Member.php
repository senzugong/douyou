<?php
/**
 * 文件描述
 * Create on : 2017/8/11 10:04
 * Autor email :416148489@qq.com
 * Create by sucaihuo
 */

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use service\LogService;
use think\Db;
use app\common\model\User;

class Member extends BasicAdmin
{
    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'dw_users';

    /**
     * 用户列表
     */
    public function index()
    {
        // 设置页面标题
        $this->title = '用户列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = User::order('add_time', 'desc');
        // 应用搜索条件
        foreach (['user_name', 'user_phone', 'role_id'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $key == 'role_id' ? $db->where($key, $get[$key]) :$db->where($key, 'like', "%{$get[$key]}%");
            }
        }
        // 实例化并显示
        return parent::_list($db);
    }
    /**
     * 表单数据默认处理
     * @param array $data
     * @autor sucaihuo email:416148489@qq.com
     */
    public function _form_filter(&$data) {
        if ($this->request->action() == "edit") {
            if (!$this->request->isPost()) {
                $data['user_avatar'] = $data['user_avatar'] ? '/'.$data['user_avatar'] : '';
                $data['add_time'] = date('Y-m-d H:i:s', $data['add_time']);
            }
        }
    }

    /**
     * 用户审核
     */
    public function examine()
    {
        $sql = Db::table('dw_users')->alias('a')
            ->join('dw_user_examine b','b.user_id = a.user_id')
            ->field('a.user_id, a.user_name, a.true_name, a.card_num, a.is_examine, b.img1, b.img2, b.img3, b.status');
        if ($this->request->isPost()) {
            $result = $sql->where(['a.user_id'=> $this->request->post('user_id')])
                ->update([
                    'b.status'=> $this->request->post('status'),
                    'a.is_examine'=> $this->request->post('status') == 1 ? 1 : 3,
                ]);
            if ($result !== false) {
                Db::table('dw_users')->where(['user_id'=> $this->request->post('user_id')])->setInc('complete_rate',10);
                $this->success('审核成功', 'member/index');
            } else {
                $this->error('数据保存失败, 请稍候再试!');
            }
        } else {
            $sql->order('b.examine_id desc');
        }
        return $this->_form($sql, '', '审核成功','member/index','a.user_id', ['a.user_id'=> $this->request->get('user_id')]);
    }

    /**
     * 用户编辑
     */
    public function edit()
    {
        return $this->_form($this->table,'form', '编辑成功','member/index','user_id');
    }

    protected function _data_filter(&$data) {
        if($this->request->action() == "index"){
            foreach($data as $k=>&$v){
                $data[$k]['add_time'] = date('Y-m-d H:m:s',$v['add_time']);
                $v['user_avatar'] = $v['user_avatar'] = $v['user_avatar'] ? '/'.$v['user_avatar'] : '';
            }
        }
    }

   protected function _form_result(&$result) {
       // 写入日志
       if($this->request->action() == "examine"){
           if ($result) {
               LogService::write('系统管理', '用户信息审核成功');
           } else {
               LogService::write('系统管理', '用户信息审核失败');
           }
       }
       if($this->request->action() == "edit"){
           if ($result) {
               LogService::write('系统管理', '用户信息修改成功');
           } else {
               LogService::write('系统管理', '用户信息修改失败');
           }
       }
   }

    /**
     * 用户禁用
     */
    public function setstatus()
    {
        if (DataService::update($this->table)) {
            if(input('status') == 1){
                LogService::write('系统管理', '用户禁用成功');
                $this->success("用户禁用成功！", 'member/index');
            }
            LogService::write('系统管理', '用户启用成功');
            $this->success("用户启用成功！", 'member/index');
        }
        LogService::write('系统管理', '用户禁用失败');
        $this->error("用户禁用失败，请稍候再试！");

    }
}