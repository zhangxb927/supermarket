<?php
include 'connection.php';
include 'common.php';
$url=parse_url($_SERVER['QUERY_STRING'])['path'];
if(strstr($url,'&')){
    $array = explode('&',$url);
    $mkid = explode('=',$array[0])[1];
    $logo = explode('=',$array[1])[1];
    $sql = "select * from sales_record where mkid='$mkid'";
}else{
    $logo = explode('=',$url)[1];
    $sql = "select * from sales_record";
}
$total_data = array();
// $arr = array('gid'=>'01010001','gname'=>'溜溜梅60g','count'=>1);
// array_push($total_data,$arr);
$res=$conn->query($sql);
$data=$res->fetchAll(PDO::FETCH_ASSOC);
switch($logo){
    case 'count':
        for($i=0;$i<count($data);$i++){
            $bool = false;
            for($j=0;$j<count($total_data);$j++){
                if($data[$i]['gid']==$total_data[$j]['gid']){
                    $bool = true;
                    $total_data[$j]['count']=$data[$i]['count']+$total_data[$j]['count'];
                }
            }
            if(!$bool){
                $array = array('gid'=>$data[$i]['gid'],'gname'=>$data[$i]['gname'],'count'=>$data[$i]['count'],'price'=>$data[$i]['price']);
                array_push($total_data,$array);
            }
        }
        $finaly_data = array();
        for($i=0;$i<count($total_data);$i++){
            if(count($finaly_data)==0){
                array_push($finaly_data,$total_data[$i]);
            }else if(count($finaly_data)==1){
                if($total_data[$i]['count']>$finaly_data[0]['count']){
                    array_unshift($finaly_data,$total_data[$i]);//把元素插入到数组开头
                }else{
                    array_push($finaly_data,$total_data[$i]);//把元素插入到数组末尾
                }
            }else{
                $bool = false;
                $count = count($finaly_data)-1;
                for($j=0;$j<$count;$j++){
                    $k = $j+1;
                    if($total_data[$i]['count']<=$finaly_data[$j]['count'] && $total_data[$i]['count']>$finaly_data[$k]['count']){
                        $bool = true;
                        $array = array(0=>$total_data[$i]);//必须将此数组赋值为一个数组的一项，否则数组的每一项均作为一项插入
                        array_splice($finaly_data, $j+1, 0, $array);
                        break 1;
                    }
                }
                if(!$bool){
                    array_push($finaly_data,$total_data[$i]);
                }
            }
            
        }
        $finaly_data = array_splice($finaly_data, 0, 5);
        echo json_encode(array(
            "ret" => 0,
            "data" => $finaly_data
        ));
    break;
    case 'price':
        for($i=0;$i<count($data);$i++){
            $bool = false;
            for($j=0;$j<count($total_data);$j++){
                if($data[$i]['gid']==$total_data[$j]['gid']){
                    $bool = true;
                    $total_data[$j]['subtotal']=$data[$i]['subtotal']+$total_data[$j]['subtotal'];
                }
            }
            if(!$bool){
                $array = array('gid'=>$data[$i]['gid'],'gname'=>$data[$i]['gname'],'subtotal'=>$data[$i]['subtotal'],'price'=>$data[$i]['price']);
                array_push($total_data,$array);
            }
        }
        $finaly_data = array();
        for($i=0;$i<count($total_data);$i++){
            if(count($finaly_data)==0){
                array_push($finaly_data,$total_data[$i]);
            }else if(count($finaly_data)==1){
                if($total_data[$i]['subtotal']>$finaly_data[0]['subtotal']){
                    array_unshift($finaly_data,$total_data[$i]);//把元素插入到数组开头
                }else{
                    array_push($finaly_data,$total_data[$i]);//把元素插入到数组末尾
                }
            }else{
                $bool = false;
                $count = count($finaly_data)-1;
                for($j=0;$j<$count;$j++){
                    $k = $j+1;
                    if($total_data[$i]['subtotal']<=$finaly_data[$j]['subtotal'] && $total_data[$i]['subtotal']>$finaly_data[$k]['subtotal']){
                        $bool = true;
                        $array = array(0=>$total_data[$i]);//必须将此数组赋值为一个数组的一项，否则数组的每一项均作为一项插入
                        array_splice($finaly_data, $j+1, 0, $array);
                        break 1;
                    }
                }
                if(!$bool){
                    array_push($finaly_data,$total_data[$i]);
                }
            }
            
        }
        $finaly_data = array_splice($finaly_data, 0, 5);
        echo json_encode(array(
            "ret" => 0,
            "data" => $finaly_data
        ));
    break;
}
?>