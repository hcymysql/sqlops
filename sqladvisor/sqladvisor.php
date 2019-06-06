<?php
    session_start();

    //检测是否登录，若没登录则转向登录界面  
    if(!isset($_SESSION['userid'])){
        header("Location:../index.html");
        exit("你还没登录呢。");
    }
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SQL自助查询优化</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">


<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/themify-icons.css">
<link rel="stylesheet" href="css/pe-icon-7-filled.css">
<link rel="stylesheet" href="css/flag-icon.min.css">
<link rel="stylesheet" href="css/cs-skin-elastic.css">
<link rel="stylesheet" href="css/style.css">

</head>
<body>
                    <div class="col-lg-9" >		
                        <div class="card" >
			<p><font color="#0000FF">注：选择你的数据库，然后提交SQL，平台会自动返回优化建议。例如你可以将线上跑的比较慢的SQL，拿到平台里执行，平台会根据你的SQL自动反馈优化建议。同时，你也可以关注“监控管理”-->“MySQL慢查询分析” ，来查看线上抓取的慢SQL。</font></p>
                            <div class="card-header" align="center">
                                <strong>SQL自助查询优化（自动反馈优化建议）</strong> 
                            </div>
                            <div class="card-body card-block"  align="center">
					<form action="sqladvisor_result.php" method="post" enctype="multipart/form-data" class="form-horizontal">
					<div class="row form-group">
                                        <div class="col col-md-3"><label for="select" class=" form-control-label">选择你的数据库</label></div>
                                        <div class="col-12 col-md-9">

<script>
function select_db(){
var select = document.getElementById("select").value;
if (!select)
{
alert("请选择数据库！");
}
}
</script>						
                                            <select name="select_yourdb" id="select" class="form-control">
						<option value="">Please select</option>
	<?php
		$conn=mysqli_connect("192.168.188.166","admin","wdhcy159753","sql_db","3333"); 
		$result = mysqli_query($conn,"SELECT dbname FROM dbinfo order by dbname ASC");
		while($row = mysqli_fetch_array($result)){
			echo "<option value=\"".$row[0]."\">".$row[0]."</option>"."<br>";
    		}
    	?>
                                            </select>
                                        </div>
                                    </div>				
					
					<div class="row form-group" align="center">
                                        <div class="col col-md-3"><label for="textarea-input" class=" form-control-label">输入你想优化的SQL</label></div>
                                        <div class="col-12 col-md-9"><textarea name="input_yoursql" id="textarea-input" rows="9" placeholder="Content..." class="form-control"></textarea></div>
                                    </div>
									
                                <div class="form-actions form-group" align="center">
                                    <button type="submit" class="btn btn-primary btn-sm" onclick="select_db()">反馈优化建议</button>
                                </div>
					</div>
					</div>
					</div>
					</div>
</body>
</html>
