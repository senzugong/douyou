<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/15
 * Time: 11:25
 */

namespace app\common\library;

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
     * @param string $phone 手机号
     * @param string $msg 消息
     */
    public static function send($phone, $msg) {
        if (is_null(self::$instance)) {
            self::$instance = new Client(Config::get('jiguang.AppKey'), Config::get('jiguang.MasterSecret'), RUNTIME_PATH.'log/jpush.log');
        }
        try {
            // 推送消息
            self::$instance->push()
                ->setPlatform('all')
                ->addAlias($phone)
                ->setNotificationAlert($msg)
                ->send();
        } catch (JPushException $exception) {
            // 推送异常
        }
    }
}