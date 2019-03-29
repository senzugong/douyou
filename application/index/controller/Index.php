<?php

namespace app\index\controller;

use service\HttpService;
use service\ToolsService;
use think\Controller;
use think\Db;
use think\Request;
use Wechat\Lib\Tools;

/**
 * 网站入口控制器
 * Class Index
 * @package app\index\controller
 * @author sucaihuo <416148489@qq.com>
 * @date 2017/04/05 10:38
 */
class Index extends Controller
{

    /**
     * 网站入口
     */
    public function index()
    {
        $this->redirect('@admin');
    }

    public function test()
    {
        $json = json_decode(file_get_contents('citys.json'), true);
//        dump($json);
        foreach ($json as $key => $vo) {
            dump(Db::name('DataRegion')->insert(['code' => $key, 'name' => $vo]));
        }
    }

    public function wuliu()
    {
        $order = '444500528707';
        dump(ToolsService::express($order));
    }

}
