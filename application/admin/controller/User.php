<?php
namespace app\admin\controller;

use controller\BasicAdmin;
use service\DataService;
use think\Db;
use app\common\model\User as UserModel;

/**
 * 系统用户管理控制器
 * Class User
 * @package app\admin\controller
 * @author sucaihuo <416148489@qq.com>
 * @date 2017/02/15 18:12
 */
class User extends BasicAdmin
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'SystemUser';


    public function getdata(){
        //会员最近七天注册数统计开始
        $starttime=date('Y-m-d',strtotime('-6 day'));
        $endtime=date('Y-m-d')." "."23:59:59";
        $time=array(
            date('Y-m-d',strtotime('-6 day')),
            date('Y-m-d',strtotime('-5 day')),
            date('Y-m-d',strtotime('-4 day')),
            date('Y-m-d',strtotime('-3 day')),
            date('Y-m-d',strtotime('-2 day')),
            date('Y-m-d',strtotime('-1 day')),
            date('Y-m-d')
        );
        $data=array();//存放数据
        $artice_data=array();
        $list=array();
        $user_list= UserModel::where('add_time','between time',[$starttime,$endtime])
            ->select();//用户总注册数
        // $articel_list=Db::name('rm_user_dynamic')
        //     ->where('add_time','between time',[$starttime,$endtime])
        //     ->select();//动态总发布数
        // $artlist=array();
        foreach($user_list as $key=>$val){
            $list[]=date('Y-m-d',$val['add_time']);
        }
        // foreach($articel_list as $key=>$val){
        //     $artlist[]=date('Y-m-d',$val['add_time']);
        // }
        $echars_list=array_count_values($list);
        // $echars_article_list=array_count_values($artlist);

        foreach($time as $k=>$v){
            $data[$k]['date']=$v;
            $data[$k]['num']=0;
            $artice_data[$k]['date']=$v;
            $artice_data[$k]['num']=0;

            foreach($echars_list as $key=>$val){
                if(isset($echars_list[$v]) && $key == $v){
                    $data[$k]['num']=$echars_list[$v];
                    unset($echars_list[$key]);
                }
            }
            // foreach($echars_article_list as $key=>$val){
            //     if(isset($echars_article_list[$v]) && $key == $v){
            //         $artice_data[$k]['num']=$echars_article_list[$v];
            //         unset($echars_article_list[$key]);
            //     }
            // }
        }
        foreach($data as $k=>$v){
            if($v['date'] == date('Y-m-d')){
                $data[$k]['date']="今天";
            }
            if(date('Y-m-d',strtotime('-1 day')) == $v['date']){
                $data[$k]['date']="昨天";
            }
        }
        //会员男女统计比例开始
        $sex_list=UserModel::field('count(user_id) as num')
            ->group('sex')
            ->order('sex desc')
            ->select(); //1是男2是女
        $sex=array();
        $sex[0]['name'] = "女";
        $sex[0]['value'] = isset($sex_list[0]) ? $sex_list[0]['num'] : 0;
        $sex[1]['name'] = "男";
        $sex[1]['value'] = isset($sex_list[1]) ? $sex_list[1]['num'] : 0;
        //会员男女统计比例结束

        return json(['user'=>$data,'sex'=>$sex,'article'=>$artice_data]);
    }
    /**
     * 用户列表
     */
    public function index()
    {
        // 设置页面标题
        $this->title = '系统用户管理';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = Db::name($this->table)->where('is_deleted',0);
        // 应用搜索条件
        foreach (['username', 'phone'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $db->where($key, 'like', "%{$get[$key]}%");
            }
        }
        // 实例化并显示
        return parent::_list($db);
    }

    /**
     * 授权管理
     * @return array|string
     */
    public function auth()
    {
        return $this->_form($this->table, 'auth');
    }

    /**
     * 用户添加
     */
    public function add()
    {

        return $this->_form($this->table,'form', '添加成功');
    }

    /**
     * 用户编辑
     */
    public function edit()
    {
        return $this->_form($this->table,'form', '编辑成功');
    }

    /**
     * 用户密码修改
     */
    public function pass()
    {
        if($this->request->isGet()){
            $this->success('抱歉, 您没有访问该模块的权限!', '');
        }else{
            $data = $this->request->post();
            if ($data['password'] !== $data['repassword']) {
                $this->error('两次输入的密码不一致！');
            }
            if (DataService::save($this->table, ['id' => $data['id'], 'password' => md5($data['password'])], 'id')) {
                $this->success('密码修改成功，下次请使用新密码登录！', '');
            }
            $this->error('密码修改失败，请稍候再试！');
        }
    }

    /**
     * 表单数据默认处理
     * @param array $data
     */
    public function _form_filter(&$data)
    {
        if ($this->request->isPost()) {
            if (isset($data['authorize']) && is_array($data['authorize'])) {
                $data['authorize'] = join(',', $data['authorize']);
            }
            if (isset($data['id'])) {
                unset($data['username']);
            } elseif (Db::name($this->table)->where('username', $data['username'])->find()) {
                $this->error('用户账号已经存在，请使用其它账号！');
            }
        } else {
            $data['authorize'] = explode(',', isset($data['authorize']) ? $data['authorize'] : '');
            $this->assign('authorizes', Db::name('SystemAuth')->select());
        }
    }

    /**
     * 删除用户
     */
    public function del()
    {
        if (in_array('10000', explode(',', $this->request->post('id')))) {
            $this->error('系统超级账号禁止删除！');
        }
        if (DataService::update($this->table)) {
            $this->success("用户删除成功！", 'user/index');
        }
        $this->error("用户删除失败，请稍候再试！");
    }

    /**
     * 用户禁用
     */
    public function setstatus()
    {
        if (in_array('10000', explode(',', $this->request->post('id')))) {
            $this->error('系统超级账号禁止操作！');
        }
        if (DataService::update($this->table)) {
            if(input('status') == 1){
                $this->success("用户启用成功！", '');
            }
            $this->success("用户禁用成功！", '');
        }
        $this->error("用户禁用失败，请稍候再试！");

    }


}
