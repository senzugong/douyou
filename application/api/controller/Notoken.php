<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 9:39
 */

namespace app\api\controller;

use app\common\library\Curl;
use app\common\model\Banner;
use app\common\model\UsdtLog;
use think\Cache;
use think\Db;
use think\Request;
use think\Config;
use controller\BasicApi;

class Notoken extends BasicApi
{
    /**
     * 每秒请求的btc值
     * @param Request $request
     * @return \think\Response
     */
    public function btc_now(Request $request){
        $btc_now = Db::table('dw_btc_now')->where(['id'=>2])->value('btc_now');
        $data = json_decode($btc_now,true);
        $type =rand(1,2);
        $num = rand(1,6);
        if($type ==1){
            $data['ticker']['sell'] = bcsub($data['ticker']['sell'],bcdiv($num,100,4),4);
        }else{
            $data['ticker']['sell'] = bcadd($data['ticker']['sell'],bcdiv($num,100,4),4);
        }
//        $number = rand(10,22);
//        $number = bcdiv($number,100,2);
//        $data['ticker']['sell'] = $data['ticker']['sell'],$number,2);
        if(!$data){
            // url地址
            $url = 'http://api.zb.cn/data/v1/ticker?market=btc_usdt';
            // 获取数据
            $data = Curl::get($url);
        }
        return $this->response($data);
    }
    /**
     * @param Request $request 首页的btc行情展示
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(Request $request){
//        $url = "http://api.zb.cn/data/v1/ticker?market=btc_usdt";
//        $data = Curl::get($url);
        $data = Db::table('dw_btc_now')->where(['id'=>2])->value('btc_now');
//        var_dump($data);die;
        $price_now1 = json_decode($data,true);//当前的btc价格（出售）
        $price_now = $price_now1['ticker']['sell'];
        $btc_price = Db::table('dw_btc')->order('btc_id desc')->value('btc_price');//数据库中的btc价格
        $list['recharge'] =  bcmul(bcdiv(bcsub($price_now,$btc_price,4),$price_now,4),100,2);
        if(!$list['recharge'] || !$price_now){
            $list['recharge'] = 0;
        }
        //累计竞猜人数
        $list['user_count'] =   Db::table('dw_btc_order')->count();
        if(!$list['user_count']){
            $list['user_count'] = 128;
        }else{
            $list['user_count'] = 128 +  $list['user_count'];
        }
        $list['btc_bouns'] = Db::table('dw_btc_bonus')->value('bonus');
        $list['guess'] = Db::table('dw_usdt_log')->alias('a')
            ->join('dw_users b','a.user_id = b.user_id')
            ->where("a.type=2 and a.log_status IN (3,4,5,6)")
            ->field('a.chance_usdt,a.log_status,b.user_name,b.user_phone')
            ->limit(20)->order('a.log_id desc')->select();
        foreach ($list['guess'] as &$v){
            if(!$v['user_name']){
                    $v['user_name'] = substr_replace($v['user_phone'] , '****', 3, 4);
            }
        }
        return $this->response($list);

    }

    /**
     * k线图的获取
     * @param Request $request
     * @return \think\Response
     */
    public function k_line(Request $request){
        $type = $request->post('type',1);
        $kname =  Db::table('dw_btc_klind')->where(['kid'=>$type])->value('kname');
        $data = Db::table('dw_btc_klind')->where(['kid'=>$type])->value('kdata');
        if(!$data){
            $url = "http://api.zb.cn/data/v1/kline?market=btc_usdt&type=$kname&size=100";
            // 获取数据
            $data = Curl::get($url);
        }
        return $this->response(json_decode($data));
    }
    /**首页banner
     * @param Request $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index_banner(Request $request){
        $banner_list['index'] = Banner::where("status = 0 and type IN (1,2)")->select();
        $banner_list['icon'] = Banner::where("status = 0 and type = 3")->select();
        foreach($banner_list['index'] as &$v){
            $v['img_url'] = $v['img_url'] ? Config::get('image_url').$v['img_url'] : '';
        }
        foreach($banner_list['icon'] as &$v){
            $v['img_url'] = $v['img_url'] ? Config::get('image_url').$v['img_url'] : '';
        }
        return $this->response($banner_list);
    }
}