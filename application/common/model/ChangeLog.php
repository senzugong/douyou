<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/22
 * Time: 10:00
 */

namespace app\common\model;

use think\Model;

/**
 * Class ErrorNum
 * 后台的钱包地址
 * @package app\common\model
 */
class ChangeLog extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_changelog';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'log_id';

    /**
     * 订单用户
     * @return \think\model\relation\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}