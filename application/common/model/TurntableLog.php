<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 15:37
 */

namespace app\common\model;

use think\Model;

/**
 * Class TurntableLog
 * 转盘中奖记录
 * @package app\common\model
 */
class TurntableLog extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_turntable_log';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'win_id';
}