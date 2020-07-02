<?php
header("content-type:text/html;charset=utf-8");
$dbms='mysql';     //数据库类型
$host='127.0.0.1'; //数据库主机名
$dbName='supermarket';    //使用的数据库
$user='root';      //数据库连接用户名
$pass='root';          //对应的密码
$dsn="$dbms:host=$host;dbname=$dbName";


try {
	$conn = new PDO($dsn, $user, $pass); 
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
	$conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
	$conn->query("set names utf8");
} catch (PDOException $e) {
	die ("Error!: " . $e->getMessage() . "<br/>");
}
?>
