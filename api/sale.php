<?php
include 'connection.php';
include 'common.php';
date_default_timezone_set('prc');
$method=$_SERVER['REQUEST_METHOD'];
switch($method){
    case 'GET':
        $url=parse_url($_SERVER['QUERY_STRING'])['path'];
        if(strstr($url,'&')){
            $array = explode('&',$url);
            if(count($array)==2){
                $mkid = $_GET['mkid'];
                $page = (int)$_GET['page'];
                $start = ($page-1)*10;
                $sql = "select * from sales_record where mkid='$mkid' limit $start,10";
                $res=$conn->query($sql);
                $data=$res->fetchAll(PDO::FETCH_ASSOC);
                $count = $conn->query("select count(*) from sales_record where mkid='$mkid'")->fetchColumn();
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
            }else if(count($array)==3){
                $s_time = explode('=',$array[0])[1];
                $e_time = explode('=',$array[1])[1];
                $page = (int)explode('=',$array[2])[1];
                $start = ($page-1)*10;
                $sql = sprintf("select * from sales_record where time>=%s and time<=%s limit $start,10",$s_time,$e_time);
                $count = $conn->query("select count(*) from sales_record where time>=$s_time and time<=$e_time")->fetchColumn();
            }else if(count($array)==4){
                $mkid = explode('=',$array[0])[1];
                $s_time = explode('=',$array[1])[1];
                $e_time = explode('=',$array[2])[1];
                $page = (int)explode('=',$array[3])[1];
                $start = ($page-1)*10;
                $sql = sprintf("select * from sales_record where mkid='%s' and time>=%s and time<=%s limit $start,10",$mkid,$s_time,$e_time);
                $count = $conn->query("select count(*) from sales_record where mkid='$mkid' and time>=$s_time and time<=$e_time")->fetchColumn();
            }
            $res=$conn->query($sql);
            $data=$res->fetchAll(PDO::FETCH_ASSOC);
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
        $parameter = explode('=',$url)[0];
        switch($parameter){
            case 'page':
                $page = (int)explode('=',$url)[1];
                $start = ($page-1)*10;
                $sql = "select * from sales_record limit $start,10";
                $res=$conn->query($sql);
                $data=$res->fetchAll(PDO::FETCH_ASSOC);
                $count = $conn->query("select count(*) from sales_record")->fetchColumn();
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
                $sql = sprintf("select * from sales_record where mkid='%s'",$mkid);
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
        
}
?>