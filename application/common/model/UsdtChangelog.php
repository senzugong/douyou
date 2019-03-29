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
 * usdt充值提现模型
 * @package app\common\model
 */
class UsdtChangelog extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_usdt_changelog';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'changelog_id';

    /**
     * 订单用户
     * @return \think\model\relation\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}