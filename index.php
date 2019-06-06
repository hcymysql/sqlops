<?php

     if(!isset($_POST['submit'])){ 
				//echo "<h1>未登录，非法访问！</h1><br>";
				//echo '<meta http-equiv="Refresh" content="1;url=login.html"/>';
	exit('<script>top.location.href="login.html"</script>');
     }
		  
    session_start();

        //注销登录  
    if($_GET['action'] == "logout"){  
        unset($_SESSION['userid']);  
        unset($_SESSION['username']);  
        echo '注销登录成功！';  
	exit('<script>top.location.href="login.html"</script>');
    }  
?>


<!doctype html>
<html  class="x-admin-sm">
<head>
	<meta charset="UTF-8">
	<title>sqlops 运管平台</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="xadmin/css/font.css">
	<link rel="stylesheet" href="xadmin/css/xadmin.css">
    <script type="text/javascript" src="xadmin/js/jquery-3.3.1.min.js"></script>
    <script src="xadmin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="xadmin/js/xadmin.js"></script>
    <script type="text/javascript" src="xadmin/js/cookie.js"></script>
    <script>
        // 是否开启刷新记忆tab功能
        // var is_remember = false;
    </script>
</head>
<body>
    <!-- 顶部开始 -->
    <div class="container">
        <div class="logo"><a href='#'>sqlops 运管平台</a></div>
        <div class="left_open">
            <i title="展开/收缩左侧栏" class="iconfont">&#xe699;</i>
        </div>
	    <!--
        <ul class="layui-nav left fast-add" lay-filter="">
          <li class="layui-nav-item">
            <a href="javascript:;">+新增</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
     <!--
              <dd><a onclick="x_admin_show('资讯','http://www.baidu.com')"><i class="iconfont">&#xe6a2;</i>资讯</a></dd>
              <dd><a onclick="x_admin_show('图片','http://www.baidu.com')"><i class="iconfont">&#xe6a8;</i>图片</a></dd>
               <dd><a onclick="x_admin_show('用户','http://www.baidu.com')"><i class="iconfont">&#xe6b8;</i>用户</a></dd>
            </dl>
          </li>
        </ul> -->
        <ul class="layui-nav right" lay-filter="">
          <li class="layui-nav-item">
		  
		  <?php
		  
		  $username = $_POST['username'];  
              $password = $_POST['password'];
	        include('conn.php');
		  mysqli_query($conn,"set names utf8");
		  $check_query = mysqli_query($conn,"select id,privilege,email,real_user from login_user where user='$username' and pwd=MD5('$password') limit 1");  
              if($result = mysqli_fetch_array($check_query)){  
				//登录成功  
                        session_start();  
                        $_SESSION['username'] = $username;  
                        $_SESSION['userid'] = $result['id'];  
	                  $_SESSION['prvi'] = $result['privilege'];
	                  $_SESSION['user_email'] = $result['email'];
	                  $_SESSION['real_user'] = $result['real_user'];
                        //echo $result['real_user'],' 欢迎你！进入 <a href="main.php">用户中心</a><br />';  
                        //echo '点击此处 <a href="login.php?action=logout">注销</a> 登录！<br />'; 
             } else {  
                        echo '<a href="javascript:history.back(-1);"><b>登录失败！</b></a> ';  
			exit;
             } 
		?>
		
            <a href="javascript:;"><?php  echo  $result['real_user'];?></a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
              <dd><a onclick="x_admin_show('个人信息','http:///')">个人信息</a></dd>
              <dd><a onclick="x_admin_show('切换帐号','http:///')">切换帐号</a></dd>
              <dd><a href="index.php?action=logout">退出</a></dd>
            </dl>
          </li>
          <!--<li class="layui-nav-item to-index"><a href="/">前台首页</a></li>-->
	  <li class="layui-nav-item to-index"><?php include 'time.php'; ?></li>
        </ul>
        
    </div>
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
     <!-- 左侧菜单开始 -->
    <div class="left-nav">
      <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6b8;</i>
                    <cite>工单管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu" style="display: block">
		
                <li date-refresh="1">
                        <a _href="sql_interface.php">
                            <i class="iconfont">&#xe6a7;</i>
                          <cite>发起工单</cite>

                        </a>
                </li >

		<?php
		    session_start();
		    $prvi = $_SESSION['prvi'];
		    if($prvi!=77){
                    echo '<li date-refresh="1">
                        <a _href="my_order.php">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>我的工单</cite>
                            
                        </a>
                    </li >';
		    }
		?>

 		    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe70b;</i>
                            <cite>审核工单</cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>
                        <ul class="sub-menu" style="display: block">
                            <li>
                                <a _href="wait_order.php">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>待审核</cite>
                                </a>
                            </li >
                        </ul>
			<ul class="sub-menu" style="display: block">
                            <li>
                                <a _href="finish_order.php">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>工单汇总</cite>
                                </a>
                            </li >
                        </ul>

                        <ul class="sub-menu" style="display: block">
                            <li>
                                <a _href="intercept_order.php">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>系统拦截</cite>
                                </a>
                            </li >
                        </ul>

                    </li>

<!--		  	
 		    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe70b;</i>
                            <cite>信息统计</cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a _href="stat/show.html">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>BUG统计</cite>
                                </a>
                            </li >
                        </ul>
                       <!--
			<ul class="sub-menu">
                            <li>
                                <a _href="member.php">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>会员统计</cite>
                                </a>
                            </li >
                        </ul>                       
                    </li>
 -->		  	
 		    <li>
					
				<li date-refresh="1">
                        <a _href="readme.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>平台介绍</cite>                           
                        </a>
                    </li>
		
<!--			
                    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe70b;</i>
                            <cite>SQL优化</cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a _href="sqladvisor/sqladvisor.php">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>SQL自助查询优化</cite>                               
                                </a>
                            </li >                       
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe723;</i>
                    <cite>监控管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="http://123.59.154.43/mysql_monitor/mysql_status_monitor.php">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>MySQL状态监控</cite>
                        </a>
                    </li >
                    <li>
                        <a _href="./slowlog/slowquery.php">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>MySQL慢查询分析</cite>
                        </a>
                    </li >
			
		    <li>
                        <a _href="http://123.59.154.43/mysql_monitor/mysql_repl_monitor.php">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>MySQL主从复制监控</cite>
                        </a>
                    </li >
			
                    <li>
                        <a _href="http://123.59.154.52/graph/">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>Grafana图形监控</cite>
                        </a>
                    </li >				
-->					
                </ul>
            </li>
           
        </ul>
      </div>
    </div>
    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
          <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>首页</li>
          </ul>
          <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
                <dl>
                    <dd data-type="this">关闭当前</dd>
                    <dd data-type="other">关闭其它</dd>
                    <dd data-type="all">关闭全部</dd>
                </dl>
          </div>
          <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='./sql_interface.php' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
          </div>
          <div id="tab_show"></div>
        </div>
    </div>
    <div class="page-content-bg"></div>
    <!-- 右侧主体结束 -->
    <!-- 中部结束 -->
    <!-- 底部开始 -->
    <div class="footer">
        <div class="copyright">http://fander.asuscomm.com:8008/sqlops_approve &nbsp;&nbsp;&nbsp;SQL自动审核-自助上线平台 v2.0</div>  
    </div>
    <!-- 底部结束 -->

</body>
</html>
