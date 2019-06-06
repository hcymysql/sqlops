<?php 
    header("Content-Type: text/html; charset=utf8");

    if(!isset($_POST['submit'])){
        exit("错误执行");
    }//判断是否有submit操作

    $name=$_POST['name'];//post获取表单里的name
    $password=$_POST['password'];//post获取表单里的password
    $realname=$_POST['realname'];
    $email=$_POST['email'];

    require 'conn.php';
    $sign_up="insert into login_user(user,real_user,email,pwd) values ('$name','$realname','$email',MD5('$password'))";//向数据库插入表单传来的值的sql
    //echo $sign_up."<br>";
    $reslut=mysqli_query($conn,$sign_up);//执行sql
    
    if (!$reslut){
        die('Error: ' . mysqli_error($conn));//如果sql执行失败输出错误
    }else{
        echo "注册成功<br><br>";//成功输出注册成功
	echo '点击此处 <a href="index.html">返回</a> 登录页面！<br />';

    }

    

    mysqli_close($conn);//关闭数据库

?>
