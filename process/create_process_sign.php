<?php
//swoole 触发器

//触发函数 达到10停止
swoole_process::signal(SIGALRM,function(){
	static $i = 0;
	echo "$i \n";
	$i++;
	if($i>100){
		swoole_process::alarm(-1);	//清理定时器
	}
});

//定时信号
swoole_process::alarm(100 * 1000);