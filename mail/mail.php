<?php

class mail{
	private $order_id;
	private $dev_user;
	private $dev_user_mail;
	private $sql_order;
	private $dbname;

	function __construct($order_id,$dev_user,$dev_user_mail,$sql_order,$dbname){
		$this->order_id = $order_id;
		$this->dev_user = $dev_user;
		$this->dev_user_mail = $dev_user_mail;
		$this->sql_order = $sql_order;
		$this->dbname = $dbname;
	}
	
	function execCommand(){
		//$SUBJECT_NAME=`echo -n 数据运维工单处理提醒 | base64`;
		//$SUBJECT="=?utf-8?b?{$SUBJECT_NAME}?=";
		$SUBJECT='数据运维工单处理提醒';
		system("./mail/sendEmail -f chunyang_he@126.com -t {$this->dev_user_mail} -cc chunyang_he@126.com -s smtp.126.com:25 -u '数据运维工单处理提醒' -o message-charset=utf8 -u '$SUBJECT' -o message-content-type=html -m '工单号：{$this->order_id} <br>工单名称：{$this->sql_order} <br>工单提交人：{$this->dev_user} <br>数据库名：{$this->dbname} <br>需要您进行审核及实施，请登录<a href='http://sqlops.xxx.com/sqlops_approve/'>SQL自动审核平台</a>查看并处理！' -xu chunyang_he@126.com -xp '123456' -o tls=no");
	}
}

?>
