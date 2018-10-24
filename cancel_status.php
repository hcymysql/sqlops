<?php
$id = $_GET['cancel_id'];
//echo $id."<br>";
$q = isset($_GET['q'])? htmlspecialchars($_GET['q']) : '';
if($q) {
        if($q =='是') {
        $con=mysqli_connect("192.168.199.199","admin","123456","sql_db");
	$sql = "DELETE FROM sql_order_wait WHERE id={$id}";
	if(mysqli_query($con,$sql)){
		header("location:my_order.php");
	}
	else{
 	 	echo "修改失败";
	}
	mysqli_close($con);
	}
else{
	echo "不撤销.</br>";
	header("location:my_order.php");
    }
}

?>
