<?php
//创建Server对象，监听 127.0.0.1:9999端口
//$host 监听ip $port 监听端口 1024端口一下要root 权限
//监听多个ip 可以写成0.0.0.0
//$mode : SWOOLE_PROCESS 多进程的方式
$host = '127.0.0.1';
$port = 9998;
$serv = new swoole_server($host, $port);
//监听连接进入事件
//$server->on(string $event,mixed $callback);
/**
* $event:
* connect : 当链接的时候 $serv 实例化变量 $fd 客户端信息
* receive : 接收的时候 $serv 实例化变量 $fd 客户端 $fromId客户端id  $data 数据
* close : 关闭的时候 $serv 实例化变量 $fd 客户端 
*/
$serv->on('connect', function (swoole_server $serv, $fd) {
	//var_dump($serv); 链接参数
	//var_dump($fd); 链接数
    echo '建立链接成功' . PHP_EOL;
});

//监听数据接收事件
$serv->on('receive', function (swoole_server $serv, $fd, $fromId, $data) {
    $serv->send($fd, 'Server: ' . $data);
});
//监听连接关闭事件
$serv->on('close', function (swoole_server $serv, $fd) {
    echo 'Client: Close.' . PHP_EOL;
});
//启动服务器
$serv->start();

///Applications/XAMPP/bin/php-7.0.2 /Applications/XAMPP/htdocs/swoole/demo1-serv.php