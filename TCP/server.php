<?php
//tcp 服务器
$serv = new swoole_server('127.0.0.1',9999);

//设置异步进程数
$serv->set(array('task_worker_num'=>4));

//投递任务
$serv->on("receive",function($serv,$fd,$from_id,$data){
	$task_id = $serv->task($data); //异步id
	echo "异步id : $task_id \n";
});

//异步处理
$serv->on('task',function($serv,$task_id,$from_id,$data){
	echo "执行 异步 ID : $task_id \n";
	$serv->finish("$data -> OK");
});

//处理结果
$serv->on('finish',function($serv,$task_id,$data){
	echo "执行成功";
});

$serv->start();