<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19
 * Time: 9:23
 */

namespace app\common\model;

use think\Model;

/**
 * Class Token
 * 用户登录token模型
 * @package app\common\model
 */
class Token extends Model
{
    /**
     * @var string 自定义表名（默认是类名当表名）
     */
    protected $table = 'dw_token';
    /**
     * @var string 自定义主键（默认是id）
     */
    protected $pk = 'id';

    /**
     * 获取用户信息
     * @return \think\model\relation\BelongsTo
     */
    protected function userInfo() {
        return $this->belongsTo(User::class);
    }

    public function scopeCheckToken($query, $user_id)
    {
        $query -> where(['user_id'=>$user_id]);
    }
}