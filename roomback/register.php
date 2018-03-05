<?php
header("content-type:text/html;charset=utf-8");
require_once("./connect.php");
$customer_number=$_POST["customer"]['customer_number'];
$customer_password=$_POST["customer"]['customer_password'];
$customer_name=$_POST["customer"]["customer_name"];
$customer_sex=$_POST["customer"]['customer_sex'];

mysqli_query($conn,"SET NAMES UTF8");
$sql0="SELECT * FROM customer WHERE customer_number='$customer_number'";
$res=mysqli_query($conn,$sql0);
$row=mysqli_fetch_assoc($res);
if($customer_number != $row['customer_number']){
	$sql1="INSERT INTO customer (customer_number,customer_password,customer_name,customer_sex) VALUES ('$customer_number', '$customer_password','$customer_name','$customer_sex')";
	mysqli_query($conn,$sql1);
	echo json_encode(1);
}else{
	echo json_encode(2);
}
?>