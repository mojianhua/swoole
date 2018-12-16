<?php
//udp服务器
//创建Server对象，监听 127.0.0.1:9998端口
$serv = new swoole_http_server('127.0.0.1', 9996);
//监听数据接收
$serv->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9997\n";
});

// $request：请求信息 $response:返回信息
$serv->on('request',function($request,$response){
	var_dump($request);
	$response->header("Content-Type","text/html;charset=utf-8");//设置头返回新
	$response->end("hello world".rand(1000,9999));
});
//启动服务
$serv->start();

///Applications/XAMPP/bin/php-7.0.2 /Applications/XAMPP/htdocs/swoole/demo1-serv.php