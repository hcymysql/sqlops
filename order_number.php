<?php

  @date_default_timezone_set("PRC");
 
  $order_id_main = date('YmdHis');
 
  $order_id_len = strlen($order_id_main);
 
  $order_id_sum = 0;
 
  for($i=0; $i<$order_id_len; $i++){
 
  	$order_id_sum += (int)(substr($order_id_main,$i,1));
 
  }
 
  //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
 
  $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
  //echo "$order_id".PHP_EOL;

?>

