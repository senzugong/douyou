<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 16:11
 */

namespace app\common\model;

use think\Model;

/**
 * Class WalletType
 * 钱包类型模型
 * @package app\common\model
 */
class WalletType extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_wallet_type';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'id';
}