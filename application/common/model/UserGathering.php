<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19
 * Time: 15:02
 */

namespace app\common\model;

use think\Model;

/**
 * Class GamePostdata
 * 微信支付宝收款码模型
 * @package app\common\model
 */
class UserGathering extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_user_gathering';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'gathering_id';
}