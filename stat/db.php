<?php
    $mysql_server_name='192.168.199.199'; 
    $mysql_username='admin'; 
    $mysql_password='123456'; 
    $mysql_database='sql_db';
  $conn=mysqli_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database,"3306") or die("error connecting") ;
  mysqli_query($conn,"set names 'utf8'"); 
  $result = mysqli_query($conn,"select ops_db,count(*) as count from sql_order_wait group by ops_db");
  $data="";
  $array= array();
  class User{
    public $ops_db;
    public $count;
  }
  while($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
    $user=new User();
    $user->ops_db = $row['ops_db'];
    $user->count = $row['count'];
    $array[]=$user;
  }
  $data=json_encode($array);
  // echo "{".'"user"'.":".$data."}";
  echo $data;
?>
