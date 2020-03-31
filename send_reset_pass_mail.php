<?php

$username = trim($_POST['name']);
$useremail = trim($_POST['email']);
$find_pwd_time = date("Y-m-d H:i:s");
//echo $username."</br>";
//echo $useremail."</br>";

//echo "请回到你的邮箱：".$useremail."查看修改密码步骤。</br></br>";

require 'conn.php';
$sql = "select user,email,pwd from login_user where user = '{$username}' AND email = '{$useremail}'";
$result = mysqli_query($conn,$sql);

if (mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_array($result))
	{
		$user_passwd=$row[2];
	}
} else{ //die("email不存在。请核查。");
	echo "<script>alert('用户名或email不存在！请核查。'); history.back();</script>";
}

$String = base64_encode($username.",".$useremail.",".$find_pwd_time);

system("./mail/sendEmail -f sqlops@126.com -t {$useremail} -s smtp.126.com -u '你的密码找回信' -o message-charset=utf8 -o message-content-type=html -m '请在10分钟内重置密码，过期链接失效。请不要把这个链接告诉别人，你懂的！</br></br><a href='http://sqlops.xxx.com/sqlops_approve/resetUserPass.php?p={$String}'>确认密码找回</a>' -xu sqlops@126.com  -xp '123456'  ");

echo "</br></br>";
echo "请登录你的邮箱：".$useremail."查看修改密码步骤。</br></br>";

?>
