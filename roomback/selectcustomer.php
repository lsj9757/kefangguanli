<?php
header("content-type:text/html;charset=utf-8");
require_once("./connect.php");
$type=$_POST["type"];

mysqli_query($conn,"SET NAMES UTF8");

if($type === "1"){
	$customer_number=$_POST["user"];
	$sql1="SELECT * FROM customer WHERE customer_number='$customer_number'";
	$res1=mysqli_query($conn,$sql1);
	$row1=mysqli_fetch_assoc($res1);
	echo json_encode($row1);
}

if($type === "2"){
	$admin_name=$_POST["user"];
	$sql2="SELECT * FROM customer ";
	$res2=mysqli_query($conn,$sql2);
	while($row2=mysqli_fetch_assoc($res2)){
		$rows2[]=$row2;
	}
	echo json_encode($rows2, JSON_UNESCAPED_UNICODE);
}
if($type === "3"){
	$customer_id=$_POST["customer_id"];
	$sql3="SELECT room_tablet FROM room WHERE customer_id='$customer_id'";
	$res3=mysqli_query($conn,$sql3);
	$row3=mysqli_fetch_assoc($res3);
	echo json_encode($row3);
}
?>