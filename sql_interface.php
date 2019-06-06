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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SQL自动审核-自助上线平台</title>

<!-- 每次打开网页 清除页面缓存-->
<HEAD>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<script type="text/javascript" src="./slowlog/js/jquery-3.3.1.min.js"></script>
<script src="./js/select2.min.js"></script>
<link href="./js/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="./sqladvisor/css/normalize.css">
<link rel="stylesheet" href="./sqladvisor/css/bootstrap.min.css">
<link rel="stylesheet" href="./sqladvisor/css/font-awesome.min.css">
<link rel="stylesheet" href="./sqladvisor/css/themify-icons.css">
<link rel="stylesheet" href="./sqladvisor/css/pe-icon-7-filled.css">
<link rel="stylesheet" href="./sqladvisor/css/flag-icon.min.css">
<link rel="stylesheet" href="./sqladvisor/css/cs-skin-elastic.css">
<link rel="stylesheet" href="./sqladvisor/css/style.css">

</HEAD>

<body>

<div class="col-lg-10" >		
<div class="card" >

 <div class="card-header" align="center">
   <strong><h1>SQL自动审核-自助上线平台<h1></strong> 
 </div>
 <div class="card-body card-block"  align="center">
 
<form action="sql_review.php" method="post" name="sql_statement" id="form1" onsubmit=" return ss()">
<div class="row form-group">
    <div class="col col-md-3"><label for="select" class=" form-control-label">选择你的数据库</label></div>
    <div class="col-12 col-md-9" align="left">
        <select name="dbname" id="select" class="singleSelect" align="left" style="width: 100%;height:calc(2.25rem + 2px);text-align:left;text-align-last:left">
		<option value="">选择或输入你的dbname</option>
	<?php
	require 'conn.php';
	$result = mysqli_query($conn,"SELECT dbname FROM dbinfo order by dbname ASC");
	while($row = mysqli_fetch_array($result)){
	//echo "<option value='".$row[0]."'>".$row[0]."</option>"."</br>";
	echo "<option value=\"".$row[0]."\">".$row[0]."</option>"."<br>";
        }?>
        </select>
    </div>
	<script type="text/javascript">
        $(document).ready(function() {
                $('.singleSelect').select2();
            });
        </script>
    </div></br>

<div class="row form-group" align="center">
<div class="col col-md-3"><label for="textarea-input" class=" form-control-label">输入你要上线的SQL</label></div>                                    
<div class="col-12 col-md-9">
<textarea class="form-control" name="sql_statement" type="text" rows="9" cols="100" value="select count(*) from t1 group by name;" size="1000000"  onfocus="if (value =='select count(*) from t1 group by name;'){value =''}" onblur="if (value ==''){value='select count(*) from t1 group by name;'}" />select count(*) from t1 group by name;</textarea>
</div>
</div>
    <br />
	
<div class="row form-group" align="center">
<div class="col col-md-3"><label for="textarea-input" class=" form-control-label">请输入工单名称</label></div>                                    
<div class="col-12 col-md-9">	
<input class="form-control" name="sql_order" type="text" placeholder="请输入工单名称.." required="required"  maxlength="2000" oninvalid="setCustomValidity('请输入工单名称..')" oninput="setCustomValidity('')"> 

<script>
function ss(){
var slt=document.getElementById("aa");
var slt2=document.getElementById("bb");
var slt3=document.getElementById("select");
if(slt.value==""){
	alert("请选择审批人!!!");
	return false;
}
if(slt2.value==""){
	alert("请选择上线理由!!!");
	return false;
}
if(slt3.value==""){
	alert("请选择数据库!!!");
	return false;
}
return true;
}
</script>
</div>
</div>
    <br />


<div class="row form-group">
    <div class="col col-md-3"><label for="select" class=" form-control-label">请选择审批人</label></div>
    <div class="col-12 col-md-9">
<select name="approver" id="aa" class="form-control">
<option value="">请选择审批人</option>
<option value="hechunyang@xxx.com">贺春旸</option>
</select>
</div></div>
    <br />

<div class="row form-group">
    <div class="col col-md-3"><label for="select" class=" form-control-label">请选择上线理由</label></div>
    <div class="col-12 col-md-9">
<!-- -->
<select name="reason" id="bb" class="form-control">
<option value="">请选择上线理由</option>
<option value="BUG（自身）">BUG（自身）</option>
<option value="BUG（外部）">BUG（外部）</option>
<option value="功能交付">功能交付</option>
</select>
    <br />
</div></div>

    <div class="form-actions form-group" align="center">
    <button name="Submit" type="submit" class="btn btn-primary btn"/>我要上线</button>
    </label>
  </div>

</form>
<table width="724" border="0" align="center">
  <tr>
    <td width="648"><div align="left">
      <p>使用说明：<br />
        1、针对select/insert/update/delete/create/alter加了规则。 <br />
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

