<?php
//异步mysql

//实例化
$db = new swoole_mysql();
$config = [
	'host'=>'127.0.0.1',
	'user'=>'root',
	'password'=>'',
	'database'=>'test',
	'charset'=>'utf8'
];

//链接数据库
$db->connect($config,function($db,$r){
	if($r == false){
		var_dump($db->connect_errorno,$db->connect_error);
		die('链接失败');
	}

	//成功
	$sql = "select * from ad";
	$db->query($sql,function(swoole_mysql $db,$r){
		if($r == false){
			var_dump($db->error);
			die('操作失败');
		}elseif($r == true){
			var_dump($db->affected_rows,$db->insert_id);	
		}
		var_dump($r);
		//关闭代码
		$db->close();
	});
});