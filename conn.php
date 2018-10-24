    <?php   
      
     $conn = mysqli_connect("192.168.199.199","admin","123456","sql_db","3306") or die("数据库链接错误".mysql_error());  
     mysqli_query($conn,"set names utf8");  
    ?>  
