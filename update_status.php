<?php
$id = $_GET['update_id'];
$login_admin_user = $_GET['login_admin_user'];
$return_status = $_GET['return_status'];
//echo $return_status."<br>";
$q = isset($_GET['q'])? htmlspecialchars($_GET['q']) : '';
if($q) {
        if($q =='是') {
	require 'conn.php';
	$sql = "update sql_order_wait set status=1,finish_status=1,approver=(select real_user from login_user where user='{$login_admin_user}') WHERE id={$id}";
	if(mysqli_query($conn,$sql)){
		$sql_get_mail = "select b.email,a.ops_order,a.ops_order_name,b.real_user,a.ops_db from sql_order_wait a join login_user b on a.ops_name = b.user where a.id={$id}";
		$result = mysqli_query($conn,$sql_get_mail);
		while($row = mysqli_fetch_array($result)){
			$email_order=$row[0];
			$ops_order=$row[1];
			$ops_order_name=$row[2];
			$real_user=$row[3];
			$ops_db=$row[4];
			
		}
	system("./mail/sendEmail -f chunyang_he@126.com -t {$email_order}  -s smtp.126.com:25 -u '工单号：{$ops_order} 已经完成审批，请执行SQL.' -o message-charset=utf8 -o message-content-type=html -m '工单号：{$ops_order} <br>工单名称：{$ops_order_name} <br>  工单提交人：{$real_user} <br>  数据库名：{$ops_db} <br> 请登录<a href='http://sqlops.xxx.com/sqlops_approve/'>SQL自动审核平台</a>执行SQL！' -xu chunyang_he@126.com  -xp '123456' -o tls=no");
		if($return_status==0){
			header("location:wait_order.php");
		} else {
			header("location:finish_order.php");
		}
	}	
	else{
 	 	echo "修改失败";
	}
	mysqli_close($conn);
	}
else{
	require 'conn.php';
	$sql_no = "update sql_order_wait set status=2,finish_status=0,approver=(select real_user from login_user where user='{$login_admin_user}') WHERE id={$id}";
	if(mysqli_query($conn,$sql_no)){
		echo "不审批.</br>";		
		header("location:wait_order.php");
	}
    }
}
mysqli_close($conn);
?>

