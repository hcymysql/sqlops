<?php
$id = $_GET['id'];
?>
<form action="cancel_status.php" method="get"> 
    <select name="q">
    <option value="">是否撤销工单？</option>
    <option value="是">是</option>
    <option value="否">否</option>
    <input type="hidden" name="cancel_id" value="<?php echo $id ?>">
    </select>
    <input type="submit" value="撤销">
    </form>

