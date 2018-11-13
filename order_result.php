<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>数据库上线工单查询</title>
<link rel="stylesheet" type="text/css" href="css/table.css">
</head>

<?php
session_start();
$prvi = $_SESSION['prvi'];
$login_user=$_SESSION['username'];

    if($prvi==0){
        echo "非法访问！你没有权限审批工单！<br>";
        //echo '<meta http-equiv="Refresh" content="2;url=my_order.php"/>';
	exit;
    }  

$ops_order=$_GET['ops_order'];
$mysql_server_name='192.168.199.199';
$mysql_username='admin'; 
$mysql_password='123456';
$mysql_database='sql_db';

$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting");
mysql_query("set names 'utf8'"); 
mysql_select_db($mysql_database);

$perNumber=50; //每页显示的记录数  
$page=$_GET['page']; //获得当前的页面值  
$count=mysql_query("select count(*) from sql_order_wait"); //获得记录总数  
$rs=mysql_fetch_array($count);   
$totalNumber=$rs[0];  
$totalPage=ceil($totalNumber/$perNumber); //计算出总页数  
/*if (!isset($page)) {  
 $page=1;  
} //如果没有值,则赋值1  */

if (empty($page)) {  
 $page=1;  
} //如果没有值,则赋值1

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录 

$sql1 = "select user from login_user where user = '${login_user}' and privilege in(1,2)";
$result1 = mysql_query($sql1,$conn);
if (mysql_num_rows($result1) > 0) {
	echo "Hi，管理员，等待你审批工单。<br>";
	$sql ="select a.* from sql_order_wait a where a.ops_order=${ops_order} order by id DESC limit $startCount,$perNumber";
}
/*
else{
	$sql ="select a.* from sql_order_wait a join login_user b on a.ops_name = b.user where a.ops_name = '${login_user}'";
}*/
$result = mysql_query($sql,$conn);

echo "<h1 align='center' class='STYLE2'><a href='wait_order.php'>数据库上线工单查询</a></h1>";
echo "<hr />";
/*echo "<form action='order_result.php' method='get'>
  <p align='center'>输入工单号:
    <input type='text' name='ops_order' value=''>
    <input name='submit'type='submit' value='查询' />
  </p>
</form>";*/
echo "<table class='bordered' width='1000px' height='100px' border='1' align='center'>";
//echo "<table width='1000px' height='100px' border='1' align='center'>";
echo "<tr>	
	    <th>工单号</th>
            <th>申请人</th>
            <th>数据库名</th>
            <th>申请时间</th>
	    <th>工单名称</th>
	    <th>审批结果</th>
	    <th>操作</th>
          </tr>";
while($row = mysql_fetch_array($result)) 
{
$status = $row['status']?"<span style=''>已审批</span>":"<a href='update.php?id={$row['id']}&login_user=$login_user&prvi=$prvi'>待审批</a>";
$exec_status = $row['status'];
$exec_finish_status = $row['finish_status'];
echo "<tr>";
echo "<td width='50'>{$row['ops_order']}</td>";
echo "<td width='30' style='word-break:break-all'>{$row['ops_name']}</td>";
echo "<td width='40'>{$row['ops_db']}</td>";
echo "<td width='25'>{$row['ops_time']}</td>";
//echo "<td width='100' onclick=\"document.location.href='sql_statement.php target='_blank''\" style='cursor:pointer;'>{$row['ops_order_name']}</td>";
echo "<td width='100'><a href='sql_statement.php?id={$row['id']}' target='_blank'>".$row['ops_order_name']."</a></td>";
//echo "<td style='word-wrap:break-word'>{$row['ops_content']}</td>";
if($prvi==1 || $prvi==2){
	if($exec_status==0){
		echo "<td width='50'>$status</br></td>";
	}
	if($exec_status==1){
		echo "<td width='50'>$status</br>
		审批人:{$row['approver']}</td>";
	}
	if($exec_status==2){
		echo "<td width='50'>审批不通过</br>
		审批人：</br>{$row['approver']} </td>";
	}
}
else{
	echo "<td width='60'>等待审批中</td>";
}
#######################################################
if($prvi==1){
if($exec_finish_status==1){
	echo "<td width='50'><a href='execute.php?id={$row['id']}'>执行工单</a></td>";
}
else if($exec_finish_status==2){
	echo "<td width='50'>已执行完</br>";
	echo "<a href='rollback.php?id={$row['id']}'>生成反向SQL</a></td>";
}
else{
	echo "<td width='50'>没审批不能执行</td>";
	//echo "<td width='80'><a href='cancel.php?id={$row['id']}'>自行撤销工单</a></td>";
} 
echo "</tr>";
}
}
echo "</table>";

if ($page != 1) { //页数不等于1  
?>  
<a href='wait_order.php?page=<?php echo ($page - 1);?>'>上一页</a><!--显示上一页-->  
<?php  
}  
for ($i=1;$i<=$totalPage;$i++) {  //循环显示出页面  
?> 
<a href="wait_order.php?page=<?php echo ($i);?>"><?php echo $i ;?></a>
<?php  
}  
if ($page<$totalPage) { //如果page小于总页数,显示下一页链接  
?>  
<a href="wait_order.php?page=<?php echo ($page + 1);?>">下一页</a>  
<?php  
}   
?>


</html>
