<?php
    session_start();

    //检测是否登录，若没登录则转向登录界面  
    if(!isset($_SESSION['userid'])){
        header("Location:index.html");
        exit("你还没登录呢。");
    }
        //注销登录  
    if($_GET['action'] == "logout"){  
        unset($_SESSION['userid']);  
        unset($_SESSION['username']);  
        echo '注销登录成功！';  
	exit('<script>top.location.href="index.html"</script>');
    }  
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首页</title>
</head>
<frameset rows="30,*" cols="*" scrolling="No" framespacing="0"
	frameborder="no" border="0"> <frame src="header.php"
	name="headmenu" id="mainFrame" title="mainFrame"> <!-- 引用头部 -->
<!-- 引用左边和主体部分 --> <frameset rows="100*" cols="220,*" scrolling="No"
	framespacing="0" frameborder="no" border="0"> <frame
	src="left.php" name="leftmenu" id="mainFrame" title="mainFrame">
<frame src="sql_interface.php" name="main" scrolling="yes" noresize="noresize"
	id="rightFrame" title="rightFrame"></frameset></frameset>
</html>
