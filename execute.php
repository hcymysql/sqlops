<?php
$id = $_GET['id'];
//$sql = htmlspecialchars($_GET['sql']);
//echo $sql; 

?>
<form action="execute_status.php" method="get"> 
    <select name="q">
    <option value="">是否执行？</option>
    <option value="是">是</option>
    <option value="否">否</option>
    <input type="hidden" name="update_id" value="<?php echo $id ?>">
    <!--<input type="hidden" name="exec_sql" value="<?php //echo $sql ?>">-->
    </select>
    <input type="submit" onclick="javascript:return confirm('你确认提交吗？')" value="我要上线">
    </form>
