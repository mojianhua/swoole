<?php
$client = new swoole_client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);
$client->connect('0.0.0.0', 9509, 10) or die("连接失败");
while (true) {
	$now = date('Y-m-d H:i:s');
	$sql = "insert into company_news (title,cover,description,content,create_time) values ('测试文章11112333','http','d13123131','sfsdffds','$now')";
	$client->send($sql);
	$data = $client->recv();
	var_dump($data);
}

$client->close();