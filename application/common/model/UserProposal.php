<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 19:42
 */

namespace app\common\model;

use think\Model;

/**
 * Class UserProposal
 * 反馈模型
 * @package app\common\model
 */
class UserProposal extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_user_proposal';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'id';
}