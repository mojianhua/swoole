<?php
//服务器代码

//创建web 服务器
$ws = new swoole_websocket_server('0.0.0.0',9090);

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
//var_dump($redis->hGetAll('userData'));  
//exit;
//$redis->flushAll();exit;
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
		$allUserkey = $redis->hKeys('userData');
		$username = $redis->hGet('userData', $fd);
		$msg = $username.'：'.$request->data."\n";
		foreach ($allUserkey as $fd) {
			$ws->push($fd,$msg);
		}
	}
});

//close
$ws->on('close',function($ws,$request) use ($redis) {
	global $redis;
	$del = $redis->hDel('userData', $request);
	if($del){
		echo "客户端-{$request} 断开链接 \n";
	}
	//var_dump($redis->hGetAll('userData'));
	//unset($GLOBALS['fd'][$request]); //清除链接池
});

$ws->start();