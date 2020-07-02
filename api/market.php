<?php
include 'connection.php';
include 'common.php';
$method=$_SERVER['REQUEST_METHOD'];
switch($method){
    case 'GET':
		$url=parse_url($_SERVER['QUERY_STRING'])['path'];
		if($url==''){
			$sql = "select mkid,descp from markets";
			$res=$conn->query($sql);
			$data=$res->fetchAll(PDO::FETCH_ASSOC);
			if($data){
				echo json_encode(array(
					"ret" => 0,
					"data" => $data
				));
			}else {
				echo json_encode(array(
					"ret" => -1
				));
			}
			die;
		}
		$parameter = explode('=',$url)[0];
		switch($parameter){
			case 'page':
				$page = (int)explode('=',$url)[1];
				$start = ($page-1)*10;
				$sql = "select * from markets limit $start,10";
				$res=$conn->query($sql);
				$data=$res->fetchAll(PDO::FETCH_ASSOC);
				$count = $conn->query("select count(*) from markets")->fetchColumn();
				if($data){
					echo json_encode(array(
						"ret" => 0,
						"num_results" => $count,
						"data" => $data
					));
				}else {
					echo json_encode(array(
						"ret" => -1
					));
				}
			break;
			case 'mkid':
				$mkid = (int)explode('=',$url)[1];
				$sql = sprintf("select * from markets where mkid='%s'",$mkid);
				$res = $conn->query($sql);
				$data = $res->fetchAll(PDO::FETCH_ASSOC);
				if($data){
					echo json_encode(array(
						"ret" => 0,
						"data" => $data
					));
				}else {
					echo json_encode(array(
						"ret" => -1
					));
				}
			break;
			case 'descp':
				$descp = $_GET['descp'];
				$sql = "select * from markets where descp like '%$descp%'";
				$res = $conn->query($sql);
				$data = $res->fetchAll(PDO::FETCH_ASSOC);
				if($data){
					echo json_encode(array(
						"ret" => 0,
						"data" => $data
					));
				}else {
					echo json_encode(array(
						"ret" => -1
					));
				}
			break;
			case 'kind':
				$kind = (int)explode('=',$url)[1];
				$sql = sprintf("select * from markets where kind=%s",$kind);
				$res = $conn->query($sql);
				$data = $res->fetchAll(PDO::FETCH_ASSOC);
				if($data){
					echo json_encode(array(
						"ret" => 0,
						"data" => $data
					));
				}else {
					echo json_encode(array(
						"ret" => -1
					));
				}
			break;
		}
    break;
    case 'POST':
        $post = $_POST;
        $count = $conn->query(sprintf("select * from markets where mkid='%s'",$post['mkid']))->fetchColumn();
        if($count>0){
            echo json_encode(array(
                "ret" => 2
            ));
        }else{
            $sql = sprintf("insert into markets(mkid,descp,kind,pos) values('%s','%s',%s,'%s')",$post['mkid'],$post['descp'],$post['kind'],$post['pos']);
			$row=$conn->exec($sql);
			if($row>0){
				echo json_encode(array(
					"ret" => 0
				));
			}else{
				echo json_encode(array(
					"ret" => -1
				));
			}
        }
        
	break;
	case 'PATCH':
		$args=json_decode(file_get_contents('php://input'));
		$sql=sprintf("UPDATE markets SET descp='%s',kind=%s,state=%s,action=%s,pos='%s' WHERE mkid='%s'",$args->descp,$args->kind,$args->state,$args->action,$args->pos,$args->mkid);
		$state=$conn->query($sql);
		if($state===false){
			echo json_encode(array(
				"ret" => -1
			));
		}else{
			echo json_encode(array(
				"ret" => 0
			));
		};
	break;
	case 'DELETE':
		$url=parse_url($_SERVER['QUERY_STRING']);
		$path = $url['path'];
		$mkid = explode('=',$path);
		$mkid = $mkid[1];
		$sql = sprintf("delete from markets where mkid='%s'",$mkid);
		$row=$conn->exec($sql);
		if($row>0){
			echo json_encode(array(
				"ret" => 0
			));
		}else{
			echo json_encode(array(
				"ret" => -1
			));
		}
	break;
}
?>