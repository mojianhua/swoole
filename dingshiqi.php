<?php
//循环执行
swoole_timer_tick(2000,function($time_id){
	echo "执行 $time_id \n";
});

//单次执行
swoole_timer_after(3000,function(){
	echo "3000 后执行 \n";
});