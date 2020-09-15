<html>                       
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<head>                  
<link rel="stylesheet" href="./css/bootstrap.min.css">
</head>
</html>

<?php

/*
	这里须要调用MariaDB的mysql客户端，因为MySQL5.6高版本会抛出警告，命令行里带密码不安全，会影响SQL语言检测结果。
	mysql: [Warning] Using a password on the command line interface can be insecure.
*/

if(preg_match("/^select|^insert|^update|^delete|^replace/i",trim($multi_key_sql[$x]))){
   $dbsql_exec="/usr/local/mariadb/bin/mysql --default-character-set=utf8 --skip-column-names -h$ip -u$user -p"."'".$pwd."'"." -P$port $db --execute=\"EXPLAIN  ${multi_sql[$x]};\" 2>&1";}

if(preg_match("/^create/i",trim($multi_key_sql[$x]))){
    $create_temp=preg_replace("/^create/i","CREATE TEMPORARY",trim($multi_key_sql[$x]));
    $dbsql_exec="/usr/local/mariadb/bin/mysql --default-character-set=utf8 --skip-column-names -h$ip -u$user -p"."'".$pwd."'"."  -P$port mysql --execute=\"${create_temp};\" 2>&1";
}

if(preg_match("/^alter/i",trim($multi_key_sql[$x]))){
   $dbsql_exec="/usr/local/mariadb/bin/mysql --default-character-set=utf8 --skip-column-names -h$ip -u$user -p"."'".$pwd."'"."  -P$port mysql --execute=\"${multi_sql[$x]};\" 2>&1";
}

$exec_result=exec("$dbsql_exec",$output,$return);
//print_r($output);
//echo $dbsql_exec."</br>";

if(!empty($multi_key_sql[$x])){
        if(preg_match("/^alter/i",trim($multi_key_sql[$x]))){
		if(preg_match("/ERROR 1064|ERROR 1146/",$output[0])){
			echo "&nbsp;&nbsp;&nbsp;<h3><span class='badge badge-danger'>检测出SQL语句有错误，报错信息如下：</span></h3>";
			echo "<b>&nbsp;&nbsp;&nbsp;".$output[0]."</b></br>";
			array_splice($output, 0, count($output));
			exit;
		}
	}
	if (!preg_match("/^alter/i",trim($multi_key_sql[$x])) && $return == 1){
		echo "&nbsp;&nbsp;&nbsp;<h3><span class='badge badge-danger'>检测出SQL语句有错误，报错信息如下：</span></h3>";
		echo "<b>&nbsp;&nbsp;&nbsp;".$output[0]."</b></br>";
		array_splice($output, 0, count($output));
		exit;
   	}
}

array_splice($output, 0, count($output));

?>
