<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 19:47
 */

namespace app\api\validate;

use think\Validate;

/**
 * Class UserSuggest
 * 反馈验证
 * @package app\api\validate
 */
class UserSuggest extends Validate
{
    protected $rule = [
        'proposal_type'=> 'require',
        'proposal_content'=> 'require',
        'images'=> 'array|max:3',
    ];
    /**
     * @var array 自定义提示
     */
    protected $message = [
        'proposal_type'=> '选择反馈类型',
        'proposal_content'=> '输入反馈信息',
        'images'=> '图片上传错误',
    ];
}