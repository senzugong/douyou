<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 19:12
 */

namespace app\common\model;


use think\Model;

class BtcPost extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_btc_post';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'id';
}