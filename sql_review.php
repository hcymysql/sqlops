<html>                       
<head>                  
<link rel="stylesheet" href="css/ace.min.css" />
</head>                      

</html> 
<?php
$s=$c=$is=$up=$at=$del=0;
$sql_count=1;
$parm_post=$_POST['sql_statement'];
$parm=preg_replace('/\`/','',strtolower($parm_post));
$dbname=$_POST['dbname'];
$sql_order=$_POST['sql_order'];
$alter_array=array();
$alter_parm=array();
$dml_parm=array();
$Keywords = array('data', 'desc', 'user', 'describe');
$ddl_sql=array();

if(!preg_match('/;/i',$parm)){
	echo "<big><font color='#FF0000'>警告！SQL语句后面要加分号;结尾, MySQL解析器规定分号才可以执行SQL。</font></big></br>";
	exit;               
}


require 'db_config.php';

$multi_sql=preg_split("/;+/",ltrim($parm));
$arrlength=count($multi_sql);
echo "</br>";
echo "<big><font color=\"#0000FF\">------你刚才选择的数据库名字是：" . $dbname . "------</font></big></br>";
for($x=0;$x<$arrlength;$x++){
echo "</br>";
echo "第" . $sql_count . "条、你刚才输入的SQL语句是：" . $multi_sql[$x] . "</br>";

echo "<hr style=FILTER: progid:DXImageTransform.Microsoft.Glow(color=#987cb9,strength=10) width=100% color=#987cb9 SIZE=1>";
if($multi_sql[$x]){
    	$parmArr_enter = str_replace("\r\n","  ",$multi_sql[$x]);
	$parmArr = preg_split("/[\s]+/",ltrim($parmArr_enter));
	switch ($parmArr[0]) {
		case 'select':
	        array_push($dml_parm,$parmArr[0]);
			if(in_array('*',$parmArr)){
				echo '提示：select *是否有必要查询所有的字段？</br>';
				$s++;
			}
			if(!in_array('where',$parmArr)){
                echo '<big><font color="#FF0000">警告！没有where条件，注意where后面的字段要加上索引</font></big></br>';
				$s++;
            }
			if(!in_array('limit',$parmArr)){
                echo '提示：没有limit会查询更多的数据</br>';
				$s++;
            }
			if(in_array("exists",$parmArr)){
                echo '<big><font color="#FF0000">警告！子查询性能低下，请转为join表关联</font></big></br>';
                $s++;
            }
            	if(in_array("in",$parmArr)){
			$countIn = array_count_values($parmArr);
			if(preg_match_all('/\(.*\)/',$parm,$out)){
                	echo "提示：in里面的数值不要超过1000个</br>";
                        $s++;
                	}
                	if(in_array("(select",$parmArr)){
				echo '<big><font color="#FF0000">警告！子查询性能低下，请转为join表关联</font></big></br>';
				        $s++;
			}
			if($countIn['select']>1){
				echo '<big><font color="#FF0000">警告!！子查询性能低下，请转为join表关联</font></big></br>';
                                        $s++;
                        }	
		}
			if(in_array("in(select",$parmArr)){
                echo '<big><font color="#FF0000">警告！子查询性能低下，请转为join表关联</font></big></br>';
                $s++;
			}
			
			if(in_array("join",$parmArr)){
				echo '提示：采用join关联，注意关联字段要都加上索引，如on a.id=b.id</br>';
				$countArr = array_count_values($parmArr);
				if($countArr['join']>1){
					echo '提示：MySQL对多表join关联性能低下，建议不要超过3个表以上的关联</br>';
					$s++;
				}
			}
			if(in_array("like",$parmArr)){
				if(preg_match_all("/'%(.)*%'/",$parm,$out)){
                     echo "<big><font color=\"#FF0000\">警告！like '%%'双百分号无法用到索引，like 'mysql%'这样是可以利用到索引的</font></big></br>";
                     $s++;
                }
			}
			if(in_array('group',$parmArr)){
				if(in_array('by',$parmArr)){
                     echo '提示：默认情况下，MySQL对所有GROUP BY col1，col2...的字段进行排序。如果查询包括GROUP BY，想要避免排序结果的消耗，则可以指定ORDER BY NULL禁止排序。</br>';
                     $s++;
				if(!in_array('having',$parmArr)){
		     echo '提示：是否要加一个having过滤下？</br>';
		     $s++;
				}
            }}
			if(in_array('order',$parmArr)){
                 if(preg_match_all("/by.*rand().*/",$parm,$out)){
                      echo '<big><font color="#FF0000">警告！MySQL里用到order by rand()在数据量比较多的时候是很慢的，因为会导致MySQL全表扫描，故也不会用到索引</font></big></br>';
                      $s++;
                 }
			     /*if(in_array('group',$parmArr)){
				      if(in_array('by',$parmArr)){
                            echo '提示：是否要加一个having过滤下？</br>';
                            $s++;
			          }
			     }*/
			     if(!in_array('group',$parmArr)){
			          if(preg_match_all("/count(.*)/",$parm,$out)){
                            echo '<big><font color="#FF0000">警告！禁止不必要的order by排序,因为前面已经count统计了</font></big></br>';
                            $s++;
                      }
                 }
    		}
			if(in_array('where',$parmArr)){
                 if(preg_match_all("/\(.*\)\s{0,}(>|<|=)/",$parm,$out)){
                            echo "<big><font color=\"#FF0000\">警告！MySQL里不支持函数索引，例DATE_FORMAT('create_time','%Y-%m-%d')='2016-01-01'是无法用到索引的，需要改写为create_time>='2016-01-01 00:00:00' and create_time<='2016-01-01 23:59:59'</font></big></br>";
                             $s++;
                 }
				 if(preg_match_all("/\(.*\)\s{0,}(>|<|=|between)/",$parm,$out)){
                             echo "<big><font color=\"#FF0000\">警告！MySQL里不支持函数索引，例DATE_FORMAT('create_time','%Y-%m-%d')='2016-01-01'是无法用到索引的，需要改写为create_time>='2016-01-01 00:00:00' and create_time<='2016-01-01 23:59:59'</font></big></br>";
                             $s++;
                 }
			}
			if($s==0){
                 echo 'SQL语句未发现问题</br>';
            }
			echo '</br>';
            //echo '<big><font color=\"#0000FF\">开始调用美团网SQLAdvisor进行第二次索引检查</font></big></br>';
			require 'sqladvisor_config.php';
			if ($message === ''){
                    echo "查询字段索引已经创建了,无需创建.</br>";
			}else{	
				echo "查询的字段没有创建索引，建议添加如下索引：</br>";
				echo $message."</br>";
				echo "<big><font color=\"#FF0000\">大表创建索引风险很高，如果一定要创建，请联系DBA进行执行。</font></big></br>";
			}	
			echo "</br>";
			echo "如果你觉得审核意见比较满意，请心中默念666，并推广给其他小伙伴使用。</br>";
            fclose($stream);
            fclose($errorStream);
			break;
		case 'insert':
		array_push($dml_parm,$parmArr[0]);
			if(preg_match('/insert.*select/i',$multi_sql[$x])){
				echo "<big><font color=\"#FF0000\">警告: insert 表1 select 表2，会造成锁表。</font></big></br>";
                $is++;
            }
			if($is==0){
                echo 'insert语句未发现问题</br>';
                $c_insert=1;
            }
			break;
		case 'update':
		array_push($dml_parm,$parmArr[0]);
			if(!in_array('where',$parmArr)){
                echo '<big><font color="#FF0000">警告！没有where条件，update会全表更新，禁止执行！！！</font></big></br>';
                exit;
            }
			$con1=mysqli_connect($ip,$user,$pwd,$db,$port);
            $result = mysqli_query($con1,"explain  ".$multi_sql[$x]);
            while($row = mysqli_fetch_array($result)){
                  $record_rows=$row[8];
                  if($record_rows<=50000){
                         echo "</br>";
                         echo $parmArr[1]."表 where条件字段，扫描影响的行数小于50000行，可以由开发自助执行。</br>";
                  }
                  else{
                         echo '<big><font color="#FF0000">'.$parmArr[1].'表 where条件字段，扫描影响的行数是：'.$record_rows.' 行，超过50000行请联系DBA执行!!!</font></big></br>';
                         $up++;
                  }
            }
            mysqli_close($con1);
			if($up==0){
                  echo 'update语句未发现问题</br>';
                  $c_update=1;
            }
			echo '</br>';
            //echo '<big><font color=\"#0000FF\">开始调用美团网SQLAdvisor进行第二次索引检查</font></big></br>';
            require 'sqladvisor_config.php';
            
            if ($message === ''){
                     echo "更新的where条件字段索引已经创建了,无需创建.</br>";
            }else{
                     echo "<big><font color=\"#FF0000\">更新的where条件字段没有创建索引，建议添加如下索引：</font></big></br>";
                     echo $message."</br>";
                     echo "<big><font color=\"#FF0000\">InnoDB存储引擎是通过给索引上的索引项加锁来实现的，这就意味着：只有通过索引条件检索数据，InnoDB才会使用行级锁，否则，InnoDB将使用表锁。</font></big></br>";
            }
            echo "</br>";
            echo "如果你觉得审核意见比较满意，请心中默念666，并推广给其他小伙伴使用。</br>";
            fclose($stream);
            fclose($errorStream);
            break;
		case 'delete':
			echo '<big><font color="#FF0000">delete删除数据属于高危语句，执行前请三思！</font></big></br>';
			array_push($dml_parm,$parmArr[0]);
			if(!in_array('where',$parmArr)){
                echo '<big><font color="#FF0000">警告！没有where条件，delete会全表更新，禁止执行！！！</font></big></br>';
                exit;
            }
	    $con3=mysqli_connect($ip,$user,$pwd,$db,$port);
            $result = mysqli_query($con3,"explain  ".$multi_sql[$x]);
            while($row = mysqli_fetch_array($result)){
                  $record_rows=$row[8];
                  if($record_rows<=50000){
                         echo "</br>";
                         echo $parmArr[2]."表 where条件字段，扫描影响的行数小于50000行，可以由开发自助执行。</br>";
                  }
                  else{
                         echo '<big><font color="#FF0000">'.$parmArr[2].'表 where条件字段，扫描影响的行数是：'.$record_rows.' 行，超过50000行请联系DBA执行!!!</font></big></br>';
                         $del++;
						 exit;
                  }
            }
            mysqli_close($con3);
			if($del==0){
                  echo 'delete语句未发现问题</br>';
                  $c_delete=1;
            }
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
		case 'create':
		array_push($dml_parm,$parmArr[0]);
		    if(preg_match('/create\s*index/',$multi_sql[$x])){
                       echo "<big><font color=\"#FF0000\">警告！不支持create index语法，请更改为alter table add index语法。</font></big></br>";
                       break;
                    } 
		    if(!in_array('primary',$parmArr)){
                       echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表没有主键</font></big></br>";
                       $c++;
                    }
		    if(in_array('primary',$parmArr)){
	               if(!preg_match('/AUTO_INCREMENT[ |,]/i',$multi_sql[$x])){
                          echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表主键应该是自增的，缺少AUTO_INCREMENT</font></big></br>";
                          $c++;
                       }
                    }
                        if(!preg_match('/.*\bid\b.*int.*/',$multi_sql[$x])){
                                        echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表主键字段名必须是id。</font></big></br>";
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
                  if($countkey['key']>=5){
                          echo "<big><font color=\"#FF0000\">警告！表中的索引数已经超过5个，索引是一把双刃剑，它可以提高查询效率但也会降低插入和更新的速度并占用磁盘空间，请让dba使用pt-duplicate-key-checker --user=root --password=xxxx --host=localhost --socket=/tmp/mysql.sock来检查重复的索引</font></big></br>";
                          $c++;
                  }
				  $countkey = array_count_values($parmArr);
                  if($countkey['index']>=5){
                           echo "<big><font color=\"#FF0000\">警告！表中的索引数已经超过5个，索引是一把双刃剑，它可以提高查询效率但也会降低插入和更新的速度
并占用磁盘空间。</font></big></br>";
                           $c++;
                  }
			}
			if(!in_array('comment',$parmArr)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表字段没有中文注释，COMMENT应该有默认值，如COMMENT '姓名'</font></big></br>";
                  $c++;
            }
            if(!preg_match_all("/comment=.*/",$parm,$out)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表没有中文注释，例：COMMENT='新版授信项表'</font></big></br>";
                  $c++;
			}
			if(!preg_match_all("/.*utf8.*/",$parm,$out)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表缺少utf8字符集，否则会出现乱码</font></big></br>";
                  $c++;
            }
			if(!in_array('engine=innodb',$parmArr)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表存储引擎应设置为InnoDB</font></big></br>";
                  $c++;
            }
                        if(!in_array('update_time',$parmArr)){
                                echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表缺少update_time字段，方便抽数据使用，且给加上索引。</font></big></br>";
                                $c++;
                        }
                        if(!preg_match_all("/update_time\s*timestamp.*|update_time\s*datetime.*/",$parm,$out)){
                                echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表update_time字段类型应设置timestamp。</font></big></br>";
                                $c++;
                        }
                        if(!preg_grep('/\(*update_time.*\),?/',$parmArr)){
                                echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表update_time字段缺少索引。</font></big></br>";
                                $c++;
                        }

			if(in_array('timestamp',$parmArr)){
				  if(!in_array('current_timestamp',$parmArr)){
                          echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表应该为timestamp类型加默认系统当前时间。例如：update_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间' </font></big></br>";
                          $c++;
				  }
            }
                        ##########################################
                        if(!in_array('create_time',$parmArr)){
                                echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表缺少create_time字段，方便抽数据使用，且给加上索引。</font></big></br>";
                                exit;
                        }
                        if(!preg_match_all("/create_time\s*timestamp.*|create_time\s*datetime.*/",$parm,$out)){
                                echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表create_time字段类型应设置timestamp。</font></big></br>";
                                exit;
                        }
                        if(!preg_grep('/\(*create_time.*\),?/',$parmArr)){
                                echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表create_time字段缺少索引。</font></big></br>";
                                exit;
                        }
                        ##########################################

            if(preg_grep('/.*utf8_bin/',$parmArr)){
            	echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表 utf8_bin应使用默认的字符>集核对utf8_general_ci。</font></big><br>";
                $c++;
            }
            if(preg_grep('/float.*/',$parmArr)){
                  echo '<big><font color="#FF0000">警告！用DECIMAL代替FLOAT和DOUBLE存储精确浮点数。浮点数的缺点是会引起精度问题，对货币等对精度敏感的数据
，应该用定点数decimal类型存储。</font></big></br>';
                  $c++;
            }
            if(preg_grep("/double.*/",$parmArr)){
                  echo '<big><font color="#FF0000">警告！用DECIMAL代替FLOAT和DOUBLE存储精确浮点数。浮点数的缺点是会引起精度问题，对货币等对精度敏感的数据
，应该用定点数decimal类型存储。</font></big></br>';
                  $c++;
            }	    
	    //--------------------------------------
	    foreach($Keywords as $value) {
	    if(preg_match('/'.'\b'.$value.'\b'.'/' ,$parm)){
            	echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表存在系统保留关键字</font></big></br>";
		$c++;
	    }
	    }
	    if(in_array('foreign',$parmArr)){
                echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表避免使用外键，外键会导致父表和子表之间耦合，十分影响SQL性能，出现过多的锁等待，甚
至会造成死锁。</font></big></br>";
            exit;
            }
            if(preg_match("/timestamp\([0-9]+\)/",$parm)){
                 echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表字段类型应设置为timestamp精确到秒。例：将timestamp(3)改成timestamp。</font></big></br>";
                 exit;
            }
            if(preg_match("/datetime\([0-9]+\)/",$parm)){
                  echo "<big><font color=\"#FF0000\">警告！$parmArr[2]表字段类型应设置为datetime精确到秒。例：将datetime(3)改成datetime。</font></big></br>";
                  exit;
            }
            if(in_array('comment',$parmArr)){
                  $count_column = array_count_values($parmArr);
                  if($count_column['comment']>=200){
                        echo "<big><font color=\"#FF0000\">警告！表中的字段超过200个。请检查是否符合建表三范式准则！</font></big></br>";
                        exit;
                  }
            }

            if($c==0){
                  echo '建表语句未发现问题</br>';
                  $c_create=1;
            }
			break;
		case 'alter':
            array_push($alter_array,$parmArr[2]);
	        array_push($alter_parm,$parmArr[0]);	
	        array_push($dml_parm,$parmArr[0]);
            $con2=mysqli_connect($ip,$user,$pwd,$db,$port); 
			$result = mysqli_query($con2,"explain select * from ".$parmArr[2]);
			while($row = mysqli_fetch_array($result)){
				   $record_rows=$row[8];
				   if($record_rows<=1500000){
					    echo "</br>";
					    echo $parmArr[2]."表记录小于150万行，可以由开发自助执行。</br>";
				   }
				   /*
				   else	if($record_rows<=40000000){
					    //echo $parmArr[2]."</br>";
					    for($i=3;$i<count($parmArr);++$i){ 
						//echo $parmArr[$i].'&nbsp'; 
						array_push($ddl_sql,$parmArr[$i]);
					    }
					    $ddl_sql_statement=join(" ",$ddl_sql);
				 	    echo $ddl_sql_statement."</br>";
					    $at==1;
					    //echo "<a href='pt_osc.php?id={$row['id']}'>通过pt-osc工具执行</a>
					    break;
				   }*/
			       else{
					    echo '<big><font color="#FF0000">'.$parmArr[2].'表记录是：'.$record_rows.' 行，表太大请联系DBA执行!!!</font></big></br>';
					    exit;
				   }  
			}
			mysqli_close($con2);
            if(in_array('drop',$parmArr)){
                   if(!preg_match('/drop.*index/i',$multi_sql[$x])){
                        echo "<big><font color=\"#FF0000\">警告！你要对$parmArr[2]表删除字段，数据会存在丢失的风险，请走审批！！！</font></big></br>";
                        exit;
                   }
            }
		    if($at==0){
                   echo 'alter语句未发现问题</br>';
                   $c_alter=1;
            }else{exit;}
            break;
		default:
			echo '请输入正确的sql语句</br>';
			break;
	}
}

$sql_count=$sql_count+1;

    if (($alter_parm[0] == $alter_parm[1] && $alter_parm[1] == 'alter') && $alter_array[0] == $alter_array[1]){
        echo "<p>";
        echo "<big><font color='#FF0000'>警告！更改表结构要减少与数据库的交互次数，应改为，例alter table t1 add index IX_uid(uid),add index IX_name(name);</font></big><p>";
        exit;
    }

    /*if (count($alter_parm) != 1){
	echo "<big><font color='#FF0000'>警告！DDL更改表结构一次只能写一条SQL！</font></big></br>";
	exit;
    }*/

} //end for


    $dml_parm_unique=array_values(array_unique($dml_parm));
    if(count($dml_parm) != 1) {
	       if (count($dml_parm_unique) != 1){
		        if ($dml_parm_unique[0] != $dml_parm_unique[1]){
    			     echo "<p>";
       	 		   echo "<big><font color='#FF0000'>警告！DDL和DML不同的操作语句要分开写，不要写在一个事务里。分开提交SQL工单。</font></big><p>"; 
               exit;
		        }
	       }
    }

if($c_create==1 || $c_insert==1 || $c_alter==1 || $c_update==1 || $c_delete==1){
      echo '</br>';
      echo '<div class="table-header">上线的SQL已经通过系统审核，待审批。</div></br>';
######记录上线操作######
    require 'order_number.php';
    session_start();
    echo '工单提交人：',$_SESSION['username']."<br>"; 
    echo '工单人邮箱：',$_SESSION['user_email']."<br>";
    echo '<br>';
    $dbuser=$_SESSION['username'];
    $dev_user_mail=$_SESSION['user_email'];

$sql_replace=preg_replace('/(#*|-*|`)/', '', $parm_post);
$conn=mysqli_connect("192.168.199.199","admin","123456","sql_db");
$ops_time="NOW()";
$str_replace_sql=str_replace("'","\'",$sql_replace);
$ops_sql = "INSERT INTO sql_order_wait (ops_order, ops_name, ops_db, ops_time, ops_order_name, ops_content) VALUES ($order_id,'$dbuser','$dbname',$ops_time,'$sql_order','$str_replace_sql')";
mysqli_query($conn,"SET NAMES 'utf8'");
mysqli_query($conn,$ops_sql);
mysqli_close($conn);

######发邮件给管理员审核SQL工单######
require 'mail/mail.php';

$sendmail = new mail($order_id,$_SESSION['username'],$dev_user_mail,$sql_order,$dbname);
$sendmail->execCommand();

}
?>
