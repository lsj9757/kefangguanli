<?php
header("content-type:text/html;charset=utf-8");
require_once("./connect.php");
$type=$_POST["type"];

mysqli_query($conn,"SET NAMES UTF8");

if($type==="1"){
	$room_tablet=$_POST["addroom"]['room_tablet'];
	$room_classification=$_POST["addroom"]['room_classification'];
	$admin_name = $_POST["admin_name"];
	$sql0="SELECT * FROM room WHERE room_tablet='$room_tablet'";
	$res0=mysqli_query($conn,$sql0);
	$row0=mysqli_fetch_assoc($res0);
	if($room_tablet != $row0['room_tablet']){
		$sql1="INSERT INTO room (room_tablet,room_classification,admin_name) VALUES ('$room_tablet', '$room_classification', '$admin_name')";
		mysqli_query($conn,$sql1);
		echo json_encode(1);
	}else{
		echo json_encode(0);
	}
}
if($type==="2"){
	$room_id=$_POST["room_id"];
	$room_tablet=$_POST["editroom"]['room_tablet'];
	$room_classification=$_POST["editroom"]['room_classification'];
	$admin_name = $_POST["admin_name"];
	$sql2="UPDATE room SET room_tablet = '$room_tablet',room_classification = '$room_classification', admin_name = '$admin_name' WHERE room_id = '$room_id'";
	$res2=mysqli_query($conn,$sql2);
	if($res2){
		echo json_encode(2);
	}
}
if($type ==="3"){
	$room_id=$_POST["room_id"];
	$sql3="DELETE FROM room WHERE room_id='$room_id' ";
    $res3=mysqli_query($conn,$sql3);
    if($res3){
		echo json_encode(3);
	}
}


?>