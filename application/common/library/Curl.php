<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19
 * Time: 10:55
 */

namespace app\common\library;

/**
 * Class Curl
 * 请求处理
 * @package app\common\library
 */
class Curl
{
    /**
     * @var int 请求超时时间
     */
    protected $timeout = 5000;
    /**
     * @var string 请求地址
     */
    protected $url = '';
    /**
     * @var array 请求数据
     */
    protected $data = [];
    /**
     * @var array 请求头部
     */
    protected $header = [];
    /**
     * @var string 最后错误结果
     */
    public static $lastError;

    /**
     * 创建GET请求
     * @param $url
     * @param array $data
     * @param array $header
     * @return bool|int|string
     */
    public static function get($url, $data =[], $header = []) {
        $curl = new self();
        $curl->url = $url;
        $curl->data = $data;
        $curl->header = $header;
        return $curl->pushData();
    }

    /**
     * 创建POST请求
     * @param $url
     * @param array $data
     * @param array $header
     * @return bool|int|string
     */
    public static function post($url, $data =[], $header = []) {
        $curl = new self();
        $curl->url = $url;
        $curl->data = $data;
        $curl->header = $header;
        return $curl->pushData('POST');
    }

    /**
     * 请求数据
     * @param string $method
     * @return bool|int|string
     */
    public function pushData($method = 'GET') {
        // 头部处理
        $headData = [];
        if (is_array($this->header) && !empty($this->header)) {
            foreach ($this->header as $key => $value) {
                if (is_int($key) && strpos($value, ':') !== false) {
                    $headData[] = $value;
                } else {
                    $headData[] = $key . ':' . $value;
                }
            }
        }
        // 参数处理
        $postDataArray = [];
        if (is_array($this->data) && !empty($this->data)) {
            foreach ($this->data as $key => $value) {
                array_push($postDataArray, $key . '=' . urlencode($value));
            }
        }
        $postData = join('&', $postDataArray);

        $curl = curl_init();
        //
        switch ($method) {
            case 'GET':
                // 参数与地址连接符
                $link = strpos($this->url, '?') !== false ? '&' : '?';
                // 连接参数
                $url = $this->url.$link.$postData;
                curl_setopt ($curl, CURLOPT_URL, $url);
                break;
            case 'POST':
                curl_setopt ($curl, CURLOPT_URL, $this->url);
                // 启用POST请求
                curl_setopt ($curl, CURLOPT_POST, 1);
                // 添加POST参数
                if (!empty($postData)){
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                }
                break;
        }
        // 添加请求头
        if (!empty($headData)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headData);
        }
        // 返回header
        curl_setopt ($curl, CURLOPT_HEADER, false );
        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER,false); //处理http证书问题
        curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        if (false === $result) {
            self::$lastError =  curl_errno($curl);
        } else {
            // 转换json数据
            $json = json_decode($result, true);
            $result = $json ?: $result;
        }
        curl_close($curl);
        return $result;
    }
}