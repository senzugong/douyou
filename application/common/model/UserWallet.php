<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19
 * Time: 9:38
 */

namespace app\common\model;

use think\Model;

/**
 * Class UserWallet
 * 用户钱包模型
 * @package app\common\model
 */
class UserWallet extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_user_wallet';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'wallet_id';

    /**
     * 钱包类型
     * @return \think\model\relation\BelongsTo
     */
    public function walletType() {
        return $this->belongsTo(WalletType::class);
    }
}