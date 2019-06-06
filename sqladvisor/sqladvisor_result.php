<?php

$get_dbname=$_POST['select_yourdb'];
$get_sql=$_POST['input_yoursql'];

    if(empty($get_dbname)){
	header("Location:sqladvisor.php");
    }

$con = mysqli_connect("192.168.188.166","admin","wdhcy159753","sql_db","3333") or die("数据库链接错误".mysql_error());
mysqli_query($con,"set names utf8");
$get_db_ip="select ip,dbname,user,pwd,port from dbinfo where dbname='${get_dbname}'";
$result = mysqli_query($con,$get_db_ip);      
list($ip,$dbname,$user,$pwd,$port) = mysqli_fetch_array($result);

$sql_advisor_export="echo '$get_sql'";
$html_str=system("$sql_advisor_export | ../slowlog/soar/soar -online-dsn='${user}:${pwd}@${ip}:${port}/${dbname}' -test-dsn='admin:wdhcy159753@192.168.188.166:3333/test' -report-type='html' -explain=true -log-output=./soar_advisor.log");
	
echo $html_str."</br>";
echo "<hr />";

?>
