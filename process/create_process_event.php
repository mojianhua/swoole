<?php
$workers = []; //进程池 进程数据
$workers_num = 3; //进程数

//创建进程
for($i=0;$i<$workers_num;$i++){
	$process = new swoole_process('doProcess'); //创建进程
	$pid = $process->start(); //创建进程，并取得进程ID
	$workers[$pid] = $process; //存入进程数组
}

//创建进程执行函数
function doProcess(swoole_process $process){
	$process->write("PID: $process->pid");  //子进程写入信息
	echo "写入信息: $process->pid $process->callback \n";
}

//添加进程时间 每一个子进程添加执行的动作
foreach ($workers as $process) {
	swoole_event_add($process->pipe,function($pipe) use($process){
		$data = $process->read(); //是否读取到数据
		echo "接收到 $data \n";
	});
}