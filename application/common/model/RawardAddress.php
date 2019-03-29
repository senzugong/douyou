<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 16:55
 */

namespace app\common\model;


use think\Model;

class RawardAddress extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_raward_address';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'address_id';
}