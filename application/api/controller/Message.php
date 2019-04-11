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
            ->order('id desc')
            ->page($page)
            ->select();
        foreach ($list as &$val) {
            $val['image'] = $val['image'] ? Config::get('image_url').$val['image'] : '';
            $val['url'] = $val['url'] ? Config::get('image_url').$val['url'] : '';
            $val['add_time'] = $this->formatTime($val['add_time']);
        }
        $data = [
            'list'=> $list,
            'total'=> MessageModel::where(['user_id'=> $userInfo['user_id']])->count(),
        ];
        return $this->response($data);
    }

    /**
     * 格式化时间
     * @param $time
     * @return false|string
     */
    protected function formatTime($time) {
        if (!$time) {
            return date('Y年m月d日 H:i', $time);
        }
        $dateTime = new \DateTime('@'.$time);
        $dateTime->modify('-3 day');
        $dateTime->setTime(0,0,0);
        // 是否是今天
        if ($time >= strtotime(date('Y-m-d 00:00:00'))) {
            $date = '今天 ' . date('H:i', $time);
        } elseif ($time >= $dateTime->getTimestamp()) {
            // 三天内
            $week = ['日', '一', '二', '三', '四', '五', '六'];
            $date = '星期' . $week[date("w", $time)] . ' ' . date('H:i', $time);
        } else {
            $date = date('Y年m月d日 H:i', $time);
        }
        return $date;
    }

    /**
     * 已读消息
     * @param Request $request
     * @return \think\Response
     */
    public function read(Request $request) {
        $msg_id = $request->param('msg_id');
        MessageModel::where(['id'=> $msg_id])->update(['is_read'=> 1]);
        return $this->response();
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