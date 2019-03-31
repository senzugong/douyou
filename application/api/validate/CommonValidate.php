<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 15:09
 */

namespace app\api\validate;

use app\common\model\ErrorNum;

/**
 * Class CommonValidate
 * 公共验证
 * @package app\api\validate
 */
class CommonValidate
{
    /**
     * 调用公共的验证
     * @param string $name 验证方法
     * @param mixed $arv 方法的参数
     * @return bool|string
     */
    public static function validate($name, ...$arv) {
        $check = new self();
        if (method_exists($check, $name)) {
            return $check->$name(...$arv);
        } else {
            return $name.'验证方法不存在';
        }
    }

    /**
     * 验证支付密码
     * @param $value
     * @return bool|string
     * @throws \think\Exception
     */
    public function payPassword($value = null) {
        if ($value === null || empty($value)) return '验证器缺少参数';
        // 用户信息
        $userInfo = request()->userInfo;
        // 密码错误封禁时间
        $sealTime = 30 * 60;
        // 错误次数
        $payError = $userInfo->payError;
        if (!$payError) {
            $payError = ErrorNum::create([
                'user_id'=>$userInfo->user_id,
                'add_time'=>time(),
            ]);
        }
        if(!$userInfo['pay_password']){
            return '先设置支付密码';
        }elseif(strlen($value) != 6){
            return '支付长度只能为6位!';
        }elseif ($payError->pay_error_num >= 5 && ($payError->add_time + $sealTime) > time()) {
            return '输入错误超过5次，支付冻结30分钟，请验证身份进行解封';
        }  elseif (md5(md5($value)) !== $userInfo->pay_password) {
            // 记录次数
            if ($payError->pay_error_num >= 5) {
                // 重置
                $payError->save(['pay_error_num'=> 1, 'add_time'=>time()]);
            } else {
                $payError->save(['pay_error_num'=> $payError->pay_error_num + 1, 'add_time'=>time()]);
            }
            return '支付密码不正确，您还可输入' . (5 - $payError->pay_error_num) . '次';
        }  else {
            $payError->save(['pay_error_num'=> 0, 'add_time'=>time()]);
            return true;
        }
    }
}