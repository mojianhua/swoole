<?php
//客户端
// $serv = new swoole_client(SWOOLE_SOCK_TCP);

// $conent = connect("127.0.0.1"，9900,5) or die ("链接失败");

// //服务器发送
// $conent->send('hello world') or die("发送是吧");

// //服务器接收数据
// $data = $data->rece();
// if(!isset($data)){
// 	die("发送失败")
// }

//异步客户端
$client = new swoole_client(SWOOLE_SOCK_TCP,SWOOLE_SOCK_ASYNC);
//Z注册链接成功的回调
$client->on('connect',function($cli){
	$cli->send("hello word \n");
});

//注册数据接收 $cli:服务端信息 $data:数据
$client->on("receive",function($cli,$data){
	echo "data : $data";
});

//注册失败
$client->on("error",function($cli){
	echo "失败 \n";
});

//注册关闭函数
$client->on('close',function($cli){
	echo "关闭 \n";
});

//发起链接
$client->connect("127.0.0.1",9990,10);