<?php
$server = new swoole_server('127.0.0.1',9509);
$server->set([
	'worker_num'=>20,    //100worker进程
	'task_worker_num'=>10, //可以用10个task进程，就是有10个进程可以用长链接
]);

//$fd 是客户端id,$from_id 是线程id,$data,是接收客户端的参数
//从客户端接收数据
function go_onReceive($server,$fd,$from_id,$data){
	echo "接收数据：".$data."-----"."fd：".$fd.'-----'."from_id：".$from_id."\n";
	//堵塞SQL,并且返回结果
	$res = $server->taskwait($data);
	echo "任务结束".PHP_EOL;
	if($res!==false){
		//向客户端发送请求
		list($status,$db_res) = explode('-----', $res,2);
		if($status == 'OK'){
			$server->send($fd, var_export(unserialize($db_res),true) . "\n");
		}else{
			$server->send($fd,$db_res);
		}
		$server->send($fd,"Receive OK \n");
	}else{
		$server->send($fd,"Error.Task time out \n");
	}
}

//$task_id是任务id
//处理任务
function go_onTast($server,$task_id,$from_id,$sql){
	echo "开始任务数据 task_id：".$task_id."-----sql：$sql"."\n";
	static $link = null;
	if($link == null){
		try {
			$pdo = new pdo('mysql:host=localhost:3603;dbname=company','root','123',[PDO::ATTR_PERSISTENT => true]);
		}catch (PDOException $e) {
			$server->finish("Mysql Error : ".$e->getMessage()."\n");
		}
		$stmt = $pdo->prepare($sql);
		$query = $stmt->execute();
		if($query){
			if(preg_match('/^select/i', $sql)){
				$res = $stmt->fetchAll();
			}else{
				$res = $stmt->rowCount();
			}
		}else{
			$server->finish("Mysql Error : sql false !!!!! \n");
		}
	}
	$server->finish("OK-----" . serialize($res));
}

function go_onFinish($server,$task_id,$data){
	echo "结束任务：task_id：".$task_id."\n";
}

$server->on('receive','go_onReceive');
$server->on('task','go_onTast');
$server->on('Finish','go_onFinish');

$server->start();