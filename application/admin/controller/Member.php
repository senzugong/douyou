<?php
/**
 * 文件描述
 * Create on : 2017/8/11 10:04
 * Autor email :416148489@qq.com
 * Create by sucaihuo
 */

namespace app\admin\controller;

use app\common\model\UsdtChangelog;
use app\common\model\UsdtMall;
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
        $db = User::alias('a')
            ->join('dw_users b', 'b.user_id = a.invite_user', 'left')
            ->field('a.*, b.true_name as invite_name')
            ->order('a.add_time', 'desc');
        // 应用搜索条件
        foreach (['true_name', 'user_phone', 'role_id'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $key == 'role_id' ? $db->where('a.'.$key, $get[$key]) :$db->where('a.'.$key, 'like', "%{$get[$key]}%");
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
            ->field('a.user_id, a.user_name, a.true_name, a.card_num, a.is_examine, b.img1, b.img2, b.img3, b.status, b.examine_id')
            ->order('b.examine_id desc');
        if ($this->request->isPost()) {
            $userExamine = $sql->find();
            Db::startTrans();
            try {
                Db::table('dw_user_examine')->where(['examine_id'=> $userExamine['examine_id']])
                    ->update([
                        'b.status'=> $this->request->post('status'),
                    ]);
                Db::table('dw_users')
                    ->where(['user_id'=> $this->request->post('user_id')])
                    ->update([
                        'is_examine'=> $this->request->post('status') == 1 ? 1 : 3,
                    ]);
                Db::table('dw_users')
                    ->where(['user_id'=> $this->request->post('user_id')])
                    ->setInc('complete_rate',10);
                // 提交
                Db::commit();
                $result = true;
            } catch (\Exception $exception) {
                // 回滚
                Db::rollback();
                $result = false;
            }
            if ($result !== false) {
                $this->success('审核成功', 'member/index');
            } else {
                $this->error('数据保存失败, 请稍候再试!');
            }
        }
        return $this->_form($sql, '', '审核成功','member/index','a.user_id', ['a.user_id'=> $this->request->get('user_id')]);
    }

    /**
     * 用户编辑
     */
    public function edit()
    {
        // 分组
        $group = Db::table('dw_group')->where(['status'=> 1])->select();
        $this->assign('group', $group);
        // 用户身份认证
        $examine = Db::table('dw_user_examine')
            ->where(['user_id'=> $this->request->param('user_id'), 'status'=> 1])
            ->find();
        $this->assign('examine', $examine);
        return $this->_form($this->table,'form', '编辑成功','member/index','user_id');
    }

    protected function _data_filter(&$data) {
        if($this->request->action() == "index"){
            foreach($data as $k=>&$v){
                $data[$k]['add_time'] = date('Y-m-d H:m:s',$v['add_time']);
                $v['user_avatar'] = $v['user_avatar'] ? '/'.$v['user_avatar'] : '';
                $v['invite_name'] = $v['invite_name'] ?: '';

                // 冻结资金
                $sell_freeze = '0.00';
                $usdtMall = UsdtMall::where(['user_id'=> $v['user_id']])
                    ->whereIn('status', [0, 1])
                    ->select();
                foreach ($usdtMall as $value) {
                    $sell_freeze = bcadd($sell_freeze, bcsub($value['usdt_num'], $value['over_usdt'], 2), 2);
                }
                $v['sell_freeze'] = $sell_freeze;
                $fetch_freeze = '0.00';
                $usdtChange = UsdtChangelog::where(['user_id'=> $v['user_id'], 'type'=> 2, 'status'=> 0])
                    ->select();
                foreach ($usdtChange as $value) {
                    $fetch_freeze = bcadd($fetch_freeze, $value['usdt_num'], 2);
                }
                $v['fetch_freeze'] = $fetch_freeze;
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