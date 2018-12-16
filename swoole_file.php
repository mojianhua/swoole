<?php
//创建swoole文件处理
//读取文件
swoole_async_readfile(__DIR__."/swoole_dns.php",function($filename,$content){
		echo  "$filename $content";
});

//异步写入文件
$content = 'hi jim hi gogo';
//第一个参数 写入地址,第二个参数内容最大不超过4m,第三个参数文件名
swoole_async_writefile(__DIR__.'/2.txt',$content,function($filename){
		echo $filename;
},0);
