<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 15:51
 */

namespace app\common\model;

use think\Model;

/**
 * Class TurntableSet
 * 转盘设置
 * @package app\common\model
 */
class TurntableSet extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_turntable_set';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'id';
}