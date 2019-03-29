<?php


namespace controller;
use app\api\event\Qrcode;
use app\common\model\Token;
use app\common\model\User;
use service\ToolsService;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use think\Cache;
use think\Request;
use think\Response;
use think\Db;
use Rsa;
use think\Validate;
/**
 * 数据接口通用控制器
 * Class BasicApi
 * @package controller
 */
class BasicApi
{

    /**
     * 访问请求对象
     * @var Request
     */
    public $request;

    /**
     * 当前访问身份
     * @var string
     */
    public $token;

    /**
     * 基础接口SDK
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        // CORS 跨域 Options 检测响应
        ToolsService::corsOptionsHandler();
        // 获取当前 Request 对象
        $this->request = is_null($request) ? Request::instance() : $request;
        // 安全方法请求过滤
        if (in_array(strtolower($this->request->action()), ['response', 'setcache', 'getcache', 'delcache', '_empty'])) {
            exit($this->response('禁止访问接口安全方法！', 403)->send());
        }
         //访问 Token 检测处理
        $this->token = $this->request->param('token');
        // 验证白名单
        $exclude = [
            'login',
            'register',
            'sms'
        ];
        if (!in_array(strtolower($request->controller()), $exclude)) {
            if (empty($this->token) || !$this->method_token($this->token)){
                exit($this->response('访问TOKEN失效，请重新授权！', 403)->send());
            }
        }

    }
    //验证token
    public  function method_token($token)
    {
        // $result = Db::table('dw_token')->where(['token'=>$token])->find();
        $result = Token::where(['token'=>$token])->find();
        if(!$result){
            return false;
        };
        if($result['expire_time'] === 0 || $result['expire_time'] > time()) {
            // 获取用户信息
            request()->userInfo = $result->userInfo;
            return true;
        }else{
            return false;
        }
    }

    /**
     * 输出返回数据
     * @param array|string $data 要返回的数据 | 提示信息
     * @param int|string $msg  状态码 | 提示消息内容
     * @param int $code 业务状态码
     * @param string $type 返回类型 JSON XML
     * @return Response
     */
    public function response($data = null, $msg = 'OK', $code = 200, $type = 'json')
    {
        // 状态不是成功时去除返回数据
        if (is_int($msg) && $msg !== 200) {
            $type = is_string($code) ? $code : $type;
            $code = $msg;
            $msg = $data;
            $data = null;
        }
        $result = [
            'msg' => $msg,
            'code' => $code,
            'token' => $this->token,
            'data' => $data,
            ];
        return Response::create($result, $type)->header(ToolsService::corsRequestHander())->code(200);
    }
    //token的创建
    public function createToken(){
        $token = md5(uniqid(md5(microtime(true)),true));// 生成随机的token
        $result['token'] = sha1($token);//加密
        $result['expire_time'] = 0;//过期时间
        return $result;
    }
    //短信验证
    public function checkSms($phone,$sms_code,$sms_type)
    {
        $sms = Db::table('dw_sms')->where(['phone'=>$phone,'sms_type'=>$sms_type])->find();
        if (!$sms) {
            $msg = '请发送验证码!';
            return $msg ;
        }
        if ($sms_type == 6) {
            $result = Db::table('dw_users')->where(['user_phone' => $phone])->find();
            if ($result) {
                $msg = '该号码已存在!';
                return $msg ;
            }
        }
        if ($sms['sms_code'] != $sms_code) {
            $msg = '验证码不正确!';
            return $msg ;

        } elseif (time() > $sms['expire_time']) {
            $msg = '验证码过期!';
            return $msg ;

        } else {
            return 1;
        }
    }
    /**
     * 写入缓存
     * @param string $name 缓存标识
     * @param mixed $value 存储数据
     * @param int|null $expire 有效时间 0为永久
     * @return bool
     */
    public function setCache($name, $value, $expire = null)
    {
        return Cache::set("{$this->token}_{$name}", $value, $expire);
    }

    /**
     * 读取缓存
     * @param string $name 缓存标识
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getCache($name, $default = false)
    {
        return Cache::get("{$this->token}_{$name}", $default);
    }

    /**
     * 删除缓存
     * @param string $name 缓存标识
     * @return bool
     */
    public function delCache($name)
    {
        return Cache::rm("{$this->token}_{$name}");
    }

    /**
     * API接口调度
     * @return Response
     */
    public function _empty()
    {
        list($module, $controller, $action, $method) = explode('/', $this->request->path() . '///');
        if (!empty($module) && !empty($controller) && !empty($action) && !empty($method)) {
            $action = ucfirst($action);
            $Api = config('app_namespace') . "\\{$module}\\{$controller}\\{$action}Api";
            if (method_exists($Api, $method)) {
                return $Api::$method($this);
            }
            return $this->response('访问的接口不存在！', 'API_NOT_FOUND', 405);
        }
        return $this->response('不符合标准的接口！', 'API_ERROR', 405);
    }

    //时间格式
    public function getTime($time)
    {
        $time = date('Y-m-d H:i:s',$time);
        return $time;
    }

    //短信发送公共接口
    public function sendSms($mobile,$smscode,$params)
    {
        // 测试
        if (\think\Config::get('SMS_DEBUG')) {
            return [
                'Code'=>'OK',
                'Msg'=>'发送成功',
            ];
        }
        require_once EXTEND_PATH .'/aliyunsms/vendor/autoload.php';
        Config::load();
        $sms_config = \think\Config::get('SMS_CONFIG'); //短信参数
        $templateParam = $params;
        $signName = $sms_config['sign'];
        $templateCode = $smscode;
        $product = "Dysmsapi";
        $domain = "dysmsapi.aliyuncs.com";
        $region = "cn-hangzhou";
        $profile = DefaultProfile::getProfile($region, $sms_config['key'], $sms_config['secret']);
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        $acsClient= new DefaultAcsClient($profile);
        $request = new SendSmsRequest();
        $request->setPhoneNumbers($mobile);
        $request->setSignName($signName);
        $request->setTemplateCode($templateCode);
        if($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }
        $acsResponse = $acsClient->getAcsResponse($request);
        $result = json_decode(json_encode($acsResponse),true);
        return $result;
    }

     //用户生成二维码（邀请好友注册）
    public function getWchatQrcode($users_id){
        $invite_code = Db::table('rm_user')->where(['user_id'=>$users_id])->value('invite_code');
        //带LOGO
         $url = \think\Config::get('image_url').'/api/Register/reg?invite_code='.$invite_code; //二维码内容
         $errorCorrectionLevel = 'L';//容错级别
         $matrixPointSize = 9;//生成图片大小
         //生成二维码图片
         Vendor('phpqrcode.phpqrcode');
         $object = new \QRcode();
         $ad = 'erweima/'.$users_id.'.jpg';
         $object->png($url, $ad, $errorCorrectionLevel, $matrixPointSize, 2);
         $logo = 'erweima/logo.jpg';//准备好的logo图片
         $QR = 'erweima/'.$users_id.'.jpg';//已经生成的原始二维码图
         if ($logo !== FALSE) {
           $QR = imagecreatefromstring(file_get_contents($QR));
           $logo = imagecreatefromstring(file_get_contents($logo));
           $QR_width = imagesx($QR);//二维码图片宽度
           $QR_height = imagesy($QR);//二维码图片高度
           $logo_width = imagesx($logo);//logo图片宽度
           $logo_height = imagesy($logo);//logo图片高度
           $logo_qr_width = $QR_width / 5;
           $scale = $logo_width/$logo_qr_width;
           $logo_qr_height = $logo_height/$scale;
           $from_width = ($QR_width - $logo_qr_width) / 2;
           //重新组合图片并调整大小
           imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
           $logo_qr_height, $logo_width, $logo_height);
         }
        //输出图片  带logo图片
         imagepng($QR, 'erweima/'.$users_id.'.png');
//        //不带LOGO
//        Vendor('phpqrcode.phpqrcode');
//        //生成二维码图片
//        $object = new \QRcode();
////        var_dump($object);die;
//        $url='http://www.shouce.ren/';//网址或者是文本内容
//        $level=3;
//        $size=4;
//        $ad = 'erweima/'.$users_id.'.jpg';
//        $errorCorrectionLevel =intval($level) ;//容错级别
//        $matrixPointSize = intval($size);//生成图片大小
//        $object->png($url,  $ad, $errorCorrectionLevel, $matrixPointSize, 2);

    }
    //用户生成二维码（添加好友图片，不带logo）
    public function getCode($users_id){
        //带LOGO
        $url = 'user_id='.$users_id; //二维码内容
        $ad = 'erweima/friend/'.$users_id.'.jpg';
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 9;//生成图片大小
        //生成二维码图片
        Vendor('phpqrcode.phpqrcode');
        $object = new \QRcode();

        $object->png($url, $ad, $errorCorrectionLevel, $matrixPointSize, 2);

    }
    //经纬度查找
    public function calcScope($lat, $lng, $radius) {
        $degree = (24901*1609)/360.0;
        $dpmLat = 1/$degree;

        $radiusLat = $dpmLat*$radius;
        $minLat = $lat - $radiusLat;       // 最小纬度
        $maxLat = $lat + $radiusLat;       // 最大纬度

        $mpdLng = $degree*cos($lat * (PI()/180));
        $dpmLng = 1 / $mpdLng;
        $radiusLng = $dpmLng*$radius;
        $minLng = $lng - $radiusLng;      // 最小经度
        $maxLng = $lng + $radiusLng;      // 最大经度

        /** 返回范围数组 */
        $scope = array(
            'minLat'    =>  $minLat,
            'maxLat'    =>  $maxLat,
            'minLng'    =>  $minLng,
            'maxLng'    =>  $maxLng
        );
        return $scope;
    }
    /**
     * rsa加解密方法
     */
    public  function rsa($phone,$type=true){
        header('Content-Type:text/html;Charset=utf-8;');

        $pubfile = ROOT_PATH.'/rsa_public_key.pem';
        $prifile = ROOT_PATH.'/rsa_private_key.pem';
        $rsa = new RSA($pubfile, $prifile);
        //加密
        if($type){
            if (is_array($phone)) {
                $phone = json_encode($phone);
            }
            $ret_e = $rsa->encrypt($phone);
        }else{//解密
            $ret_e = $rsa->decrypt($phone);
            if ($json = json_decode($ret_e, true)) {
                $ret_e = $json;
            }
        }
       return $ret_e;
    }
    /**
     * 同步用户信息
     * @param $user_id 用户id
     * @return Boolean
     */
    public function syncUserInfo($user_id) {
        $Chat = controller('Chat');
        return $Chat->update_info($user_id);
    }

}
