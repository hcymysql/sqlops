<?php
    session_start();

    //检测是否登录，若没登录则转向登录界面  
    if(!isset($_SESSION['userid'])){
        header("Location:index.html");
        exit("你还没登录呢。");
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SQL自动审核-自助上线平台</title>
<style type="text/css">
<!--
.STYLE2 {font-size: 50px}
.STYLE3 {font-size: 24px}
-->
</style>
<link rel="stylesheet" type="text/css" href="css/page.css">
</head>

<!-- 每次打开网页 清除页面缓存-->
<HEAD>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
</HEAD>

<body>
<div class="jumbotron" style="background-color:#336699">
  <div class="container">
<span style="float:left;"><img src="image/logo.jpg" height="80" width="113" \></span><font size="11">SQL自动审核-自助上线平台</font>
  </div>
</div>
<div class="jumbotron" style="background-color:#FFF; color:#333; padding:10px;">
  <div class="container">
  <p>输入SQL语句</p>
  </div>
</div>
<form action="sql_review.php" method="post" name="sql_statement" id="form1">
  <label></label>
  <div align="center">
    <label>
    <tr>
        <td>选择你的数据库：</td>
        <td><select name="dbname">
	<?php
	$conn=mysqli_connect("192.168.199.199","admin","123456","sql_db","3306"); 
	$result = mysqli_query($conn,"SELECT dbname FROM dbinfo");
	while($row = mysqli_fetch_array($result)){
	//echo "<option value='".$row[0]."'>".$row[0]."</option>"."</br>";
	echo "<option value=\"".$row[0]."\">".$row[0]."</option>"."<br>";
        }?>
        </select><td>
    </tr>
<textarea name="sql_statement" type="text" rows="100" cols="100" value="请输入SQL语句...;" size="1000000" style="width:745px;height:200px;color:blue;font-size:24px;border: 5px dashed #FF9933" onfocus="if (value =='请输入SQL语句...'){value =''}" onblur="if (value ==''){value='请输入SQL语句...'}" />
请输入SQL语句...</textarea>
    <br />
    <br />
<input name="sql_order" type="text" style="width:300px;" maxlength="2000" value="请输入工单名称.."  
    onfocus="if (value =='请输入工单名称..'){value =''}"  
    onblur="if (value ==''){value='请输入工单名称..'}" />  
    <br />
    <br />
    </label>

    <label>
    <input name="Submit" type="submit" class="STYLE3" value="提交审核" />
    </label>
  </div>
</form>
<table width="724" border="0" align="center">
  <tr>
    <td width="648"><div align="left">
      <p>使用说明：<br />
        1、针对select/insert/update/create/alter加了规则，delete需要审批。 <br />
        2、语句之间要有空格，例where id = 100，没有空格会影响判断的准确性。 <br />
        3、SQL语句后面要加分号; MySQL解析器规定分号才可以执行SQL。<br />
        <big><font color="#FF0000">4、反引号`可能会造成上线失败，需要用文本编辑器替换掉。</font></big><br />
        <big><font color="#FF0000">5、支持多条SQL解析，用一个分号;分割。例如：<br/>
                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;insert into t1 values(1,'a');<br>
                                   <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;###### <br>-->
                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;insert into t1 values(2,'b'); </font></big><br />
	<big><font color="#FF0000">6、JSON格式里的双引号要用反斜杠进行转义，例如：{\"dis_text\":\"nba\"}。</font></big></p>
      </div></td>
  </tr>
</table>
<p align="center">&nbsp;</p>
</body>
</html>

