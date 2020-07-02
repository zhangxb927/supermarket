<?php
	date_default_timezone_set('prc');
	include 'connection.php';
	include 'common.php';
	$method=$_SERVER['REQUEST_METHOD'];
	// $url=parse_url($_SERVER['QUERY_STRING']);
	// $args=convertUrlQuery($url);

	switch($method){
		case "POST":
			$name = $_POST['name'];
			$password = $_POST['password'];
			$login_time = strtotime('now');
			$sql = sprintf("SELECT * FROM administrators WHERE name='%s' AND password='%s';",$name,md5($password));
			$res = $conn->query($sql);
			$data = $res->fetch(PDO::FETCH_ASSOC);
			if($data){
				$uid = $data['uid'];
				$sqll = sprintf("update administrators set login_time='%s' where uid='%s'",$login_time,$uid);
				$state=$conn->query($sqll);
				if($state){
					echo json_encode(array(
						"ret" => 0,
						"data" => $data
					));
				}
				
			}else{
				echo json_encode(array(
					"ret" => -1
				));
			}
		break;
 	}
?>