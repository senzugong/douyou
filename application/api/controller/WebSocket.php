<?php
namespace app\api\controller;
use controller\BasicApi;
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

class WebSocket extends BasicApi {

    public function test(){
        $worker = new Worker();
        $worker->onWorkerStart = function($worker){

            $con = new AsyncTcpConnection('wss://api.zb.cn/websocket');

            $con->onConnect = function($con) {
                $data = ['event'=>'addChannel','channel'=>'ltcbtc_ticker'];
                $con->send(json_encode($data));
            };

            $con->onMessage = function($con, $data) {
                $this->response($data);
            };

            $con->connect();
        };

        Worker::runAll();
    }



//    public function __construct()
//    {
//        $worker = new Worker();
//        $worker->count = 1;
//        $worker->onWorkerStart = function($worker){
//            // ssl需要访问443端口wss://api.zb.cn/websocket
//            $con = new AsyncTcpConnection('wss://api.zb.cn/websocket');
//
////            // 设置以ssl加密方式访问，使之成为wss
////            $con->transport = 'ssl';
//
//            $con->onConnect = function($con) {
//                $con->send('hello');
//            };
//
//            $con->onMessage = function($con, $data) {
//                echo 1111;
//            };
//
//            $con->connect();
//        };
//        Worker::runAll();
//    }
}



