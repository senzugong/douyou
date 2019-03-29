<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 11:10
 */

namespace app\common\model;

use think\Model;

/**
 * Class RawardSet
 * 周末奖励设置模型
 * @package app\common\model
 */
class RawardSet extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_raward_set';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'id';
}