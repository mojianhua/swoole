<?php
echo date('Y-m-d H:i:s');
$pdo = new pdo('mysql:host=localhost:3603;dbname=company','root','123');
$sqls = "insert into company_news (title,cover,description,content,create_time) values ('测试文章11112333','http','d13123131','sfsdffds','$now')";
$stmt = $pdo->prepare($sqls);
$query = $stmt->execute();