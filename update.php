<?php
$id = $_GET['id'];
$login_user = $_GET['login_user'];
?>
<form action="update_status.php" method="get"> 
    <select name="q">
    <option value="">是否审批？</option>
    <option value="是">是</option>
    <option value="否">否</option>
    <input type="hidden" name="update_id" value="<?php echo $id ?>">
    <input type="hidden" name="login_admin_user" value="<?php echo $login_user ?>">
    </select>
    <input type="submit" value="审批">
    </form>

