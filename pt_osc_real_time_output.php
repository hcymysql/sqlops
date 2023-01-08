<?php

ini_set('date.timezone','Asia/Shanghai');
$id = $_GET['update_id'];
$q = isset($_GET['q'])? htmlspecialchars($_GET['q']) : '';


echo "<big><font color='#FF0000'>表结构正在变更，请耐心等待，期间不要关闭浏览器！执行进度会自动刷新。</font></big></br></br>";

function liveExecuteCommand($cmd) 
{ 

    while (@ ob_end_flush()); // end all output buffers if any 

    $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r'); 

    $live_output  = ""; 
    $complete_output = ""; 

    while (!feof($proc)) 
    { 
     $live_output  = fread($proc, 4096); 
     $complete_output = $complete_output . $live_output; 
     $live_output = str_replace("\n","<br>",$live_output);
     echo "$live_output"."<br>"; 
     @ flush(); 
    } 

    pclose($proc); 

    // get exit status 
    preg_match('/[0-9]+$/', $complete_output, $matches); 

    // return exit status and intended output 
    return array (
        'exit_status' => intval($matches[0]), 
        'output'  => str_replace("Exit status : " . $matches[0], '', $complete_output) 
       ); 
}

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
		$table_tmp = explode(' ',$ops_content);
		$table = $table_tmp[2];
		$sql_cut_tmp = array_slice($table_tmp,3);
		$sql_cut_tmp2 = implode(' ',$sql_cut_tmp);
		$sql_cut = str_ireplace(';','',$sql_cut_tmp2);
		$is_ddl=$row[6];
		$ops_sql=nl2br(htmlspecialchars($row[5],ENT_QUOTES));
		$ops_user=$row[7];
	}

	$result = liveExecuteCommand("/usr/bin/env pt-online-schema-change --host={$ip} --user={$user} --password='{$pwd}' --port={$port}  --alter='{$sql_cut}' D={$db},t={$table}   --no-check-replication-filters --recursion-method=none  --charset=utf8 --max-load='Threads_running=200' --critical-load='Threads_running=200'  --execute");

	if($result['exit_status'] === 0){ 
		echo "<big><font color='#FF0000'>执行命令已经变更完毕。</font></big></br>"; 
		$sql = "update sql_order_wait set finish_status=2 WHERE id={$id}";
		if(mysqli_query($conn,$sql)){
			echo "<br>";
			echo "对，就这样，上线成功了！<br>";
			echo "<img src='image/666.jpg'  alt='666' />";
			echo "</br></br>";
		}
		mysqli_close($conn);
    		echo "<br>";
		echo "<a href='my_order.php'>点击返回工单界面</a><br>";		
	} else { 
		echo "<big><font color='#FF0000'>执行出错。</font></big></br></br>";
	} 
}  else{
		echo "没有执行.</br></br>";
	}
}

?> 
