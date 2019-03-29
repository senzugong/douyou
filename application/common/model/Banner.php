<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/29
 * Time: 10:36
 */

namespace app\common\model;


use think\Model;

class Banner extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_banner';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'banner_id';
}