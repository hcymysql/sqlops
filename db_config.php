<?php

//获取开发选择的数据库的配置信息，表结构为dbinfo.sql
$conn=mysqli_connect("192.168.199.199","admin","123456","sql_db","3306");
$result = mysqli_query($conn,"SELECT ip,dbname,user,pwd,port FROM dbinfo where dbname='".$dbname ."'");
while($row = mysqli_fetch_array($result))
{
  $ip=$row[0];
  $db=$row[1];
  $user=$row[2];
  $pwd=$row[3];
  $port=$row[4];
}
mysqli_close($conn);

?>
