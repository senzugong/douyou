<?php


namespace hook;

use think\Request;

/**
 * 视图输出过滤
 * Class FilterView
 * @package hook
 * @author jonny <980218641@qq.com>
 * @date 2017/04/25 11:59
 */
class FilterView
{

    /**
     * 当前请求对象
     * @var Request
     */
    protected $request;

    /**
     * 行为入口
     * @param $params
     */
    public function run(&$params)
    {
        $this->request = Request::instance();
        $appRoot = $this->request->root(true);
        $replace = [
            '__APP__'    => $appRoot,
            '__SELF__'   => $this->request->url(true),
            '__PUBLIC__' => strpos($appRoot, EXT) ? ltrim(dirname($appRoot), DS) : $appRoot,
        ];
        $params = str_replace(array_keys($replace), array_values($replace), $params);
        !IS_CLI && $this->baidu($params);
    }

    /**
     * 百度统计实现代码
     * @param $params
     */
    public function baidu(&$params)
    {
        if (($key = sysconf('tongji_baidu_key'))) {
            $https = Request::instance()->isSsl() ? 'https' : 'http';
            $script = <<<SCRIPT
\n<!-- 百度统计 开始 -->
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "{$https}://hm.baidu.com/hm.js?{$key}";
        var s = document.getElementsByTagName("script")[0]; 
        s.parentNode.insertBefore(hm, s);
    })();
</script>
<!-- 百度统计 结束 -->\n\n
SCRIPT;
            $params = preg_replace('|</body>|i', "{$script}</body>", $params);
        }
    }

}
