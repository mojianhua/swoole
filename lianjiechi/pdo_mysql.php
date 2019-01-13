<?php
echo date('Y-m-d H:i:s');
$pdo = new pdo('mysql:host=localhost:3603;dbname=company','root','123');
for ($i=0; $i<=5000 ; $i++) {
		$now = date('Y-m-d H:i:s');
		$sqls[$i] = "insert into company_news (title,cover,description,content,create_time) values ('测试文章11112333','http','d13123131','sfsdffds','$now')";
	}

foreach ($sqls as $key => $value) {
	$stmt = $pdo->prepare($value);
	$query = $stmt->execute();
	var_dump($key);
}
echo date('Y-m-d H:i:s');