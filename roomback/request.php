<?php
header("content-type:text/html;charset=utf-8");
require_once("./connect.php");
$customer_id=$_POST["customer_id"];
$request_type=$_POST["request_type"];
$request_message=json_encode($_POST['request_message'], JSON_UNESCAPED_UNICODE);

mysqli_query($conn,"SET NAMES UTF8");
$sql="UPDATE customer SET request_message = '$request_message',request_type = '$request_type'WHERE customer_id = '$customer_id'";
$res=mysqli_query($conn,$sql);
if($res){
	echo json_encode(1);
}else{
	echo json_encode(0);
}