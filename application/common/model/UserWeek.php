<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 14:39
 */

namespace app\common\model;

use think\Model;

/**
 * Class UserWeek
 * 签到模型
 * @package app\common\model
 */
class UserWeek extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_user_week';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'id';
}