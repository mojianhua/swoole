<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2017/7/30
 * Time: 15:24
 */
$ws_server = new swoole_websocket_server('0.0.0.0', 9502);

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
//$redis->flushAll();exit;

$ws_server->on('open', function ($ws, $request) use ($redis) {
    $redis->sAdd('fd', $request->fd);
});

$ws_server->on('message', function ($ws, $frame) use ($redis) {
    global $redis;
    $fds = $redis->sMembers('fd');
    foreach ($fds as $fd){
        $ws->push($fd,$frame->fd.'--'.$frame->data);
        //发送二进制数据：
        //$ws->push($fd,WEBSOCKET_OPCODE_BINARY);
    }
});

//监听WebSocket连接关闭事件
$ws_server->on('close', function ($ws, $fd) use ($redis) {
    $redis->sRem('fd',$fd);
});

$ws_server->start();



//服务器代码

//创建web 服务器
/*
$ws = new swoole_websocket_server('0.0.0.0',9090);

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$ws->on('open',function($ws,$request) use ($redis) {
	echo "新用户 $request->fd 加入。 \n";
	$fd = $request->fd;
	$setname = $redis->hSet('userData',$fd,'匿名');
	if($setname){
		echo "设置默认用户名成功 \n";
	}
});

//message
$ws->on('message',function($ws,$request) use ($redis) {
	global $redis;
	$fd = $request->fd;
	if(strstr($request->data, "#name#")){  //设置昵称
		$username = str_replace('#name#', '', $request->data);
		$setname = $redis->hSet('userData',$fd,$username);
		if(!$setname){
			echo "设置用户名成功 \n";
		}
		var_dump($redis->hGetAll('userData'));  
	}else{
		//信息发送
		$allUser = $redis->hGetAll('userData');
		var_dump($allUser);
		foreach ($allUser as $ak=>$av) {
			$msg = $fd.'：'.$request->data."\n";
			$ws->push($ak,$msg);
		}
	}
});

//close
$ws->on('close',function($ws,$request) use ($redis) {
	echo "客户端-{$request} 断开链接 \n";
	$redis->sRem('userData',$allUser[$request]);
	//unset($GLOBALS['fd'][$request]); //清除链接池
});

$ws->start();