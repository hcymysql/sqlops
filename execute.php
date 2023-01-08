<?php
$id = $_GET['id'];
//$sql = htmlspecialchars($_GET['sql']);
//echo $sql; 

require 'conn.php';
$big_table_ddl = "select * from sql_order_wait where id=${id}";
$result_big = mysqli_query($conn,$big_table_ddl);
while($row = mysqli_fetch_array($result_big)){
	if($row["is_big_table"] == 1 || !empty($row['big_table_information']) ){
		$big_table_row = preg_replace('/[^0-9]+/','',$row['big_table_information']);
		if($big_table_row <= 30000000){
			$pt_osc_status = 1;
			echo '由于该表的行数小于3000万，平台将调用pt-online-schema-change开源工具在线执行Alter表结构变更。</br></br>';
		} else {	
			echo '<big><font color="#FF0000">Alter更改表结构会引起表锁，影响业务。由于该表过大，平台不支持自助上线。</font></big></br></br>';
			echo $row['big_table_information'] . "</br>";
			exit;
		}
	}
}

$big_table_dml = "select * from sql_order_wait where id=${id}";
$result_big_dml = mysqli_query($conn,$big_table_dml);
while($row = mysqli_fetch_array($result_big_dml)){
	if(!empty($row['dml_big_information']) ){
		echo '<big><font color="#FF0000">Update检索大批量数据会引起大范围行锁，影响业务。平台不支持自助上线。</font></big></br></br>';
		echo $row['dml_big_information'] . "</br>";
		exit;
	}
}

?>
<script>
function ss(){
var slt=document.getElementById("aa");
if(slt.value==""){
alert("你还没做出选择呢！选择是或者否！");
return false;
}
return true;
}
</script>

<form action="<?php  echo isset($pt_osc_status) ?  'pt_osc_real_time_output.php' :  'execute_status.php';  ?>" method="get" onsubmit=" return ss()"> 
    <select name="q" id="aa">
    <option value="">是否执行？</option>
    <option value="是">是</option>
    <option value="否">否</option>
    <input type="hidden" name="update_id" value="<?php echo $id ?>">
    <!--<input type="hidden" name="pt_osc_status" value="<?php   // echo isset($pt_osc_status) ? $pt_osc_status : 0;  ?>">-->
    </select>
    <input type="submit" onclick="javascript:return confirm('你确认提交吗？')" value="我要上线">
</form>
