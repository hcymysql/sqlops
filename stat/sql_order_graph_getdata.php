<?php

//function index($arr1){
function index(){
    ini_set('date.timezone','Asia/Shanghai');

    require '../conn.php';
    $get_info="select DATE_FORMAT(ops_time,'%Y-%m') AS stat_month,count(*) as counts from sql_order_wait where ops_time >=concat(DATE_FORMAT(now(),'%Y'),'-01-01') group by DATE_FORMAT(ops_time,'%Y-%m')";
	
    $result1 = mysqli_query($conn,$get_info);

  $array= array();
  class Connections{
    public $stat_month;
    public $counts;
  }
  while($row = mysqli_fetch_array($result1,MYSQLI_ASSOC)){
    $cons=new Connections();
    $cons->stat_month = $row['stat_month'];
    //$user->user_max = $row['user_max'];
    $cons->counts = $row['counts'];
    $array[]=$cons;
  }
  $top_data=json_encode($array);
  // echo "{".'"user"'.":".$data."}";
 echo $top_data;
}

/*$fn = isset($_GET['fn']) ? $_GET['fn'] : 'main';
if (function_exists($fn)) {
  call_user_func($fn);
}
*/

    //$ip = $_GET['ip'];
    //$dbname = $_GET['dbname'];
    //$port = $_GET['port'];

//index($ip,$dbname,$port);
index();


?>

