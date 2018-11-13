<?php

$id = $_GET['id'];

$con=mysqli_connect("192.168.199.199","admin","123456","sql_db");
mysqli_query($con,"set names 'utf8'");
$sql_get = "select a.ops_order_name,a.ops_content from sql_order_wait a  where a.id={$id}";
$result = mysqli_query($con,$sql_get);
$row = mysqli_fetch_array($result);
echo "工单名称：".$row[0]."</br>";
echo "<pre>".$row[1]."</pre></br>";
?>
