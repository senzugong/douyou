<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 10:28
 */

namespace app\api\controller;
use app\common\library\Curl;
use controller\BasicApi;
use think\Config;
use think\Db;
use think\Request;

/**
 * Class Wave
 * 猜涨跌控制器
 * @package app\api\controller
 */
class Wave extends BasicApi
{
    /**下注信息
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
   public function basic_usdt(Request $request){
     $type = $request->post('type',1);//实时  2点位
     $list['usdt'] = Db::table('dw_basic_usdt')->where(['type'=>$type])->field('id,usdt_num')->select();
     if($type==1){
         $list['time'] = Db::table('dw_basic_time')->select();
     }else{
         $list['quiz'] = Db::table('dw_basic_quiz')->field('id,quiz_num')->select();
         $list['point'] = Db::table('dw_basic_point')->select();
     }
     $list['recharge'] = 2;
     $list['btc_price'] = Db::table('dw_btc')->order('btc_id desc')->value('btc_price');
       return $this->response($list);
   }

    /**btc的k线图
     * @param Request $request
     * @return \think\Response
     */
   public function btc_list(Request $request){
       $type = $request->post('type','1min');
       $size = $request->post('size','1000');
       $url = "http://api.zb.cn/data/v1/kline?market=btc_usdt&type=$type&size=$size";
       // 获取数据
       $data = Curl::get($url);
       return $this->response($data);
   }

    /**
     * 每秒请求的btc值
     * @param Request $request
     * @return \think\Response
     */
   public function btc_now(Request $request){
       $url = "http://api.zb.cn/data/v1/ticker?market=btc_usdt";
       // 获取数据
       $data = Curl::get($url);
       return $this->response($data);
   }


    /**
     * 返回猜涨跌规则
     * @return \think\Response
     */
    public function rule() {
        $result = <<<EOF
 <div class="rule-box"><div class="rule-tile padding-bottom-20"><span>参与资格</span></div><div class="rule-text padding-bottom-23">活动仅限蚪游账户用户参加。</div><div class="rule-tile padding-bottom-20"><span>活动时间</span></div><div class="rule-text padding-bottom-23">2019年3月1日至2019年3月31</div><div class="rule-tile padding-bottom-20"><span>活动规则</span></div><div class="rule-text color-active padding-bottom-5">1. 参与规则</div><div class="rule-text padding-bottom-30">活动期间，BTC涨跌的竞猜只能竞猜下一期涨跌，如您在40期内，只能投41期竞猜，以此类推。每次竞猜<span class="color-active">最低使用20蚪金，最高使用200蚪金，</span>每次做完竞猜后不可修改，蚪金使用后无法退还。</div><div class="rule-text color-active padding-bottom-5">2. 开奖规则</div><div class="rule-text padding-bottom-30">在当前竞猜期数结束时的3分钟左右，竞猜结果揭晓。若竞猜成功，则按参与蚪金所在竞猜方的蚪金池的比列，瓜分当期所有竞猜蚪金。所获蚪金一般在当期竞猜结果揭晓后立即到账。</div><div class="rule-text color-active padding-bottom-5">3. 特别须知</div><div class="rule-text padding-bottom-30">1) 禁止利用竞猜活动进行赌博活动等违法违规行为，若在活动前后发现违法违规行为，不仅限于冒用欺诈、赌博、传播色情、冒用他人身份等用户，蚪游有权取消活动参与资格及领奖资格。</div><div class="rule-text padding-bottom-30">2) 请避免违规刷蚪金的行为（包括但不限于虚假交易、恶意拆单、退款），如发现刷蚪金行为，蚪游有权取消活动参与资格及领奖资格。</div><div class="rule-text">3) 大盘不涨不跌视为上涨。</div></div>
EOF;
        return $this->response($result);
    }
}