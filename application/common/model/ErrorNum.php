<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 10:00
 */

namespace app\common\model;

use think\Model;

/**
 * Class ErrorNum
 * 支付错误次数
 * @package app\common\model
 */
class ErrorNum extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_error_num';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'id';
}