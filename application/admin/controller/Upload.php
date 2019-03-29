<?php

namespace app\admin\controller;
use controller\BasicAdmin;
use think\File;
use think\Request;
class Upload extends BasicAdmin
{
    /*
     * 服务项目图片
     */
    public function uploadArticleImage(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'static' . DS . 'upload/article');
        if($info){
            $res['status']=200;
            $res['image_name']='/static' . DS . 'upload/article/'.$info->getSaveName();
            return json($res);

        }else{
            $res['status']=0;
            $res['error_info']=$file->getError();
            return json($res);
        }
    }
    /*
    * 上传案例图片
    */
    public function uploadCaseImage(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload/image/case');
        if($info){
            $res['status']=1;
            $res['image_name']=$info->getSaveName();
            return json($res);

        }else{
            $res['status']=0;
            $res['error_info']=$file->getError();
            return json($res);
        }
    }
    /*
     * 上传轮播图片
     */
    public function uploadAdImage(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload/image/shuff');
        if($info){
            $res['status']=1;
            $res['image_name']=$info->getSaveName();
            return json($res);

        }else{
            $res['status']=0;
            $res['error_info']=$file->getError();
            return json($res);
        }
    }
    public function uploadTeamImage(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload/image/team');
        if($info){
            $res['status']=1;
            $res['image_name']=$info->getSaveName();
            return json($res);

        }else{
            $res['status']=0;
            $res['error_info']=$file->getError();
            return json($res);
        }
    }

    /*
     * 编辑器图片上传接口
     */
    public function uploadImage(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload/editor');
        if($info){
            $res['src']='/public/upload/editor/'.$info->getSaveName();
            $result=array('code'=>0,'data'=>$res,'msg'=>'上传成功');

            return json($result);

        }else{

            $result=array('code'=>-1,'data'=>'','msg'=>$file->getError());

            return json($result);
        }
    }
	//文章图片上传
    public function upload(){
       $file = request()->file('file');
       $info = $file->move(ROOT_PATH . 'public' . DS . 'upload/image/article');
       if($info){
           $res['status']=1;
           $res['image_name']=$info->getSaveName();
           return json($res);
        }else{
           $res['status']=0;
           $res['error_info']=$file->getError();
           return json($res);
        }
    }
    //网站logo上传
    public function upload_logo(){
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload/image');
        if($info){
            $res['status']=1;
            $res['image_name']=$info->getSaveName();
            return json($res);
        }else{
            $res['status']=0;
            $res['error_info']=$file->getError();
            return json($res);
        }
    }

}