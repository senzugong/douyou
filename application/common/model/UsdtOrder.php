<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 15:30
 */

namespace app\common\model;


use think\Model;

class UsdtOrder extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_usdt_order';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'order_id';

    /**
     * 获取对应的挂单
     * @return \think\model\relation\BelongsTo
     */
    public function usdtMall() {
        return $this->belongsTo(UsdtMall::class, 'mall_id');
    }

    /**
     * 下单用户
     * @return \think\model\relation\BelongsTo
     */
    public function orderUser() {
        return $this->belongsTo(User::class);
    }

    /**
     * 挂单用户
     * @return \think\model\relation\BelongsTo
     */
    public function mallUser() {
        return $this->belongsTo(User::class, 'mall_user_id');
    }
}