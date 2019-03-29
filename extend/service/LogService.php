<?php


namespace service;

use think\Db;
use think\Request;

/**
 * 操作日志服务
 * Class LogService
 * @package service
 * @author jonny <980218641@qq.com>
 * @date 2017/03/24 13:25
 */
class LogService
{

    /**
     * 获取数据操作对象
     * @return \think\db\Query
     */
    protected static function db()
    {
        return Db::name('SystemLog');
    }

    /**
     * 写入操作日志
     * @param string $action
     * @param string $content
     * @return bool
     */
    public static function write($action = '行为', $content = "内容描述")
    {
        $request = Request::instance();
        $node = strtolower(join('/', [$request->module(), $request->controller(), $request->action()]));
        $data = ['ip' => $request->ip(), 'node' => $node, 'username' => session('user.username') . '', 'action' => $action, 'content' => $content];
        return self::db()->insert($data) !== false;
    }

    public static function write1( $user_id , $content = "内容描述")
    {
        $request = Request::instance();
        $data = ['ip_address' => $request->ip(0,true),  'user_id' => $user_id, 'log_info' => $content,'add_time'=>time()];
        $result =Db::name('rm_user_action_log')->insert($data);
    }
    public static function write2( $user_id,$type,$exchange_user_id,$rmz_num,$rmz_count,$status,$note='')
    {

//        $request = Request::instance();
        $data = ['user_id' => $user_id,  'type' => $type, 'exchange_user_id' => $exchange_user_id,'rmz_num'=>$rmz_num,'rmz_count'=>$rmz_count, 'note'=>$note,'status'=>$status,'add_time'=>time()];
        $result =Db::name('rm_user_rmz_log')->insert($data);
    }
}
