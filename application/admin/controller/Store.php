<?php


namespace app\admin\controller;

use app\common\model\UsdtLog;
use app\common\model\User;
use controller\BasicAdmin;
use service\DataService;
use service\LogService;
use think\Db;
use think\Request;

/**
 * Class Store
 * 商户管理
 * @package app\admin\controller
 */
class Store extends BasicAdmin
{
    public $table = 'dw_users';

    /**
     * 入驻商户列表
     * @return array|string
     */
    public function index() {
        // 设置页面标题
        $this->title = '用户列表';
        // 获取到所有GET参数
        $get = $this->request->get();
        // 实例Query对象
        $db = User::alias('a')
            ->join('dw_users b', 'b.user_id = a.invite_user', 'left')
            ->field('a.*, b.true_name as invite_name')
            ->where('a.is_business != 0')
            ->order("a.is_business % 3 desc,a.add_time desc");
        // 应用搜索条件
        foreach (['true_name', 'user_phone', 'role_id'] as $key) {
            if (isset($get[$key]) && $get[$key] !== '') {
                $key == 'role_id' ? $db->where('a.'.$key, $get[$key]) :$db->where('a.'.$key, 'like', "%{$get[$key]}%");
            }
        }
        // 实例化并显示
        return parent::_list($db);
    }
    protected function _data_filter(&$data) {
        if($this->request->action() == "index"){
            foreach($data as $k=>&$v){
                $images = Db::table('dw_user_examine')->where(['user_id'=>$v['user_id']])->order('examine_id desc')->find();
                $data[$k]['add_time'] = date('Y-m-d H:m:s',$v['add_time']);
                $v['user_avatar'] = $v['user_avatar'] ? '/'.$v['user_avatar'] : '';
                $v['img1'] = $images['img1'] ? '/'.$images['img1'] : '';
                $v['img2'] = $images['img2'] ? '/'.$images['img2'] : '';
                $v['img3'] = $images['img3'] ? '/'.$images['img3'] : '';
                $v['invite_name'] = $v['invite_name'] ?: '';
            }
        }
    }

    /**编辑
     * @return array|string
     */
    public function edit(Request $request) {
        if ($this->request->isGet()) {
            return $this->success('成功');
        }
        if ($request->isPost()) {
            $business_msg = $request->post('business_msg', '');
            $user_id = $request->post('id');
            $result1 = Db::table('dw_users')->where(['user_id'=>$user_id])->find();
            Db::table('dw_user_examine')->where(['user_id'=>$user_id])->update(['status'=>2]);
            $result = Db::table('dw_users')->where(['user_id'=>$user_id])->update(['business_msg'=>$business_msg,'is_business'=>3,'business_usdt'=>0,'is_examine'=>3,'dw_usdt'=>bcadd($result1['dw_usdt'],$result1['business_usdt'],4)]);
            // 添加日志
            UsdtLog::create([
                'user_id'=>$user_id,
                'log_content'=> '商家认证失败返回USDT',
                'type'=> 2,
                'log_status'=>10,
                'chance_usdt'=>$result1['business_usdt'],
                'dw_usdt'=>bcadd($result1['dw_usdt'],$result1['business_usdt'],4),
                'add_time'=> time(),
            ]);
            if($result){
                $this->success('成功');
            }else{
                $this->error('失败');
            }
    }
    }
    /**
     * 设置审核状态
     */
    public function setstatus(Request $request)
    {
        if ($request->isPost()) {
            $status = $request->post('status');
            $user_id = $request->post('id');
            if($status == 1){//通过
                $result = Db::table('dw_users')->where(['user_id'=>$user_id])->update(['is_business'=>1,'is_examine'=>1]);
            }else{
                $result1 = Db::table('dw_users')->where(['user_id'=>$user_id])->find();
                Db::table('dw_user_examine')->where(['user_id'=>$user_id])->update(['status'=>2]);
                $result = Db::table('dw_users')->where(['user_id'=>$user_id])->update(['is_business'=>3,'business_usdt'=>0,'is_examine'=>3,'dw_usdt'=>bcadd($result1['dw_usdt'],$result1['business_usdt'],4)]);
                // 添加日志
                UsdtLog::create([
                    'user_id'=>$user_id,
                    'log_content'=> '商家认证失败返回USDT',
                    'type'=> 2,
                    'log_status'=>10,
                    'chance_usdt'=>$result1['business_usdt'],
                    'dw_usdt'=>bcadd($result1['dw_usdt'],$result1['business_usdt'],4),
                    'add_time'=> time(),
                ]);
            }
//            $time = $request->post('time',1);
            if($result){
                $this->success('成功');
            }else{
                $this->error('失败');
            }
        }
//        if (DataService::update($this->table)) {
//            if(input('status') == 1){
//                LogService::write('系统管理', '商家认证通过');
//                $this->success("商家认证通过！", 'store/index');
//            }
//            LogService::write('系统管理', '商家认证不通过');
//            $this->success("商家认证不通过！", 'store/index');
//        }
//        LogService::write('系统管理', '商家认证操作失败');
//        $this->error("商家认证操作失败，请稍候再试！");

    }
}