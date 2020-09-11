<?php
ini_set('date.timezone','Asia/Shanghai');
$id = $_GET['update_id'];
//$sql =  $_GET['exec_sql'];
//echo $sql."<br>";
$q = isset($_GET['q'])? htmlspecialchars($_GET['q']) : '';
if($q) {
        if($q =='是') {
        require 'conn.php';
        $result = mysqli_query($conn,"SELECT a.ip,a.dbname,a.user,a.pwd,a.port,b.ops_content,b.is_ddl,b.ops_name FROM dbinfo a join sql_order_wait b on a.dbname = b.ops_db where b.id='".$id ."'");
	while($row = mysqli_fetch_array($result))
	{
  		$ip=$row[0];
  		$db=$row[1];
  		$user=$row[2];
  		$pwd=$row[3];
  		$port=$row[4];
		$ops_content=$row[5];
		$is_ddl=$row[6];
		//$ops_sql=htmlspecialchars(nl2br($row[5]),ENT_QUOTES);
		$ops_sql=nl2br(htmlspecialchars($row[5],ENT_QUOTES));
		$ops_user=$row[7];
		/*echo $ip."<br>";
		echo $db."<br>";
		echo $user."<br>";
		echo $pwd."<br>";
		echo $port."<br>";
		echo $ops_content."<br>";*/
	}
	mysqli_close($conn);
	//上线（客户端请使用MySQL 5.5及以下版本，或者MariaDB10版本。）
	//MySQL 5.6版本会报Using a password on the command line interface can be insecure警告，导致上线失败。
	// --safe-updates
	$dbsql_exec="/usr/bin/mysql --default-character-set=utf8 --skip-column-names -h$ip -u$user -p"."'".$pwd."'"." -P$port $db --execute=\"SET tx_isolation = 'REPEATABLE-READ';START TRANSACTION WITH CONSISTENT SNAPSHOT;SHOW MASTER STATUS;".$ops_content.";COMMIT;SHOW MASTER STATUS;"."\" 2>&1" ;

	//echo $dbsql_exec."</br>";

        ##########上线执行###################
	echo "<br>";
	$exec_result=exec("$dbsql_exec",$output,$return);
	$b=$e=array();
        foreach($output as $v){
		if(preg_match("/mysql.*/",$v)){
			array_push($b,$v);
		} else{
			array_push($e,$v);
		}
        }

	if ($return == 0){
		$input_binlog=$b[0]."\n".$b[1]."\n";
		require 'conn.php';
		$sql = "update sql_order_wait set finish_status=2,binlog_information='{$input_binlog}' WHERE id={$id}";
		//echo $sql;
		if(mysqli_query($conn,$sql)){
			echo "<br>";
			echo "对，就这样，上线成功了！<br>";
			echo "<img src='image/666.jpg'  alt='666' />";
			echo "</br></br>";
		}
		mysqli_close($conn);
		//header("refresh:3;location:wait_order.php");
		if($is_ddl == 1){
			$exec_time=date("Y-m-d H:i:s");
			system("./mail/sendEmail -f chunyang_he@126.com -t sqlops@126.com -s smtp.126.com:25 -u '【通知】{$db}库【Alter】操作已经上线完毕-{$exec_time}' -o message-charset=utf8 -o message-content-type=html -m '上线人：{$ops_user} <br><br> 上线的SQL是：{$ops_sql}<br><br>数据库名：{$db}  <br><br>执行时间：{$exec_time}' -xu sqlops@126.com  -xp '123456' -o tls=no");
		}
    		echo "<br>";
		echo "<a href='my_order.php'>点击返回工单界面</a><br>";
	}
	else{
		 echo "<br>";
		 echo $e[0]."<br>";
      		 echo "<br><h1>上线失败！</h1></br>";
      		 echo "<img src='image/fail.gif'  alt='fail' />";
	}
	#######################################

	/*$sql = "update sql_order_wait set status=1,finish_status=1 WHERE id={$id}";
	if(mysqli_query($con,$sql)){
		header("location:wait_order.php");
	}
	else{
 	 	echo "修改失败";
	}*/
	//mysql_close($con);
	}
else{
	echo "没有执行.</br>";
    }
}

?>
