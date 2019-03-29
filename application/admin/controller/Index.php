<?php

namespace app\admin\controller;

use app\common\model\UserProposal;
use controller\BasicAdmin;
use service\DataService;
use service\NodeService;
use service\ToolsService;
use think\Db;
use think\Request;
use think\View;
use app\common\model\User;

/**
 * 后台入口
 * Class Index
 * @package app\admin\controller
 * @author sucaihuo <416148489@qq.com>
 * @date 2017/02/15 10:41
 */
class Index extends BasicAdmin
{

    /**
     * 后台框架布局
     * @return View
     */
    public function index()
    {
        NodeService::applyAuthNode();
        $list = Db::name('SystemMenu')->where('status', '1')->order('sort asc,id asc')->field('id,title,icon,url,pid')->select();
        $menus= $this->_filterMenu(ToolsService::arr2tree($list));
        if (Request::instance()->isAjax()){
           $data['data']['list']=$menus;
           $data['status']=200;
           return json($data);
        }
        return view('', ['title' => '系统管理', 'menus' => $menus]);
    }

    /**
     * 后台主菜单权限过滤
     * @param array $menus
     * @return array
     */
    private function _filterMenu($menus)
    {
        foreach ($menus as $key => &$menu) {
            if (!empty($menu['sub'])) {
                $menu['sub'] = $this->_filterMenu($menu['sub']);
            }
            if (!empty($menu['sub'])) {
                $menu['url'] = '#';
            } elseif (stripos($menu['url'], 'http') === 0) {
                continue;
            } elseif ($menu['url'] !== '#' && auth(join('/', array_slice(explode('/', $menu['url']), 0, 3)))) {
                $menu['url'] = url($menu['url']);
            } else {
                unset($menus[$key]);
            }
        }
        return $menus;
    }

    /**
     * 主机信息显示
     * @return View
     */
    public function main()
    {
        /*if (session('user.username') === 'admin' && session('user.password') === '21232f297a57a5a743894a0e4a801fc3') {
            $url = url('admin/index/pass') . '?id=' . session('user.id');
            $alert = ['type' => 'danger', 'title' => '安全提示', 'content' => "超级管理员默认密码未修改，建议马上<a href='javascript:void(0)' data-modal='{$url}'>修改</a>！",];
            $this->assign('alert', $alert);
            $this->assign('title', '后台首页');
        }
        $_version = Db::query('select version() as ver');
        $version = array_pop($_version);
        return view('', ['mysql_ver' => $version['ver']]);*/

        $user_count= User::count();//用户总数
        $now_user_count= User::whereTime('add_time', 'd')->count();//今日注册用户数


        $article_count=User::where(['is_examine'=> 1])->count();// 已高级认证
        $now_article_count=User::where(['is_examine'=> 2])->count();//待审核高级认证
        //
        $comment_count= UserProposal::count();//反馈总数
        $now_comment_count= UserProposal::whereTime('add_time', 'd')->count();//今日反馈数
        $this->assign([
           'usercount'=>$user_count,
            'now_user_count'=>$now_user_count,
            'article_count'=>$article_count,
            'now_article_count'=>$now_article_count,
            'comment_count'=>$comment_count,
            'now_comment_count'=>$now_comment_count
        ]);
        return view();
    }

    /**
     * 修改密码
     */
    public function pass()
    {
        if (intval($this->request->request('id')) !== intval(session('user.id'))) {
            $this->error('访问异常！');
        }
        if ($this->request->isGet()) {
            $this->assign('verify', true);
            return $this->_form('SystemUser', 'user/pass');
        } else {
            $data = $this->request->post();
            if ($data['password'] !== $data['repassword']) {
                $this->error('两次输入的密码不一致，请重新输入！');
            }
            $user = Db::name('SystemUser')->where('id', session('user.id'))->find();
            if (md5($data['oldpassword']) !== $user['password']) {
                $this->error('旧密码验证失败，请重新输入！');
            }
            if (DataService::save('SystemUser', ['id' => session('user.id'), 'password' => md5($data['password'])])) {
                $this->success('密码修改成功，下次请使用新密码登录！', '');
            } else {
                $this->error('密码修改失败，请稍候再试！');
            }
        }
    }

    /**
     * 修改资料
     */
    public function info()
    {
        if (intval($this->request->request('id')) === intval(session('user.id'))) {
            return $this->_form('SystemUser', 'user/form');
        }
        $this->error('访问异常！');
    }

}
