<?php
include 'connection.php';
include 'common.php';
date_default_timezone_set('prc');
$method=$_SERVER['REQUEST_METHOD'];
switch($method){
    case 'POST':
        $post = $_POST;
        $count = $conn->query(sprintf("select * from markets where mkid='%s'",$post['mkid']))->fetchColumn();
        if($count<1){
            echo json_encode(array(
                "ret" => 1
            ));die;
        }
        $number = $conn->query(sprintf("select * from all_goods where gid='%s'",$post['gid']))->fetchColumn();
        if($number>0){
            echo json_encode(array(
                "ret" => 4
            ));die;
        }
        $expire_date = '';      
        if(!empty($post['gen_time'])&&!empty($post['expiration_date'])){
            $second = $post['expiration_date']*24*60*60;
            $expire_date = strtotime($post['gen_time'])+$second;
            $expire_date = date('Y-m-d H:i:s',$expire_date);
        }
        $sql = sprintf("insert into goods(mkid,gid,name,price,sprice,type,gen_time,expiration_date,expire_date) values('%s','%s','%s',%s,%s,%s,'%s',%s,'%s')",$post['mkid'],$post['gid'],$post['name'],$post['price'],$post['sprice'],$post['type'],$post['gen_time'],$post['expiration_date'],$expire_date);
        $row=$conn->exec($sql);
        if($row>0){
            $sqll = sprintf("insert into all_goods(gid,name,price,sprice,type,gen_time,expiration_date,expire_date) values('%s','%s',%s,%s,%s,'%s',%s,'%s')",$post['gid'],$post['name'],$post['price'],$post['sprice'],$post['type'],$post['gen_time'],$post['expiration_date'],$expire_date);
            $res = $conn->exec($sqll);
            if($res>0){
                echo json_encode(array(
                    "ret" => 0
                ));
            }else{
                echo json_encode(array(
                    "ret" => -1
                ));
            }         
        }else{
            echo json_encode(array(
                "ret" => -1
            ));
        }
    break;
    case 'GET':
        $url=parse_url($_SERVER['QUERY_STRING'])['path'];
        if(strstr($url,'&')){
            $path = explode('&',$url);
            if(explode('=',$path[0])[0]=='page'){
                $page = (int)explode('=',$path[0])[1];
                $mkid = explode('=',$path[1])[1];
                $start = ($page-1)*10;
				$sql = "select * from goods where count!=0 and mkid='$mkid' limit $start,10";
				$res=$conn->query($sql);
				$data=$res->fetchAll(PDO::FETCH_ASSOC);
                $count = $conn->query("select count(*) from goods where count!=0 and mkid='$mkid'")->fetchColumn();
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
                die;
            }
            $mkid = explode('=',$path[0])[1];
            $type = explode('=',$path[1])[0];
            if($type=='gid'){
                $gid = explode('=',$path[1])[1];
                if($mkid==0){
                    $sql = sprintf("select * from goods where gid='%s'",$gid);
                }else{
                    $sql = sprintf("select * from goods where mkid='%s' and gid='%s'",$mkid,$gid);
                }
            }else if($type=='name'){
                $name = $_GET['name'];
                if($mkid==0){
                    $sql = "select * from goods where name like '%$name%'";
                }else{
                    $sql = "select * from goods where name like '%$name%' and mkid='$mkid'";
                }
            }
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
				$sql = "select * from goods where count!=0 limit $start,10";
				$res=$conn->query($sql);
				$data=$res->fetchAll(PDO::FETCH_ASSOC);
				$count = $conn->query("select count(*) from goods where count!=0")->fetchColumn();
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
                $mkid = explode('=',$url)[1];
                $sql = sprintf("select * from goods where mkid='%s' and count!=0",$mkid);
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
            break;
        }
    break;
    case 'DELETE':
        $path=parse_url($_SERVER['QUERY_STRING'])['path'];
        $id = explode('=',$path)[1];
        $sql = sprintf("delete from goods where id=%s",$id);
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
    case 'PATCH':
        $url=parse_url($_SERVER['QUERY_STRING'])['path'];
        if(strstr($url,'&')){
            $path = explode('&',$url);
            $id = explode('=',$path[0])[1];
            $count = explode('=',$path[1])[1];
            $sql = sprintf("update goods set count=%s where id=%s",$count,$id);
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
            die;
        }
        $args=json_decode(file_get_contents('php://input'));
        $gen_time = $args->gen_time;
        $second = ($args->expiration_date)*24*60*60;
        $expire_date = strtotime($gen_time)+$second;
        $expire_date = date('Y-m-d H:i:s',$expire_date);
        $sql=sprintf("UPDATE goods SET name='%s',sprice=%s,price=%s,type=%s,gen_time='%s',expiration_date=%s,expire_date='%s' WHERE id=%s",$args->name,$args->sprice,$args->price,$args->type,$gen_time,$args->expiration_date,$expire_date,$args->id);
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
}
?>