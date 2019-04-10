<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 11:21
 */

namespace app\api\controller;

use app\admin\controller\Config;
use controller\BasicApi;
use app\common\model\Message as MessageModel;
use think\Request;

/**
 * Class Message
 * 用户消息
 * @package app\api\controller
 */
class Message extends BasicApi
{
    /**
     * 消息列表
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(Request $request) {
        $userInfo = $request->userInfo;
        // 分页
        $page = $request->param('page', 1);
        // 消息通知
        $list = MessageModel::where(['user_id'=> $userInfo['user_id']])
            ->page($page)
            ->select();
        foreach ($list as &$val) {
            $val['image'] = $val['image'] ? Config::get('image_url').$val['image'] : '';
            $val['url'] = $val['url'] ? Config::get('image_url').$val['url'] : '';
        }
        return $this->response($list);
    }

    /**
     * 删除消息
     * @param Request $request
     * @return \think\Response
     */
    public function del(Request $request) {
        $userInfo = $request->userInfo;
        $msgId = $request->param('msg_id');
        if ($msgId) {
            // 根据ID删除记录
            MessageModel::destroy($msgId);
        } else {
            // 删除全部
            MessageModel::where(['user_id'=> $userInfo['user_id']])->delete();
        }
        return $this->response();
    }
}