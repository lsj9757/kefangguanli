<?php
header("content-type:text/html;charset=utf-8");
require_once("./connect.php");
$user=addslashes($_POST["login"]['user']);
$password=addslashes($_POST["login"]['password']);
$sql0="SELECT * FROM customer WHERE customer_number='$user' and customer_password='$password'";
$ret0 = mysqli_query($conn,$sql0);
$row0 = mysqli_fetch_assoc($ret0);
	if($row0){
		echo json_encode(1);
	}else{
		$sql1="SELECT * FROM admin WHERE admin_name='$user' and admin_password='$password'";
		$ret1 = mysqli_query($conn,$sql1);
		$row1 = mysqli_fetch_assoc($ret1);
		if($row1){
			echo json_encode(2);
		}else{
			echo json_encode(0);
		}
	}
?>