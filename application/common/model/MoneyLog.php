<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19
 * Time: 9:44
 */

namespace app\common\model;

use think\Model;

/**
 * Class MoneyLog
 * 用户账号动态日志模型
 * @package app\common\model
 */
class MoneyLog extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_money_log';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'log_id';

}