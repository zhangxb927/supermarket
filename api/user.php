<?php
include 'connection.php';
include 'common.php';
$url=parse_url($_SERVER['QUERY_STRING'])['path'];
$path = explode('&',$url);
if(strstr($url,'&')){
    if(explode('=',$path[0])[0]=='nickname'){
        $nickname = $_GET['nickname'];
        $page = (int)$_GET['page'];
        $start = ($page-1)*10;
        $sql = "select * from users where nickname like '%$nickname%' limit $start,10";
        $res=$conn->query($sql);
        $data=$res->fetchAll(PDO::FETCH_ASSOC);
        $count = $conn->query("select count(*) from users where nickname like '%$nickname%'")->fetchColumn();
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
    }else if(explode('=',$path[0])[0]=='openid'){
        $openid = $_GET['openid'];
        $page = (int)$_GET['page'];
        $start = ($page-1)*10;
        $sql = "select * from sales_record where openid='$openid' limit $start,10";
        $res=$conn->query($sql);
        $data=$res->fetchAll(PDO::FETCH_ASSOC);
        $count = $conn->query("select count(*) from sales_record where openid='$openid'")->fetchColumn();
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
    }   
}else{
    $phone = $_GET['phone'];
    $sql = "select * from users where phone='$phone'";
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
}
?>