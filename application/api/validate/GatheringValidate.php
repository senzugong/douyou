<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 19:47
 */

namespace app\api\validate;

use app\common\model\UserBank;
use think\Validate;

/**
 * Class GatheringValidate
 * 微信 支付宝验证
 * @package app\api\validate
 */
class GatheringValidate extends Validate
{
    protected $rule = [
        ['type_id','require|between:1,3'],
        ['bank_number','require|checkBank'],
        ['bank_name','require'],
        ['bank_branch','require'],
        ['gathering_name','require'],
        ['gathering_img','require'],
        ['payment_id','require'],
    ];
    /**
     * @var array 自定义提示
     */
    protected $message = [
        'type_id'=> '未填或者输入错误!',
        'bank_number.require'=> '银行卡号必填!',
        'bank_name.require'=> '银行名字必填!',
        'bank_branch.require'=> '银行支行必填!',
        'gathering_name.require'=> '账号不能为空!',
        'gathering_img.require'=> '收款码不能为空!',
        'payment_id.require'=> '支付的id不能为空!',
    ];
    protected function checkBank($value){
        $userInfo = request()->userInfo;
        $result = UserBank::where(['user_id'=>$userInfo['user_id'],'bank_number'=>$value])->find();
        if($result){
            return '改银行卡已存在!';
        }
        $arr_no = str_split($value);
        $last_n = $arr_no[count($arr_no)-1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n){
            if($i%2==0){
                $ix = $n*2;
                if($ix>=10){
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                }else{
                    $total += $ix;
                }
            }else{
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $x = 10 - ($total % 10);
        if($x == $last_n){
            return true;
        }else{
            return '请检查输入的银行卡号!';
        }
    }
    /**
     * @var array 应用场景
     */
    protected $scene = [
        'bank'  =>  ['bank_number','type_id'=>'require|eq:1','bank_name','bank_branch'],
        'wx_gather'  =>  ['gathering_name','type_id'=>'require|between:2,3','gathering_img'],
        'default' => ['type_id','payment_id']
    ];
}