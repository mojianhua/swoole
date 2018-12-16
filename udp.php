<?php
//udp服务器
//创建Server对象，监听 127.0.0.1:9998端口
$serv = new swoole_server('127.0.0.1', 9998,SWOOLE_PROCESS,SWOOLE_SOCK_UDP);
//监听数据接收
// $serv:服务器信息 $data 数据，接收到信息 $fd 客户端信息
$serv->on('packet',function($serv,$data,$fd){
	//发送数据到相应客户端，反馈信息
	$serv-sendto($fd['address'],$fd['port'],"Server : $data");
	var_dump($fd);
})
//启动服务
$serv->start();

///Applications/XAMPP/bin/php-7.0.2 /Applications/XAMPP/htdocs/swoole/demo1-serv.php