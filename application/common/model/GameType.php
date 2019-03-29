<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19
 * Time: 10:07
 */

namespace app\common\model;

use think\Model;

/**
 * Class GameResult
 * 号码竞猜类型模型
 * @package app\common\model
 */
class GameType extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_game_type';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'type_id';
}