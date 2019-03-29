<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 14:37
 */

namespace app\common\model;

use think\Model;

/**
 * Class User
 * 用户模型类
 * @package app\common\model
 */
class User extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_users';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'user_id';

    /**
     * 获取用户钱包列表
     * @return \think\model\relation\HasMany
     */
    public function userWallet() {
        return $this->hasMany(UserWallet::class);
    }

    /**
     * 获取用户账单
     * @return \think\model\relation\HasMany
     */
    public function userMoneyLog() {
        return $this->hasMany(MoneyLog::class);
    }

    /**
     * 获取用户购买号码竞猜记录
     * @return \think\model\relation\HasMany
     */
    public function UserGameByHistory() {
        return $this->hasMany(GamePostdata::class);
    }

    /**
     * 用户转动抽奖转盘记录
     * @return \think\model\relation\HasMany
     */
    public function turntableLog() {
        return $this->hasMany(TurntableLog::class);
    }
    /**
     * 用户银行
     * @return \think\model\relation\HasMany
     */
    public function userBank() {
        return $this->hasMany(UserBank::class);
    }
    /**
     * 用户的微信支付宝
     * @return \think\model\relation\HasMany
     */
    public function userGathering() {
        return $this->hasMany(UserGathering::class);
    }
    /**
     * 支付错误次数
     * @return \think\model\relation\HasOne
     */
    public function payError() {
        return $this->hasOne(ErrorNum::class);
    }

    /**
     * 用户签到
     * @return \think\model\relation\HasMany
     */
    public function userWeek() {
        return $this->hasMany(UserWeek::class);
    }

    /**
     * 用户高级认证信息
     * @return \think\model\relation\HasMany
     */
    public function userExamine() {
        return $this->hasMany(Examine::class);
    }

    /**
     * 用户发布的场外挂单
     * @return \think\model\relation\HasMany
     */
    public function usdtMall() {
        return $this->hasMany(UsdtMall::class);
    }
}