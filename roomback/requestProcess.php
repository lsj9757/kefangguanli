<?php
header("content-type:text/html;charset=utf-8");
require_once("./connect.php");
$customer_id=$_POST["customer_id"];
$request_type=$_POST["request_type"];
$room_id=$_POST["room_id"];
$admin=$_POST["admin_name"];


mysqli_query($conn,"SET NAMES UTF8");


date_default_timezone_set("Asia/Shanghai"); 
$time=date('y-m-d H:i:s',time());
$preQuery1 = "SELECT * FROM room WHERE room_id = '$room_id'";
$res1 = mysqli_query($conn, $preQuery1);
$row1 = mysqli_fetch_array($res1);
$room_tablet=$row1["room_tablet"];
$room_classification=$row1["room_classification"];
$preQuery2 = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
$res2 = mysqli_query($conn, $preQuery2);
$row2 = mysqli_fetch_array($res2);
$customer_name=$row2["customer_name"];
$customer_number=$row2["customer_number"];


if($request_type == 1){
	$sql[0] = "UPDATE room SET room_state = 1, customer_id = '$customer_id' WHERE room_id = '$room_id'";
	$sql[1] = "UPDATE customer SET customer_state = 1, request_type = '0', request_message = '' ,customer_time= '$time'WHERE customer_id = '$customer_id'";
	$sql[2] ="INSERT INTO log (log_time,log_type,customer_id,customer_number,customer_name,room_id,room_tablet,room_classification,log_admin) VALUES ('$time',1,'$customer_id', '$customer_number','$customer_name','$room_id','$room_tablet','$room_classification','$admin')";
}
elseif($request_type == 2){
	$preQuery = "SELECT * FROM room WHERE customer_id = '$customer_id'";
	$res = mysqli_query($conn, $preQuery);
	$row = mysqli_fetch_array($res);
	$nowroom_id=$row["room_id"];
	$sql[0] = "UPDATE room SET room_state = 0, customer_id = 0 WHERE room_id = '$nowroom_id'";
	$sql[1] = "UPDATE room SET room_state = 1, customer_id = '$customer_id' WHERE room_id = '$room_id'";
	$sql[2] = "UPDATE customer SET customer_state = 1, request_type = '0', request_message = '' ,customer_time= '$time'WHERE customer_id = '$customer_id'";
	$sql[3] ="INSERT INTO log (log_time,log_type,customer_id,customer_number,customer_name,room_id,room_tablet,room_classification,log_admin) VALUES ('$time',2,'$customer_id', '$customer_number','$customer_name','$room_id','$room_tablet','$room_classification','$admin')";
}
elseif($request_type == 3){
	$sql[0] = "UPDATE room SET room_state = 0, customer_id = '0' WHERE room_id = '$room_id'";
	$sql[1] = "UPDATE customer SET customer_state = 0, request_type = '0', request_message = '' ,customer_time=NULL WHERE customer_id = '$customer_id'";
	$sql[2] ="INSERT INTO log (log_time,log_type,customer_id,customer_number,customer_name,room_id,room_tablet,room_classification,log_admin) VALUES ('$time',3,'$customer_id', '$customer_number','$customer_name','$room_id','$room_tablet','$room_classification','$admin')";
}

$bool = true;
for($i=0; $i<count($sql); $i++){
	if(!mysqli_query($conn, $sql[$i])){
		$bool = false;
	}
}

if($bool){
	echo json_encode(1);
}else{
	echo json_encode(0);
}