<?php
//进程通讯
$workers = [];//进程仓库
$workers_num = 2; //最大进数

//创建进程
for($i = 0;$i<$workers_num;$i++){
	//因为要使用管道进程通讯
	$process = new swoole_process('doProcess',false,false);
	//开启队列 ，类似全局函数
	$process->useQueue();
	$pid = $process->start();
	$workers[$pid] = $process;
}

//执行进程函数
function doProcess(swoole_process $process){
	//获取数据
	$recv = $process->pop(); //8192
	echo "从主进程获取到的数据 : $recv \n";
	sleep(5);
	//退出子进程
	$process->exit(0);
}

//主进程向子进程添加数据
foreach($workers as $pid => $process){
	$process->push("hello 子进程 $pid \n");
}

//等待子进程结束 回收资源
for($i = 0;$i<$workers_num;$i++){
	$ret = swoole_process::wait(); //等待完成
	$pid = $ret['pid'];
	unset($workers[$pid]);
	echo "子进程退出 $pid \n";
}