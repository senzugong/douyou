<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 15:29
 */

namespace app\common\model;


use think\Model;

class UsdtMall extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_usdt_mall';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'mall_id';
}