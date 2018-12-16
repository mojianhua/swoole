<?php
//服务器代码

//创建web 服务器
$ws = new swoole_websocket_server('0.0.0.0',9090);

//open 情况 建立链接 或者当客户端打开时候
$ws->on('open',function($ws,$request){
	echo "新用户 $request->fd 加入。 \n";
	$GLOBALS['fd'][$request->fd]['id'] = $request->fd; //设置用户id
	$GLOBALS['fd'][$request->fd]['name'] = '匿名'; //设置用户名
});

//message
$ws->on('message',function($ws,$request){
	$msg = $GLOBALS['fd'][$request->fd]['name'].":".$request->data."\n";
	var_dump($msg);
	if(strstr($request->data, "#name#")){  //设置昵称
		$GLOBALS['fd'][$request->fd]['name'] = str_replace('#name#', '', $request->data);
	}else{
		//信息发送
		var_dump($GLOBALS);
		foreach ($GLOBALS['fd'] as $value) {
			var_dump($value);
			$ws->push($value['id'],$msg);
		}
	}
});

//close
$ws->on('close',function($ws,$request){
	echo "客户端-{$request} 断开链接 \n";
	unset($GLOBALS['fd'][$request]); //清除链接池
});

$ws->start();