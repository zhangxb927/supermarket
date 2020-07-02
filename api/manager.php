<?php
include 'connection.php';
include 'common.php';
$method=$_SERVER['REQUEST_METHOD'];
switch($method){
    case 'GET':
        $url=parse_url($_SERVER['QUERY_STRING']);
        $path = $url['path'];
        $page = explode('=',$path);
        $page = (int)$page[1];
        $start = ($page-1)*10;
        $sql = "select uid,name,phone,email,mkid,level from administrators limit $start,10";
        $res=$conn->query($sql);
        $data=$res->fetchAll(PDO::FETCH_ASSOC);
        $count = $conn->query("select count(*) from administrators")->fetchColumn();
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
    case 'POST':
        $post = $_POST;
        if(!empty($post['mkid'])){ 
            $res = $conn->query(sprintf("select count(*) from markets where mkid='%s'",$post['mkid']));
            if($res->fetchColumn() < 1){
                echo json_encode(array(
					"ret" => 1
                ));die;
            }
            $ress = $conn->query(sprintf("select kind from markets where mkid='%s'",$post['mkid']))->fetch(PDO::FETCH_ASSOC);
            if($ress['kind']==1){
                echo json_encode(array(
					"ret" => 3
                ));die;
            }
        }
        $sql = sprintf("insert into administrators(password,name,email,phone,level,mkid) values('%s','%s','%s','%s',%s,'%s')",md5($post['password']),$post['name'],$post['email'],$post['phone'],$post['level'],$post['mkid']);
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
        $args=json_decode(file_get_contents('php://input'));
        $sql=sprintf("UPDATE administrators SET name='%s',email='%s',phone='%s',mkid='%s' WHERE uid=%s",$args->name,$args->email,$args->phone,$args->mkid,$args->uid);
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
        $uid = explode('=',$path);
        $uid = $uid[1];
        $sql = sprintf("delete from administrators where uid='%s'",$uid);
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