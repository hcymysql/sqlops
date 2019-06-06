<?php

    session_start();

    //检测是否登录，若没登录则转向登录界面  
    if(!isset($_SESSION['userid'])){
        header("Location:index.html");
        exit("你还没登录呢。");
    }

$id = $_GET['id'];

$con=mysqli_connect("192.168.188.166","admin","wdhcy159753","sql_db");
$sql_get = "select a.ops_order_name,a.ops_content from sql_order_error a  where a.id={$id}";
$result = mysqli_query($con,$sql_get);
$row = mysqli_fetch_array($result);
echo "工单名称：".$row[0]."</br>";
echo "<pre>".$row[1]."</pre></br>";
?>
