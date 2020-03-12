<html>                       
<head>                  
<link rel="stylesheet" href="css/ace.min.css" />
</head>                      

</html> 
<?php
$s=$c=$is=$up=$at=$de=0;
$sql_count=1;
$parm_post=$_POST['sql_statement'];  //记录用户提交的原始SQL
$approver=$_POST['approver'];	//记录选择的审批人
$reason=$_POST['reason'];		//记录审批理由
$parm=preg_replace('/`/','',strtolower($parm_post));  //将用户提交的SQL带有反引号去掉
$parm_key=preg_replace('/`/','\`',strtolower($parm_post)); //保留关键字反引号转义
$dbname=$_POST['dbname'];		//记录选择的数据库名
$sql_order=$_POST['sql_order'];		//记录用户输入的工单名字
$alter_array=array(); //记录alter操作的表名
$alter_parm=array();  //记录alter命令
$dml_parm=array();  //记录DML操作的命令
$is_big_array=array(); //DDL大表检查
$review_array=array(); //提交的SQL审核是否可以通过
$prompt_message=array(); //记录SQL拦截信息
//$Keywords = array('data', 'desc', 'user', 'describe');
$dml_big_array=array();  //DML大表检查

if(!preg_match('/;/i',$parm)){
        echo "<big><font color='#FF0000'>警告！SQL语句后面要加分号;结尾, MySQL解析器规定分号才可以执行SQL。</font></big></br>";
        exit;
}

require 'conn.php';
require 'db_config.php';

$multi_sql=preg_split("/;+/",ltrim($parm));
$multi_key_sql=preg_split("/;+/",ltrim($parm_key));
$arrlength=count($multi_sql);
echo "</br>";
echo "<big><font color=\"#0000FF\">------你刚才选择的数据库名字是：" . $dbname . "------</font></big></br>";
for($x=0;$x<$arrlength;$x++){
echo "</br>";
echo "第" . $sql_count . "条、你刚才输入的SQL语句是：" . $multi_sql[$x] . "</br>";

//SQL语法检测
require 'sql_check.php';
//--------------------------

echo "<hr style=FILTER: progid:DXImageTransform.Microsoft.Glow(color=#987cb9,strength=10) width=100% color=#987cb9 SIZE=1>";
if($multi_sql[$x]){
    $parmArr_enter = str_replace("\r\n","  ",$multi_sql[$x]);
	$parmArr = preg_split("/[\s]+/",ltrim($parmArr_enter));
	switch ($parmArr[0]) {
		case 'insert':
		    array_push($dml_parm,$parmArr[0]);
			if(preg_match('/\bselect\b/i',$multi_sql[$x])){
				echo "<big><font color=\"#FF0000\">警告: insert 表1 select 表2，会造成锁表。</font></big></br>";
				$prompt_message[]='警告: insert 表1 select 表2，会造成锁表。';
                //$is++;
            }
			if($is==0){
                echo 'insert语句未发现问题</br>';
                $c_insert=1;
            } else {$c_insert=0;}
			array_push($review_array,$c_insert);
			break;
//---------------------------------华丽的分割线---------------------------------//
		case 'update':
		    array_push($dml_parm,$parmArr[0]);
			if(!in_array('where',$parmArr)){
                echo '<big><font color="#FF0000">警告！没有where条件，update会全表更新，禁止执行！！！</font></big></br>';
			    $prompt_message[]= '警告！没有where条件，update会全表更新，禁止执行！';
			    $up++;
            }
            $result = mysqli_query($conn,"explain  ".$multi_sql[$x]);
            while($row = mysqli_fetch_array($result)){
                  $record_rows=$row[8];
                  if($record_rows<=50000){
                      echo "</br>";
                      echo $parmArr[1]."表 where条件字段，扫描影响的行数小于50000行，可以由开发自助执行。</br>";
                  }
                  else{
                      echo '<big><font color="#FF0000">'.$parmArr[1].'表 where条件字段，扫描影响的行数是：'.$record_rows.' 行，超过50000行请联系DBA执行!!!</font></big></br>';
                      array_push($dml_big_array,"${parmArr[1]} 表 where条件字段，扫描影响的行数是：${record_rows}行，超过50000行请联系DBA执行!</br>");
                      //$up++;
                  }
            }
			if($up==0){
                  echo 'update语句未发现问题</br>';
                  $c_update=1;
            } else {$c_update=0;}
			array_push($review_array,$c_update);
			echo '</br>';
            //echo '<big><font color=\"#0000FF\">开始调用美团网SQLAdvisor进行第二次索引检查</font></big></br>';
            require 'sqladvisor_config.php';
            
            if ($message === ''){
                     echo "更新的where条件字段索引已经创建了,无需创建.</br>";
            }else{
                     echo "<big><font color=\"#FF0000\">更新的where条件字段没有创建索引，建议添加如下索引：</font></big></br>";
                     echo $message."</br>";
			    $prompt_message[]='更新的where条件字段没有创建索引，建议添加如下索引：';
			    $prompt_message[]=$message;
                     echo "<big><font color=\"#FF0000\">InnoDB存储引擎是通过给索引上的索引项加锁来实现的，这就意味着：只有通过索引条件检索数据，InnoDB才会使用行级锁，否则，InnoDB将使用表锁。</font></big></br>";
            }
            echo "</br>";
            echo "如果你觉得审核意见比较满意，请心中默念666，并推广给其他小伙伴使用。</br>";
            fclose($stream);
            fclose($errorStream);

            break;
//---------------------------------华丽的分割线---------------------------------//
		case 'delete':
		    echo '<big><font color="#FF0000">delete删除数据属于高危语句，执行前请三思！</font></big></br>';
			array_push($dml_parm,$parmArr[0]);
			if(!in_array('where',$parmArr)){
			    echo '<big><font color="#FF0000">警告！没有where条件，delete会全表更新，禁止执行！！！</font></big></br>';
			    $prompt_message[]='警告！没有where条件，delete会全表删除，禁止执行！';
			    $de++;
			}
			$result = mysqli_query($conn,"explain  ".$multi_sql[$x]);
			while($row = mysqli_fetch_array($result)){
			    $record_rows=$row[8];
			    if($record_rows<=50000){
			        echo "</br>";
			        echo $parmArr[2]."表 where条件字段，扫描影响的行数小于50000行，可以由开发自助执行。</br>";
			    }
			    else{
			        echo '<big><font color="#FF0000">'.$parmArr[2].'表 where条件字段，扫描影响的行数是：'.$record_rows.' 行，超过50000行请联系DBA执行!!!</font></big></br>';
			        $de++;
			        //exit;
			    }
			}
			if($de==0){
			    echo 'delete语句未发现问题</br>';
			    $c_delete=1;
			}else{$c_delete=0;}
			array_push($review_array,$c_delete);
			echo '</br>';
            //echo '<big><font color=\"#0000FF\">开始调用美团网SQLAdvisor进行第二次索引检查</font></big></br>';
            require 'sqladvisor_config.php';
            if ($message == ''){
                echo "删除的where条件字段索引已经创建了,无需创建.</br>";
            }else{
                echo "<big><font color=\"#FF0000\">删除的where条件字段没有创建索引，建议添加如下索引：</font></big></br>";
                echo $message."</br>";
                echo "<big><font color=\"#FF0000\">InnoDB存储引擎是通过给索引上的索引项加锁来实现的，这就意味着：只有通过索引条件检索数据，InnoDB才会使用行级锁，否则，InnoDB将使用表锁。</font></big></br>";
			}
			echo "</br>";
            echo "如果你觉得审核意见比较满意，请心中默念666，并推广给其他小伙伴使用。</br>";
            fclose($stream);
            fclose($errorStream);
			break;
//---------------------------------华丽的分割线---------------------------------//
		case 'create':
		    array_push($dml_parm,$parmArr[0]);
		    if(preg_match('/create\s*index/',$multi_sql[$x])){
		         echo "<big><font color=\"#FF0000\">警告！不支持create index语法，请更改为alter table add index语法。</font></big></br>";
		         $prompt_message[]='警告！不支持create index语法，请更改为alter table add index语法。';
			     $c++;
		    }
		    if(!in_array('primary',$parmArr)){
		         echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表没有主键</font></big></br>";
		         $c++;
			     $prompt_message[]="警告！${parmArr[2]}表没有主键";
		    }
		    if(in_array('primary',$parmArr)){
		         if(!preg_match('/AUTO_INCREMENT[ |,]/i',$multi_sql[$x])){
		             echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表主键应该是自增的，缺少AUTO_INCREMENT</font></big></br>";
		             $c++;
			         $prompt_message[]='警告！$parmArr[2]表主键应该是自增的，缺少AUTO_INCREMENT';
		         }
		    }
		    if(!preg_match('/.*\bid\b.*int.*/',$multi_sql[$x])){
		        echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表主键字段名必须是id。</font></big></br>";
		        $prompt_message[]="警告！${parmArr[2]}表主键字段名必须是id。";
		        $c++;
		    }
			if(!preg_match('/auto_increment=1 /i',$multi_sql[$x])){
			    echo "提示：id自增字段默认值为1，auto_increment=1 </br>";
			}
			if(preg_match_all('/\bkey\b/i',$multi_sql[$x],$match)){
			    if(!in_array('index',$parmArr)){
 					$countkey = array_count_values($parmArr);
 					if($countkey['key'] == 1){
 					    echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表没有索引</font></big></br>";
 					    $c++;
 					}
			    }
			}
			if(in_array('key',$parmArr)){
				  $countkey = array_count_values($parmArr);
                  if($countkey['key']>=15){
                      echo "<big><font color=\"#FF0000\">警告！表中的索引数已经超过5个，索引是一把双刃剑，它可以提高查询效率但也会降低插入和更新的速度并占用磁盘空间，请让dba使用pt-duplicate-key-checker --user=root --password=xxxx --host=localhost --socket=/tmp/mysql.sock来检查重复的索引</font></big></br>";
                      $prompt_message[]='警告！表中的索引数已经超过5个。';
                      $c++;
                  }
				  $countkey = array_count_values($parmArr);
                  if($countkey['index']>=15){
                      echo "<big><font color=\"#FF0000\">警告！表中的索引数已经超过5个，索引是一把双刃剑，它可以提高查询效率但也会降低插入和更新的速度并占用磁盘空间。</font></big></br>";
                      $c++;
                  }
			}
			if(!in_array('comment',$parmArr)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表字段没有中文注释，COMMENT应该有默认值，如COMMENT '姓名'</font></big></br>";
                  $c++;
		          $prompt_message[]="警告！${parmArr[2]}表字段没有中文注释，COMMENT应该有默认值，如COMMENT \'姓名\'";
            }
            if(!preg_match_all("/comment=.*/",$parm,$out)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表没有中文注释，例：COMMENT='新版授信项表'</font></big></br>";
                  $c++;
                  $prompt_message[]="警告！${parmArr[2]}表没有中文注释，例：COMMENT=\"新版授信项表\"";
			}
			if(!preg_match_all("/.*utf8.*/",$parm,$out)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表缺少utf8字符集，否则会出现乱码</font></big></br>";
			      $prompt_message[]="警告！${parmArr[2]}表缺少utf8字符集，否则会出现乱码";
                  $c++;
            }
			if(!in_array('engine=innodb',$parmArr)){
			      if(!in_array(')engine=innodb',$parmArr)){
			          echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表存储引擎应设置为InnoDB</font></big></br>";
			          $prompt_message[]="警告！${parmArr[2]}表存储引擎应设置为InnoDB";
			          $c++;
			      }
			}
			if(!in_array('update_time',$parmArr)){
			      echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表缺少update_time字段，方便抽数据使用，且给加上索引。</font></big></br>";
			      $prompt_message[]="警告！${parmArr[2]}表缺少update_time字段，方便抽数据使用，且给加上索引。";
			      $c++;
			}
			if(!preg_match_all("/update_time\s*timestamp.*|update_time\s*datetime.*/",$parm,$out)){
			      echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表update_time字段类型应设置timestamp。</font></big></br>";
			      $prompt_message[]="警告！${parmArr[2]}表update_time字段类型应设置timestamp。";
			      $c++;
			}
			if(!preg_grep('/\(*update_time.*\),?/',$parmArr)){
			      echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表update_time字段缺少索引。</font></big></br>";
			      $prompt_message[]="警告！${parmArr[2]}表update_time字段缺少索引。";
			      $c++;
			}
			if(in_array('timestamp',$parmArr)){
				  if(!in_array('current_timestamp',$parmArr)){
                          echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表应该为timestamp类型加默认系统当前时间。例如：update_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间' </font></big></br>";
                          $c++;
				   $prompt_message[]="警告！${parmArr[2]}表应该为timestamp类型加默认系统当前时间。例如：update_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'更新时间\'";
				  }
            }
            ##########################################
            if(!in_array('create_time',$parmArr)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表缺少create_time字段，方便抽数据使用，且给加上索引。</font></big></br>";
                  $c++;
                  $prompt_message[]="警告！${parmArr[2]}表缺少create_time字段，方便抽数据使用，且给加上索引。";
            }
            if(!preg_match_all("/create_time\s*timestamp.*|create_time\s*datetime.*/",$parm,$out)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表create_time字段类型应设置timestamp。</font></big></br>";
                  $c++;
                  $prompt_message[]="警告！${parmArr[2]}表create_time字段类型应设置timestamp。";
            }
            if(!preg_grep('/\(*create_time.*\),?/',$parmArr)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表create_time字段缺少索引。</font></big></br>";
                  $c++;
                  $prompt_message[]="警告！${parmArr[2]}表create_time字段缺少索引。";
            }
            ##########################################
            if(preg_grep('/.*utf8_bin/',$parmArr)){
            	  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表 utf8_bin应使用默认的字符集核对utf8_general_ci。</font></big><br>";
            	  $prompt_message[]="警告！${parmArr[2]}表 utf8_bin应使用默认的字符集核对utf8_general_ci。";
                  $c++;
            }
            if(preg_grep('/float.*/',$parmArr)){
                  echo '<big><font color="#FF0000">警告！用DECIMAL代替FLOAT和DOUBLE存储精确浮点数。浮点数的缺点是会引起精度问题，对货币等对精度敏感的数据，应该用定点数decimal类型存储。</font></big></br>';
                  $prompt_message[]='警告！用DECIMAL代替FLOAT和DOUBLE存储精确浮点数。浮点数的缺点是会引起精度问题，对货币等对精度敏感的数据，应该用定点数decimal类型存储。';
                  $c++;
            }
            if(preg_grep("/double.*/",$parmArr)){
                  echo '<big><font color="#FF0000">警告！用DECIMAL代替FLOAT和DOUBLE存储精确浮点数。浮点数的缺点是会引起精度问题，对货币等对精度敏感的数据，应该用定点数decimal类型存储。</font></big></br>';
                  $c++;
			      $prompt_message[]='警告！用DECIMAL代替FLOAT和DOUBLE存储精确浮点数。浮点数的缺点是会引起精度问题，对货币等对精度敏感的数据，应该用定点数decimal类型存储。';
            }	    
	    //--------------------------------------
	    foreach($Keywords as $value) {
	        if(preg_match('/'.'\b'.$value.'\b'.'/' ,$parm)){
            	echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表存在系统保留关键字</font></big></br>";
            	$c++;
	        }
	    }
	    if(in_array('foreign',$parmArr)){
                echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表避免使用外键，外键会导致父表和子表之间耦合，十分影响SQL性能，出现过多的锁等待，甚至会造成死锁。</font></big></br>";
                $prompt_message[]="警告！${parmArr[2]}表避免使用外键，外键会导致父表和子表之间耦合，十分影响SQL性能，出现过多的锁等待，甚至会造成死锁。";
                $c++;
	    }
	    if(preg_match("/timestamp\([0-9]+\)/",$parm)){
	            echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表字段类型应设置为timestamp精确到秒。例：将timestamp(3)改成timestamp。</font></big></br>";
	            $c++;
		        $prompt_message[]="警告！${parmArr[2]}表字段类型应设置为timestamp精确到秒。例：将timestamp(3)改成timestamp。";
	    }
	    if(preg_match("/datetime\([0-9]+\)/",$parm)){
	            echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表字段类型应设置为datetime精确到秒。例：将datetime(3)改成datetime。</font></big></br>";
	            $c++;
		        $prompt_message[]="警告！${parmArr[2]}表字段类型应设置为datetime精确到秒。例：将datetime(3)改成datetime。";
	    }
	    if(in_array('comment',$parmArr)){
	            $count_column = array_count_values($parmArr);
	            if($count_column['comment']>=200){
	                echo "<big><font color=\"#FF0000\">警告！表中的字段超过200个。请检查是否符合建表三范式准则！</font></big></br>";
				    $prompt_message[]='警告！表中的字段超过200个。请检查是否符合建表三范式准则！';
				    $c++;
	            }
	    }
	    if($c==0){
	        echo '建表语句未发现问题</br>';
	        $c_create=1;
            }else{$c_create=0;
	    }
	    array_push($review_array,$c_create);
	    break;
//---------------------------------华丽的分割线---------------------------------//
		case 'alter':
		    array_push($alter_array,$parmArr[2]);
		    array_push($alter_parm,$parmArr[0]);
		    array_push($dml_parm,$parmArr[0]);
			$result = mysqli_query($conn,"explain select * from ".$parmArr[2]." ");
			$row = mysqli_fetch_array($result);
			$record_rows=$row[8];
			if($record_rows<=1500000){
			    echo "</br>";
			    echo $parmArr[2]."表记录小于150万行，可以由开发自助执行。</br>";
			    $is_small=1;
			} else{
			        array_push($is_big_array,"$parmArr[2] 表记录是：${record_rows}行，表太大请联系DBA在业务低峰期执行!</br>");
			        echo '<big><font color="#FF0000">'.$parmArr[2].'表记录是：'.$record_rows.' 行，表太大请联系DBA在业务低峰期执行!!!</font></big></br>';
			        $is_small=0;
			        //exit;
			}
            if(in_array('drop',$parmArr)){
                   if(!preg_match('/drop.*index|drop.*foreign/i',$multi_sql[$x])){
                        echo "<big><font color=\"#FF0000\">警告！你要对$parmArr[2]表删除字段，数据会存在丢失的风险，请三思！！！</font></big></br>";
                        exit;
                   }
            }
	        if(in_array('rename',$parmArr)){
	               echo "<big><font color=\"#FF0000\">警告！你要对$parmArr[2]表改名，请先私下联系下大数据团队，看是否有问题。</font></big></br>";
			       exit;
            }

	        if(in_array('change',$parmArr)){
        	    if(in_array('column',$parmArr)){
                	$key=array_search('column', $parmArr);
        	    } else {
        	        $key=array_search('change', $parmArr);
        	    }
        	    if($parmArr[$key+1] != $parmArr[$key+2]){
                	//echo "<big><font color=\"#FF0000\">警告！你要对$parmArr[2]表字段${parmArr[$key+1]}改名，请先私下联系下大数据团队，看是否有问题。</font></big></br>";
                	//exit;
        	    }
	        }

	        if(!preg_match("/key|index/i",$parm)){
                  if(!preg_match("/comment.*/i",$parm)){
                      echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表字段没有中文注释，例：COMMENT '新版授信项表'</font></big></br>";
			          $prompt_message[]="警告！${parmArr[2]}表字段没有中文注释，例：COMMENT \'新版授信项表\'";
			          $at++;
			          //exit;
                  }
	        }
		    
            if($at==0){
                echo 'alter语句未发现问题</br>';
                $c_alter=1;
            } else {
                $c_alter=0;
            }
		    array_push($review_array,$c_alter);
            break;
//---------------------------------华丽的分割线---------------------------------//
		default:
			echo '请输入正确的sql语句</br>';
			exit;
	}
}

$sql_count=$sql_count+1;

    if (($alter_parm[0] == $alter_parm[1] && $alter_parm[1] == 'alter') && $alter_array[0] == $alter_array[1]){
        echo "<p>";
        echo "<big><font color='#FF0000'>警告！更改表结构要减少与数据库的交互次数，应改为，例alter table t1 add index IX_uid(uid),add index IX_name(name);</font></big><p>";
        //exit;
	  $c_alter=0;
	  $prompt_message[]="警告！更改表结构要减少与数据库的交互次数，应改为，例alter table t1 add index IX_uid(uid),add index IX_name(name);";
    }
    
}//end for

    $dml_parm_unique=array_values(array_unique($dml_parm));
    if(count($dml_parm) != 1)
    {
        if (count($dml_parm_unique) != 1)
        {
                if ($dml_parm_unique[0] != $dml_parm_unique[1])
                {
                        //echo $dml_parm_unique[0]."</br>";
                        //echo $dml_parm_unique[1]."</br>";
                        echo "<p>";
                        echo "<big><font color='#FF0000'>警告！DDL和DML不同的操作语句要分开写，不要写在一个事务里。分开提交SQL工单。</font></big><p>";
                        $c_alter=0;
				$prompt_message[]="警告！DDL和DML不同的操作语句要分开写，不要写在一个事务里。分开提交SQL工单。";
                }
        }
    }

//---------------------------------华丽的分割线---------------------------------//

$r=0;
//print_r($review_array);
foreach ($review_array as $value) {
  if($value<>1){
	  $r=$r-100;
  }else{$r++;}
}
//echo 'r的值是：'.$r."</br>";

require 'order_number.php';
session_start();
$dbuser=$_SESSION['username'];
$dev_user_mail=$_SESSION['user_email'];
$sql_replace=preg_replace('/(#*)/', '', $parm_post);
$ops_time="NOW()";
$str_replace_sql1=str_replace("'","\'",$sql_replace);
$str_replace_sql2=str_replace('"','\"',$str_replace_sql1);
$str_replace_sql=str_replace('`','\\\`',$str_replace_sql2);

if($r>=0){
//if($c_create==1 || $c_insert==1 || $c_alter==1 || $c_update==1 || $c_delete==1){
      echo '</br>';
      echo '<div class="table-header">上线的SQL已经通过系统审核，待审批。</div></br>';
######记录上线操作######
    //require 'order_number.php';
    //session_start();
    echo '工单提交人：',$_SESSION['username']."<br>"; 
    echo '工单人邮箱：',$_SESSION['user_email']."<br>";
    echo '<br>';

if(isset($is_big_array)){
	//$big_table=1;
	$big_table_information=nl2br(join("",$is_big_array));
}

if(isset($dml_big_array)){
	//$big_table=1;
	$dml_big_information=nl2br(join("",$dml_big_array));
}

if(!preg_match("/alter/i",$parm)){
	$ops_sql = "INSERT INTO sql_order_wait (ops_order, ops_name, ops_db, ops_time, ops_order_name, ops_reason, ops_content, dml_big_information) VALUES ($order_id,'$dbuser','$dbname',$ops_time,'$sql_order','$reason','$str_replace_sql','$dml_big_information')";
	$is_ddl = 0;
} else {
	if($is_small ==1){
	$ops_sql = "INSERT INTO sql_order_wait (ops_order, ops_name, ops_db, ops_time, ops_order_name, ops_reason, ops_content, is_ddl,is_big_table,big_table_information) VALUES ($order_id,'$dbuser','$dbname',$ops_time,'$sql_order','$reason','$str_replace_sql',1,0,'$big_table_information')";
	} else {
	$ops_sql = "INSERT INTO sql_order_wait (ops_order, ops_name, ops_db, ops_time, ops_order_name, ops_reason, ops_content, is_ddl,is_big_table,big_table_information) VALUES ($order_id,'$dbuser','$dbname',$ops_time,'$sql_order','$reason','$str_replace_sql',1,1,'$big_table_information')";
	}
	$is_ddl = 1;
	//echo $ops_sql."</br>";
}
echo $ops_sql."<br>"; 
require 'conn.php';
mysqli_query($conn,$ops_sql);

######发邮件给管理员审核SQL工单######

require 'mail/mail.php';
$sendmail = new mail($order_id,$_SESSION['real_user'],$dev_user_mail,$sql_order,$dbname,$approver);
$sendmail->execCommand(); 


}//end if $r
else{
	$pm=join("\n",$prompt_message);

	$ops_sql = "INSERT INTO sql_order_error (ops_order, ops_name, ops_db, ops_time, ops_order_name, ops_reason, ops_content, prompt_message) VALUES ($order_id,'$dbuser','$dbname',$ops_time,'$sql_order','$reason','$str_replace_sql','$pm')";
	//echo $ops_sql."</br>";
	mysqli_query($conn,"SET NAMES utf8");
      mysqli_query($conn,$ops_sql);

}


?>
