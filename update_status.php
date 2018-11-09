<?php
$id = $_GET['update_id'];
$login_admin_user = $_GET['login_admin_user'];
//echo $login_admin_user."<br>";
$q = isset($_GET['q'])? htmlspecialchars($_GET['q']) : '';
if($q) {
        if($q =='是') {
        $con=mysqli_connect("192.168.199.199","admin","123456","sql_db","3306");
	$sql = "update sql_order_wait set status=1,finish_status=1,approver='{$login_admin_user}' WHERE id={$id}";
	if(mysqli_query($con,$sql)){
		$sql_get_mail = "select b.email,a.ops_order,a.ops_order_name from sql_order_wait a join login_user b on a.ops_name = b.user where a.id={$id}";
		$result = mysqli_query($con,$sql_get_mail);
		while($row = mysqli_fetch_array($result)){
			$email_order=$row[0];
			$ops_order=$row[1];
			$ops_order_name=$row[2];
		}
		require 'mail/mail.php';
		$sendmail = new mail($ops_order,'',$email_order,$ops_order_name,'');
		$sendmail->noticeReply();
		header("location:wait_order.php");
	}
	else{
 	 	echo "修改失败";
	}
	//mysqli_close($con);
	}
else{
	$con=mysqli_connect("192.168.199.199","admin","123456","sql_db","3306");
	$sql_no = "update sql_order_wait set status=2,finish_status=0,approver='{$login_admin_user}' WHERE id={$id}";
	if(mysqli_query($con,$sql_no)){
		echo "不审批.</br>";		
		header("location:wait_order.php");
	}
    }
}
//mysqli_close($con);
?>
