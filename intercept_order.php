<!doctype html>
<html class="x-admin-sm">
<head>
    <meta http-equiv="Content-Type"  content="text/html;  charset=UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="60" />
    <title>触碰规则拦截SQL工单汇总</title>

<style type="text/css">
a:link { text-decoration: none;color: #3366FF}
a:active { text-decoration:blink;color: green}
a:hover { text-decoration:underline;color: #6600FF}
a:visited { text-decoration: none;color: green}
</style> 

    <script type="text/javascript" src="xadmin/js/jquery-3.3.1.min.js"></script>
    <script src="xadmin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="xadmin/js/xadmin.js"></script>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./slowlog/css/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="./slowlog/css/font-awesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="./slowlog/css/styles.css">
</head>


<?php
session_start();
$prvi = $_SESSION['prvi'];
$login_user=$_SESSION['username'];

    if($prvi==0){
        echo "<br><h1>&nbsp;&nbsp;非法访问！你没有权限审批工单！</h1><br>";
        //echo '<meta http-equiv="Refresh" content="2;url=my_order.php"/>';
	exit;
    }
?>

<body>
<div class="card">
<div class="card-header bg-light">
    <h1>系统拦截工单查询</h1>
</div>

<div class="card-body">
<div class="table-responsive">

<?php	

require 'conn.php';

$perNumber=50; //每页显示的记录数  
$page=$_GET['page']; //获得当前的页面值  
$count=mysqli_query($conn,"select count(*) from sql_order_error"); //获得记录总数  
$rs=mysqli_fetch_array($count);   
$totalNumber=$rs[0];  
$totalPage=ceil($totalNumber/$perNumber); //计算出总页数  

if (empty($page)) {  
 $page=1;  
} //如果没有值,则赋值1

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录 

$sql1 = "select user from login_user where user = '${login_user}' and privilege in(1,2,3,100)";
mysqli_query($conn,"set names utf8");
$result1 = mysqli_query($conn,$sql1);
if (mysqli_num_rows($result1) > 0) {
	$sql ="select a.*,b.real_user from sql_order_error a join login_user b on a.ops_name = b.user order by id DESC limit $startCount,$perNumber";
}
$result = mysqli_query($conn,$sql);

echo "<table style='table-layout:fixed;width:100%;font-size:15px;' class='table table-bordered'>";
echo "<tr>	
	    <th style='width:10%'>工单号</th>
            <th>申请人</th>
            <th>数据库名</th>
            <th>申请时间</th>
	    <th>工单名称</th>
	    <th>申请原因</th>
	    <th>审批结果</th>";

while($row = mysqli_fetch_array($result)) 
{
$exec_status = $row['status'];
$exec_status_second = $row['status_second'];
$exec_finish_status = $row['finish_status'];
$exec_finish_status_second = $row['finish_status_second'];
echo "<tr>";
echo "<td>{$row['ops_order']}</td>";
echo "<td>{$row['real_user']}</td>";
echo "<td>{$row['ops_db']}</td>";
echo "<td>{$row['ops_time']}</td>";
echo "<td><a href='javascript:void(0);' onclick=\"x_admin_show('工单内容详细信息','sql_statement_error.php?id={$row['id']}')\">{$row['ops_order_name']}</a></td>";
echo "<td>{$row['ops_reason']}</td>";
echo "<td><span class='badge badge-danger'>被系统拦截</span></td>";
echo "</tr>";
}//end while
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";

$maxPageCount=10; 
$buffCount=2;
$startPage=1;
 
if  ($page< $buffCount){
    $startPage=1;
}else if($page>=$buffCount  and $page<$totalPage-$maxPageCount  ){
    $startPage=$page-$buffCount+1;
}else{
    $startPage=$totalPage-$maxPageCount+1;
}
 
$endPage=$startPage+$maxPageCount-1;
 
 
$htmlstr="";
 
$htmlstr.="<table class='bordered' border='1' align='center'><tr>";
    if ($page > 1){
        $htmlstr.="<td> <a href='intercept_order.php?page=" . "1" . "'>第一页</a></td>";
        $htmlstr.="<td> <a href='intercept_order.php?page=" . ($page-1) . "'>上一页</a></td>";
    }

    $htmlstr.="<td> 总共${totalPage}页</td>";

    for ($i=$startPage;$i<=$endPage; $i++){
         
        $htmlstr.="<td><a href='intercept_order.php?page=" . $i . "'>" . $i . "</a></td>";
    }
     
    if ($page<$totalPage){
        $htmlstr.="<td><a href='intercept_order.php?page=" . ($page+1) . "'>下一页</a></td>";
        $htmlstr.="<td><a href='intercept_order.php?page=" . $totalPage . "'>最后页</a></td>";
 
    }
$htmlstr.="</tr></table>";
echo $htmlstr;

?>


</html>
