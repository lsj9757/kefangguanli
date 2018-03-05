<?php
header("content-type:text/html;charset=utf-8");
require_once("./connect.php");

mysqli_query($conn,"SET NAMES UTF8");
$sql="SELECT * FROM log ";
$res=mysqli_query($conn,$sql);
while($row=mysqli_fetch_assoc($res)){
    $rows[]=$row;
  }

echo json_encode($rows);
?>