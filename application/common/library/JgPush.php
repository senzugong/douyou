<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 11:25
 */

namespace app\common\library;

use app\common\model\Message;
use app\common\model\User;
use JPush\Client;
use JPush\Exceptions\JPushException;
use think\Config;

/**
 * Class JgPush
 * 极光推送
 * @package app\common\library
 */
class JgPush
{
    /**
     * @var Client 对象实例
     */
    protected static $instance;

    /**
     * 推送消息到App通知栏
     * @param string $user 用户id
     * @param string $msg 消息
     * @throws \think\exception\DbException
     */
    public static function send($user, $msg) {
        if (is_null(self::$instance)) {
            self::$instance = new Client(Config::get('jiguang.AppKey'), Config::get('jiguang.MasterSecret'), RUNTIME_PATH.'log/jpush.log');
        }
        try {
            $userInfo = User::get($user);
            // 未读条数
            $count = Message::where(['user_id'=> $user, 'is_read'=> 0])->count();
            // 推送消息
            self::$instance->push()
                ->setPlatform('all')
                ->addAlias($userInfo['user_phone'])
                ->setNotificationAlert($msg)
                ->iosNotification($msg, [
                    'sound' => 'default',
                    'badge' => $count,
                ])
                ->options(['apns_production'=> true]) // 设置生产环境
                ->send();
        } catch (JPushException $exception) {
            // 推送异常
        }
    }
}