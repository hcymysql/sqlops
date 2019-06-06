<?php

    session_start();

    //检测是否登录，若没登录则转向登录界面  
    if(!isset($_SESSION['userid'])){
        header("Location:index.html");
        exit("你还没登录呢。");
    }


$id = $_GET['id'];
$return_status = isset($_GET['return_status'])?1:0;
//echo $return_status."<br>";
$login_user = $_GET['login_user'];
$prvi = $_GET['prvi'];
if($prvi == 2){
	echo "你是内审和安全组的人，没有权限审批工单！</br>";
	exit;
}
?>
<script>
function ss(){
var slt=document.getElementById("aa");
if(slt.value==""){
alert("你还没批准呢！选择是或者否！");
return false;
}
return true;
}
</script>
<form action="update_status.php" method="get" onsubmit=" return ss()"> 
    <select name="q" id="aa">
    <option value="">是否审批？</option>
    <option value="是">是</option>
    <option value="否">否</option>
    <input type="hidden" name="update_id" value="<?php echo $id ?>">
    <input type="hidden" name="login_admin_user" value="<?php echo $login_user ?>">
    <input type="hidden" name="return_status" value="<?php echo $return_status ?>">
    </select>
    <input type="submit" value="审批">
    </form>

