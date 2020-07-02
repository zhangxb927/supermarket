<?php
include 'connection.php';
include 'common.php';
$method=$_SERVER['REQUEST_METHOD'];
switch($method){
    case 'GET':
        $url=parse_url($_SERVER['QUERY_STRING'])['path'];
        $parameter = explode('=',$url)[0];
        switch($parameter){
            case 'page':
                $page = (int)explode('=',$url)[1];
                $start = ($page-1)*10;
				$sql = "select * from all_goods limit $start,10";
				$res=$conn->query($sql);
				$data=$res->fetchAll(PDO::FETCH_ASSOC);
				$count = $conn->query("select count(*) from all_goods")->fetchColumn();
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
            case 'gid':
                $gid = explode('=',$url)[1];
                $sql = sprintf("select * from all_goods where gid='%s'",$gid);
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
            case 'name':
                $name = $_GET['name'];
                $sql = "select * from all_goods where name like '%$name%'";
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
    case 'POST':
        $datas = json_decode(file_get_contents('php://input'));
        for($i=0;$i<count($datas);$i++){
            $arg = $datas[$i];
            $mkid = $arg->mkid;
            $gid = $arg->gid;
            $res = $conn->query(sprintf("select * from goods where mkid='%s' and gid='%s'",$mkid,$gid))->fetch(PDO::FETCH_ASSOC);
            if($res){
                $id = $res["id"];
                $count = $res["count"]+$arg->count;
                $sql = sprintf("update goods set count=%s where id=%s",$count,$id);
                $state = $conn->query($sql);
                if($state===false){
                    echo json_encode(array(
                        "ret" => -1
                    ));die;
                }
            }else{
                $sql = sprintf("insert into goods(mkid,gid,name,price,sprice,count,type,gen_time,expiration_date,expire_date) values('%s','%s','%s',%s,%s,%s,%s,'%s',%s,'%s')",$arg->mkid,$arg->gid,$arg->name,$arg->price,$arg->sprice,$arg->count,$arg->type,$arg->gen_time,$arg->expiration_date,$arg->expire_date);
                $state = $conn->exec($sql);
                if($state<=0){
                    echo json_encode(array(
                        "ret" => -1
                    ));die;
                }  
            }
        }
        echo json_encode(array(
            "ret" => 0
        ));
    break;
}
?>