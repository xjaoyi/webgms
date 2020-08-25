<?php
namespace bootstrap;

use libs\Conf;

class App
{
    public static $routeInfo;
    public static $htmlresult;

    public static function run(){
        self::denfDir();
        self::getUrl();
        self::whoops_error();
        self::Route();
    }

    /**
     * notes:路径定义
     * @uthor: sandy
     * @date: 2020/8/24 17:04
     */
    public static function denfDir()
    {
        define('ROOT_PATH', dirname(__DIR__) . '/');
        define('CONF_PATH', ROOT_PATH . 'conf/');
        define('RESOURCE', ROOT_PATH . 'resource/');
        define('CACHE', ROOT_PATH . 'caching/');
    }

    public static function getUrl()
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            include ROOT_PATH . 'router/web.php';
        });
        /** 下面都是基础实现的方法 在fast-router有**/
        // 获取传输类型以及.com后的参数
        $httpMethod = $_SERVER['REQUEST_METHOD'];

        $uri = $_SERVER['REQUEST_URI'];
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars
                break;
        }
        /** 上面都是基础实现的方法 在fast-router有**/
        //把对应的参数与控制器的关系放在静态变量方便分发
        self::$routeInfo = $routeInfo;
   }

    public static function whoops_error()
    {
        //  whoops报错插件
        //根据app.php中的debug判断是否报错
        $bool = Conf::get('debug');
        if ($bool) {
            $whoops = new \Whoops\Run;
            $errorTitle = '框架出错了！';
            $option = new \Whoops\Handler\PrettyPageHandler();
            $option->setPageTitle($errorTitle);
            $whoops->pushHandler($option);
            $whoops->register();
            ini_set('display_error', 'On');
        } else {
            ini_set('display_error', 'Off');
        }
    }

    /**
     *  分发路由
     */
    public static function Route()
    {
        if (self::$routeInfo[0] !=0){
            //把方法、控制器根据@符号炸开
            $routerMessage = explode('@', self::$routeInfo[1]);
            //由于我们控制都是在app/的Controller里面我们这里为什么可以大写由于自动加载做了对应关系
            $controller = 'App\\Controller\\' . $routerMessage[0];
            $controller = str_replace('/','\\',$controller);
            $action = $routerMessage[1];

            //-----NEW 方式-----
            //$obj = new $controller;
            //$obj->$action();

            //----反射方式-------
            $obj = new $controller;
            //通过反射获得参数
            $reflection = new \ReflectionMethod($controller, $action);
            $actionParameters=$reflection->getParameters();

            //获取到方法参数为一个类

            if (!empty($actionParameters)){
                //如果参数不为空
                foreach ($actionParameters as $actionP){
                    $parame=$actionP->getType()->getName();
                }
                $parameters=new $parame;
                self::$htmlresult=$obj->$action($parameters);
            }else{
                //如果参数为空
                self::$htmlresult = $obj->$action();
            }

        }else{
            throw new \ErrorException('路由错误！请检查路由是否正确！');
        }
    }

    public static function send()
    {
        $res = self::$htmlresult;


        if (gettype($res) == 'string') {
            //字符串
            echo $res;
        } else {

            echo json_encode($res);
        }
    }

}