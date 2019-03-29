<?php

namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use think\Db;

/**
 * 系统权限管理控制器
 * Class Auth
 * @package app\admin\controller
 * @author sucaihuo <416148489@qq.com>
 * @date 2017/02/15 18:13
 */
class Auth extends BasicAdmin
{

    /**
     * 默认数据模型
     * @var string
     */
    public $table = 'SystemAuth';

    /**
     * 权限列表
     */
    public function index()
    {
        $this->title = '系统权限管理';

        return parent::_list($this->table);
    }

    /**
     * 权限授权
     * @return string
     */
    public function apply()
    {

        $auth_id = $this->request->get('id', '0');
        $method = '_apply_' . strtolower($this->request->get('action', '0'));
        if (method_exists($this, $method)) {
            return $this->$method($auth_id);
        }
        $this->assign('title', '节点授权');
        return $this->_form($this->table, 'apply','');
       // return view();
    }

    /**
     * 读取授权节点
     * @param $auth_id
     */
    protected function _apply_getnode($auth_id)
    {
        $nodes = NodeService::get();
        $checked = Db::name('SystemAuthNode')->where('auth', $auth_id)->column('node');
        foreach ($nodes as $key => &$node) {
            $node['checked'] = in_array($node['node'], $checked);
            if (empty($node['is_auth']) && substr_count($node['node'], '/') > 1) {
                unset($nodes[$key]);
            }
        }
        $allnode = $this->_apply_filter(ToolsService::arr2tree($nodes, 'node', 'pnode', '_sub_'));
        $this->success('获取节点成功!', '', $allnode);
    }

    /**
     * 保存授权节点
     * @param $auth_id
     */
    protected function _apply_save($auth_id)
    {
        $data = [];
        $post = $this->request->post();
        foreach (isset($post['nodes']) ? $post['nodes'] : [] as $node) {
            $data[] = ['auth' => $auth_id, 'node' => $node];
        }
        Db::name('SystemAuthNode')->where('auth', $auth_id)->delete();
        Db::name('SystemAuthNode')->insertAll($data);
        $this->success('节点授权更新成功!', 'auth/index');
    }

    /**
     * 节点数据拼装
     * @param array $nodes
     * @param int $level
     * @return array
     */
    protected function _apply_filter($nodes, $level = 1)
    {
        foreach ($nodes as $key => &$node) {
            if (!empty($node['_sub_']) && is_array($node['_sub_'])) {
                $node['_sub_'] = $this->_apply_filter($node['_sub_'], $level + 1);
            } elseif ($level < 3) {
                unset($nodes[$key]);
            }
        }
        return $nodes;
    }

    /**
     * 权限添加
     */
    public function add()
    {
        return $this->_form($this->table,'form', '添加成功');
    }

    /**
     * 权限编辑
     */
    public function edit()
    {
        return $this->_form($this->table,'form', '编辑成功');
    }

    /**
     * 权限禁用
     */
    public function setstatus()
    {
        if (DataService::update($this->table)) {
            if(input('status') == 1){
                $this->success("权限启用成功!", '');
            }
            $this->success("权限禁用成功!", '');
        }
        $this->error("权限禁用失败, 请稍候再试!");
    }


    /**
     * 权限删除
     */
    public function del()
    {
        if (DataService::update($this->table)) {
            $id = $this->request->post('id');
            Db::name('SystemAuthNode')->where('auth', $id)->delete();
            $this->success("权限删除成功!", 'auth/index');
        }
        $this->error("权限删除失败, 请稍候再试!");
    }

    /**
     * 函数描述 更新权限名称
     * @param 变量说明
     * @param 变量说明
     * @return 返回值
     * @autor sucaihuo email:416148489@qq.com
     */
    public function checkAuthName(){
        $id=input('id','','int');//权限id
        $name=input('title');
        if(!empty($name) && !empty($id)){
            $auth_info=Db::name($this->table)->where('title',$name)->find();

            if($auth_info){
                $this->error("权限名称已经存在");
            }else{
                Db::name($this->table)->where('id',$id)->update(['title'=>$name]);
                $this->success("更新成功!");
            }
        }else{
            $this->error("参数错误");
        }

    }
}
