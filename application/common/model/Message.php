<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 19:11
 */

namespace app\common\model;

use think\Model;

/**
 * Class Message
 * 消息模型
 * @package app\common\model
 */
class Message extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_message';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'id';
}