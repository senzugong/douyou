<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19
 * Time: 14:00
 */

namespace app\common\model;
use think\Model;

class Sms extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_sms';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'id';

    public function scopePhone($query, $phone,$type)
    {
        $query->where(['phone'=>$phone,'sms_type'=>$type]);
    }

}