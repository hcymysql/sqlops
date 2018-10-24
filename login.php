    <?php  
    //登录  
    if(!isset($_POST['submit'])){  
        //exit('非法访问!'); 
	echo "非法访问！<br>";
	echo '<meta http-equiv="Refresh" content="3;url=login.php"/>';
    }  
    $username = $_POST['username'];  
    $password = $_POST['password'];  
      
    //包含数据库连接文件  
    include('conn.php');  
    //检测用户名及密码是否正确  
    $check_query = mysqli_query($conn,"select id,privilege,email from login_user where user='$username' and pwd=MD5('$password') limit 1");  
    if($result = mysqli_fetch_array($check_query)){  
        //登录成功  
        session_start();  
        $_SESSION['username'] = $username;  
        $_SESSION['userid'] = $result['id'];  
	$_SESSION['prvi'] = $result['privilege'];
	$_SESSION['user_email'] = $result['email'];
        echo $username,' 欢迎你！进入 <a href="main.php">用户中心</a><br />';  
        echo '点击此处 <a href="login.php?action=logout">注销</a> 登录！<br />'; 
	header("Location:main.php");  
        exit;  
    } else {  
        exit('登录失败！点击此处 <a href="javascript:history.back(-1);">返回</a> 重试');  
    }  
      
      
      
    //注销登录  
    if($_GET['action'] == "logout"){  
        unset($_SESSION['userid']);  
        unset($_SESSION['username']);  
        echo '注销登录成功！点击此处 <a href="login.php">登录</a>';  
	header("Location:index.html");  
        exit;  
    }  
      
    ?>  
