<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>重置密码</title>
<style>
 
	body{
		font-size:12px;
		font-family:"宋体";
		}
	.bule{ 
	border-color:#ABFEFE;}
	.font1{
		color:#666;}
	
		
</style>
 
 
</head>
 
<script type="text/javascript">
function checkLength(){
var password=document.getElementById("txtpassword"); //获取密码框值
if(password.value.length<6){
alert("密码长度必须大于六位！");
//return 0;
}
}
</script> 

<body>
<table width="800" height="29" border="0" align="center">
  <tr>
    <!--<td width="80" height="29"><img src="img/p1.png" height="29" border="0"></td>-->
     <td bgcolor="#B9EDF7"><b>重置密码</b></td>
  </tr>
</table>
<br><br>
 
 
 
  <table width="800" border="0" align="center">
	<form action="" method="POST">
  <tr>
     <td><b>输入新的密码：</b></td>
     <td>
     <input type="password" name="password" class="bule" id="txtpassword" /></td>
     <td class="font1">最少6个字符</td>
     </tr>
  <tr>
  </tr>
 
  
  <tr>
    <td colspan="3">
  </tr>
  <tr>
  <td colspan="3" align="center"><input name="submit" type="submit" value="提交" onclick="checkLength11();">
  <input type="reset" value="重置"/></td>
  </form>
  </tr>
  <tr>
  <td colspan="3" align="center">&nbsp;</td>
  </tr>
</table>
 
<?php
ini_set('date.timezone','Asia/Shanghai');
$nowtime = date("Y-m-d H:i:s");

//$new_pwd=$_POST['password'];
$array = explode(',',base64_decode($_GET['p']));
$minute=floor((strtotime($nowtime)-strtotime($array[2]))%86400/60);
 
//echo $nowtime."</br>";
//echo $array[2]."</br>";

if($minute >=10){
	die("<p align='center'>你的激活有效期已超过10分钟，请输入你的帐号重新发送激活邮件。</p></br>");
}

if(isset($_POST['submit'])){
	
	$new_pwd=$_POST['password'];
	if(strlen($new_pwd)<6){
		echo "<script>alert('用户密码的长度不得少于6位!请重新输入'); history.back();</script>";
	}

        require 'con.php';
       
	$update_pwd="UPDATE login_user set pwd=MD5('$new_pwd') where user = '{$array[0]}' AND email = '{$array[1]}'";
	//echo $update_pwd."</br>";
	mysqli_query($con,$update_pwd);
        if (mysqli_affected_rows($con) >0){
		echo "<p align='center'>密码已经成功更新！</p></br>";
		echo "<p align='center'>点击此处 <a href='index.html'>登录</a></p></br>";
	} else{
		echo "<p align='center'>密码更新失败！</p></br>";
	}
}
		
?>
	
</body>
</html>
