<?php
/**
* 单例模式mysql pdo,个人理解是，链接msql后直接保存到静态变量中，防止重复链接
*/
 class Mysql{
 	//保存实例
 	protected static $conn = null;
 	protected $dsn;
 	protected $pdo;
 	//构造方法为private,防止创建对象
 	private function __construct(){
 		$dbms='mysql';
 		$host = '120.79.82.241';
 		$port = '3603';
 		$dbname = 'test_f2c';
 		$user = 'root';
 		$password = '1qa2ws#ED';
 		try{
 			$this->dsn = "$dbms:host=$host:$port;dbname=$dbname";
 			$this->pdo = new PDO($this->dsn,$user,$password);
 		} catch (PDOException $e) {
 			exit($e->getMessage());
 		}
 	}

 	//唯一入口
 	public static function getInstance(){
 		if(is_null(self::$conn)){
 			self::$conn = new self();
 		}
 		return self::$conn;
 	}

 	//禁止克隆
 	public function __clone(){
 		trigger_error('禁止克隆');
 	}

 	//关闭数据库
 	public function __destruct(){
 		$this->pdo = null;
 	}
 }

 $mysql1 = Mysql::getInstance();
 var_dump($mysql1);
 $mysql2 = Mysql::getInstance();
 var_dump($mysql2);
?>