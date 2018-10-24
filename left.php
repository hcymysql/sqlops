<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首页左侧导航</title>
<link rel="stylesheet" type="text/css" href="css/public.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/public.js"></script>
<head></head>

<body id="bg">
	<!-- 左边节点 -->
	<div class="container">

		<div class="leftsidebar_box">
			<a href="sql_interface.php" target="main"><div class="line">
					<image src="image/coin01.png" />&nbsp;&nbsp;首页
				</div></a>
			<dl class="system_log">
				<dt>
					<image class="icon1" src="image/coin03.png" /><image class="icon2"
						src="image/coin04.png" /> 工单管理<image class="icon3"
						src="image/coin19.png" /><image class="icon4"
						src="image/coin20.png" />
				</dt>
				<?php
				session_start();
				$prvi = $_SESSION['prvi'];
				if($prvi!=1){ 
				echo "<dd>";
					echo "<image class='coin11' src='image/coin111.png' /><image class='coin22'";
					echo "src='image/coin222.png' /><a class='cks' href='my_order.php'";
						echo "target='main'>我的工单</a><image class='icon5' src='image/coin21.png' />";
				echo "</dd>";
				}
				?>
				<dd>
                                        <img class="coin11" src="image/coin111.png" /><image class="coin22"
                                                src="image/coin222.png" /><a class="cks" href="stat/show.html"
                                                target="main">上线数量统计</a><image class="icon5" src="image/coin21.png" />
                                </dd>	
				<dd>
                                        <image class="coin11" src="image/coin111.png" /><image class="coin22"
                                                src="image/coin222.png" /><a class="cks" href="wait_order.php"
                                                target="main">审核工单</a><image class="icon5" src="image/coin21.png" />
                                </dd>
			</dl>
		

		</div>

	</div>
</body>
</html>
