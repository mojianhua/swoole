<?php
//websocket 服务器
//创建Server服务器
$ws = new swoole_websocket_server('127.0.0.1', 9999);
//建立连接 $ws 服务器 $request:客户端信息
$ws->on("open", function ($ws,$request) {
    var_dump($request);
    $ws->push($request->fd,"hi ni hao \n");
});

// 接收信息
$ws->on('message',function($ws,$request){
	echo "mssage: $request->data";
	$ws->push($request-fd."接收到信息");
});
//关闭信息
$ws->on('close',function($ws,$request){
	echo "close \n";
});

$ws->start();

///Applications/XAMPP/bin/php-7.0.2 /Applications/XAMPP/htdocs/swoole/demo1-serv.php