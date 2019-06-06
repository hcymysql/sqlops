<html>
<head>
    <meta http-equiv="Content-Type"  content="text/html;  charset=UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会员信息统计</title>
    <link rel="stylesheet" href="./slowlog/css/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="./slowlog/css/font-awesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="./slowlog/css/styles.css">
</head>

<body>
<div class="card">
<div class="card-header bg-light">

<?php
    session_start();

    //检测是否登录，若没登录则转向登录界面  
    if(!isset($_SESSION['userid'])){
        header("Location:index.html");
        exit("你还没登录呢。");
    }	

$mysql_server_name='192.168.188.166:3333';
$mysql_username='admin'; 
$mysql_password='wdhcy159753';
$mysql_database='sql_db';

$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("error connecting");
mysql_query("set names 'utf8'"); 
mysql_select_db($mysql_database);

$perNumber=300; //每页显示的记录数  
$page=$_GET['page']; //获得当前的页面值  
$count=mysql_query("select count(*) from login_user"); //获得记录总数  
$rs=mysql_fetch_array($count);   
$totalNumber=$rs[0];  
$totalPage=ceil($totalNumber/$perNumber); //计算出总页数  

$db_count="SELECT * FROM dbinfo WHERE dbname NOT REGEXP 'test'";
$db_result=mysql_query($db_count);
$rowcount=mysql_num_rows($db_result);

?>
<h1>会员信息总计：<?php echo $totalNumber." 人"."&nbsp;&nbsp;&nbsp;数据库总计：".$rowcount."个";  ?></h1>
</div>
<div class="card-body">
<div class="table-responsive">

<?php
if (empty($page)) {  
 $page=1;  
} //如果没有值,则赋值1

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录 

$sql = "select * from login_user limit $startCount,$perNumber";
$result = mysql_query($sql,$conn);

echo "<table class='table table-hover'>";
echo "<tr>	
	        <th>会员id</th>
            <th>登录名</th>
            <th>真实姓名</th>
            <th>邮箱</th>
	        <th>备注</th>";

while($row = mysql_fetch_array($result)) 
{
echo "<tr>";
echo "<td>{$row['id']}</td>";
echo "<td>{$row['user']}</td>";
echo "<td>{$row['real_user']}</td>";
echo "<td>{$row['email']}</td>";
if($row['privilege'] == 0)
{
	echo "<td>开发上线人</br>";	
} else if($row['privilege'] == 1)
{
	echo "<td>业务方审批人</br>";
} else if($row['privilege'] == 2)
{
	echo "<td>安全和内审人</br>";
} else if($row['privilege'] == 3) 
{
	echo "<td>大数据审批人</br>";
}else {
	echo "<td>后台管理员</br>";
}	
echo "</tr>";
}
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
        $htmlstr.="<td> <a href='member.php?page=" . "1" . "'>第一页</a></td>";
        $htmlstr.="<td> <a href='member.php?page=" . ($page-1) . "'>上一页</a></td>";
    }

    $htmlstr.="<td> 总共${totalPage}页</td>";

    for ($i=$startPage;$i<=$endPage; $i++){
         
        $htmlstr.="<td><a href='member.php?page=" . $i . "'>" . $i . "</a></td>";
    }
     
    if ($page<$totalPage){
        $htmlstr.="<td><a href='member.php?page=" . ($page+1) . "'>下一页</a></td>";
        $htmlstr.="<td><a href='member.php?page=" . $totalPage . "'>最后页</a></td>";
 
    }
$htmlstr.="</tr></table>";
echo $htmlstr;

?>


</html>
