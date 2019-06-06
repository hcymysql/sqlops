<?php

    session_start();

    //检测是否登录，若没登录则转向登录界面  
    if(!isset($_SESSION['userid'])){
        header("Location:index.html");
        exit("你还没登录呢。");
    }


$id = $_GET['cancel_id'];
//echo $id."<br>";
$q = isset($_GET['q'])? htmlspecialchars($_GET['q']) : '';
if($q) {
     	if($q =='是') {
     	require 'conn.php';
	$sql = "DELETE FROM sql_order_wait WHERE id={$id}";
	if(mysqli_query($conn,$sql)){
		header("location:my_order.php");
	}
	else{
 	 	echo "修改失败";
	}
	mysqli_close($conn);
	}
else{
	echo "不撤销.</br>";
	header("location:my_order.php");
    }
}

?>
