<?php
//创建swoole锁
$lock = new swoole_lock(SWOOLE_MUTEX); //互斥锁
echo "创建互斥锁\n";
$lock->lock(); //开始锁定 主进程
if(pcntl_fork() > 0){
	sleep(5);
	$lock->unlock(); //解锁
	echo "主进程解锁\n";
}else{
	echo "子进程等待 \n";
	$lock->lock;  //上锁
	echo "子进程获取锁 \n";
	$lock->unlock(); //释放锁 
	exit('子进程结束');
}

echo "主进程 释放锁\n";
unset($lock);
sleep(5);
echo  "子进程退出\n";