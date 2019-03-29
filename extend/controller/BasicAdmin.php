<?php

namespace controller;

use service\DataService;
use think\Controller;
use think\db\Query;
use think\Db;
use think\Request;

/**
 * 后台权限基础控制器
 * Class BasicAdmin
 * @package controller
 */
class BasicAdmin extends Controller
{

    /**
     * 页面标题
     * @var string
     */
    public $title;

    /**
     * 默认操作数据表
     * @var string
     */
    public $table;

    /**
     * 默认检查用户登录状态
     * @var bool
     */
    public $checkLogin = true;

    /**
     * 默认检查节点访问权限
     * @var bool
     */
    public $checkAuth = true;


    /**
     * 表单默认操作
     * @param Query $dbQuery 数据库查询对象
     * @param string $tplFile 显示模板名字
     * @param string $pkField 更新主键规则
     * @param array $where 查询规则
     * @param array $extendData 扩展数据
     * @return array|string
     */

    protected function _form($dbQuery = null,$tplFile = '',$msg="",$url="", $pkField = '', $where = [], $extendData = [])
    {
        if($url == ''){
            $url=$this->request->controller()."/index";
        }

        $db = is_null($dbQuery) ? Db::name($this->table) : (is_string($dbQuery) ? Db::name($dbQuery) : $dbQuery);
        $pk = empty($pkField) ? ($db->getPk() ? $db->getPk() : 'id') : $pkField;
        $pkValue = $this->request->request($pk, isset($where[$pk]) ? $where[$pk] : (isset($extendData[$pk]) ? $extendData[$pk] : null));
        // 非POST请求, 获取数据并显示表单页面
        if (!$this->request->isPost()) {
            $vo = ($pkValue !== null) ? array_merge((array)$db->where($pk, $pkValue)->where($where)->find(), $extendData) : $extendData;
            if (false !== $this->_callback('_form_filter', $vo)) {
                empty($this->title) || $this->assign('title', $this->title);
                return $this->fetch($tplFile, ['vo' => $vo]);
            }
            return $vo;
        }

        // POST请求, 数据自动存库
        $data = array_merge($this->request->post(), $extendData);
        if (false !== $this->_callback('_form_filter', $data)) {
            $result = DataService::save($db, $data, $pk, $where);
            if (false !== $this->_callback('_form_result', $result)) {
                $result !== false ? $this->success($msg, $url) : $this->error('数据保存失败, 请稍候再试!');
            }
        }
    }

    /**
     * 列表集成处理方法
     * @param Query $dbQuery 数据库查询对象
     * @return array|string
     */
    protected function _list($dbQuery = null)
    {

        $db = is_null($dbQuery) ? Db::name($this->table) : (is_string($dbQuery) ? Db::name($dbQuery) : $dbQuery);
        // 列表排序默认处理
        $result = [];
       // $count=$db->count();
        $result['list'] = $db->select();
        if($this->request->isAjax()){
            if (false !== $this->_callback('_data_filter', $result['list'])) {
                return json([
                    'data'=>$result,
                    'status'=>'200',
                    'total'=>count($result['list']),
                ]);
            }
        }
        if ($this->request->isGet()) {
            if(false !== $this->_callback('_data_filter', $result['list'])){
                return $this->fetch();
            }


        }
    }

    /**
     * 当前对象回调成员方法
     * @param string $method
     * @param array|bool $data
     * @return bool
     */
    protected function _callback($method, &$data)
    {
        foreach ([$method, "_" . $this->request->action() . "{$method}"] as $_method) {
            if (method_exists($this, $_method) && false === $this->$_method($data)) {
                return false;
            }
        }
        return true;
    }

}
