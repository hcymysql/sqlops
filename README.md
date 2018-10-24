![image](https://raw.githubusercontent.com/hcymysql/sqlops/master/sqlops%E6%B5%81%E7%A8%8B.png)

为了让DBA从日常繁琐的工作中解放出来，通过SQL自助平台，可以让开发自上线，开发提交SQL后就会自动返回优化建议，无需DBA的再次审核，从而提升上线效率，有利于建立数据库开发规范。

借鉴了去哪网Inception的思路并且把美团网SQLAdvisor（索引优化建议）集成在一起，并结合了之前写的《DBA的40条军规》纳入了审核规则里，用PHP实现。

SQL自动审核主要完成两方面目的：

1、避免性能太差的SQL进入生产系统，导致整体性能降低。

2、检查开发设计的索引是否合理，是否需要添加索引。 


思路其实很简单:

1、获取开发提交的SQL

2、对要执行的SQL做分析，触碰事先定义好的规则来判断这个SQL是否可以自动审核通过，未通过审核的需要人工处理。


-----------------------------------------------------
10.22日更新

1)增加一键生成反向SQL回滚功能。

演示地址 http://fander.jios.org:8008/

普通上线账号：guest ，密码：123456

管理员审批账号：admin，密码：123456

感谢好友陈俊聪友情提供云主机。


-----------------------------------------------------
一、环境安装Centos6.8

1、php环境安装

# yum install httpd php mysql php-mysql php-devel php-pear libssh2 libssh2-devel -y

2、安装php ssh2扩展

pecl install -f ssh2

3、修改/etc/php.ini

在最后一行添加

extension=ssh2.so

4、关闭selinux

# vim /etc/selinux/config

SELINUX=disabled

5、美团网SQLAdvisor安装
请移步 https://github.com/Meituan-Dianping/SQLAdvisor/blob/master/doc/QUICK_START.md


6、binlog2sql安装
请移步 https://github.com/danfengcao/binlog2sql

-----------------------------------------------------

上线流程为：
开发提交SQL，系统自动审核（sql_review.php），审核通过后生成我的工单待管理员批复并且发邮件通知，管理员人工确认审核通过后，开发点击执行完成上线。

sqlops_approve/sql/sql_db.sql

涉及的表解释说明

1、login_user.sql  (sqlops平台系统登录表）

2、sql_order_wait.sql  （工单记录表）

3、dbinfo.sql	（数据库上线信息表）


脚本解释

1、index.html（用户登录入口）

2、login.php（用户密码校验）

3、main.php（首页框架栏）

4、header.php（用户登录欢迎页面，和注销）

5、left.php（导航栏）

6、sql_interface.php（SQL传参入口）

7、sql_review.php（SQL审核）

8、my_order.php（查看我的工单，执行，撤销）

9、wait_order.php（管理员人工批复：通过，否决）

10、update.php（管理员审批确认）

11、update_status.php（修改审批状态值）

12、execute.php（开发执行SQL工单）

13、execute_status.php（修改执行工单状态）

14、cancel.php（开发自行撤销工单）

15、cancel_status.php（修改撤销工单状态）

16、stat/show.html（工单动态统计图表）

17、db_config.php（DB配置信息的IP、端口、用户名、密码、库名）

18、sqladvisor_config.php（访问SQLAdvisor服务器的IP、SSH端口、SSH用户名、SSH密码）

19、rollback.php（生成回滚反向SQL）

20、mail/mail.php（提交工单时发送邮件通知）

---------------------
注：
1、客户端版本使用mysql5.5或者mariadb10.X。

5.6会出现Warning: Using a password on the command line interface can be insecure，导致上线失败。

2、php文件里的涉及连接数据库的用户名和密码要修改，这块没有做成统一个DB配置文件调用。

---------------------
# create审核

检查项：

1、警告！表没有主键

2、警告！表主键字段名必须是id。

3、提示：id自增字段默认值为1，auto_increment=1

4、警告！表字段没有中文注释，COMMENT应该有默认值，如COMMENT '姓名'

5、警告！表没有中文注释，例：COMMENT='学生信息表'

6、警告！表缺少utf8字符集，否则会出现乱码

7、警告！表存储引擎应设置为InnoDB

8、警告！表缺少update_time字段，方便抽数据使用，且给加上索引。

9、警告！表update_time字段类型应设置timestamp。

10、警告！表update_time字段缺少索引。

11、警告！表缺少create_time字段，方便抽数据使用，且给加上索引。

12、警告！表中的索引数已经超过5个，索引是一把双刃剑，它可以提高查询效率但也会降低插入和更新的速度并占用磁盘空间

13、警告！表应该为timestamp类型加默认系统当前时间。例如：update_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'

14、警告！表 utf8_bin应使用默认的字符集核对utf8_general_ci

15、警告！用DECIMAL代替FLOAT和DOUBLE存储精确浮点数。浮点数的缺点是会引起精度问题，对货币等对精度敏感的数据，应该用定点数decimal类型存储。

16、警告！避免使用外键，外键会导致父表和子表之间耦合，十分影响SQL性能，出现过多的锁等待，甚至会造成死锁。

17、警告！表字段类型应设置为datetime精确到秒。例：将datetime(3)改成datetime。
   警告！表字段类型应设置为timestamp精确到秒。例：将timestamp(3)改成datetime。

---------------------
# alter审核

检查项：

1、警告！不支持create index语法，请更改为alter table add index语法。

2、警告！更改表结构要减少与数据库的交互次数，应改为，例alter table t1 add index IX_uid(uid),add index IX_name(name)

3、表记录小于150万行，可以由开发自助执行。否则表太大请联系DBA执行!

4、支持删除索引，但不支持删除字段

---------------------
# insert审核

检查项：

1、警告：insert 表1 select 表2，会造成锁表。

---------------------
# update/delete审核

检查项：

1、警告！没有where条件，update会全表更新，禁止执行！！！

2、更新的行数小于1000行，可以由开发自助执行。否则请联系DBA执行！！！

3、防止where 1=1 绕过审核规则

4、检查更新字段有无索引

5、警告！DML不同的操作要分开写，不要写在一个事务里。

---------------------
# select审核

检查项：

1、select * 是否有必要查询所有的字段？

2、警告！没有where条件，注意where后面的字段要加上索引

3、没有limit会查询更多的数据

4、警告！子查询性能低下，请转为join表关联

5、提示：in里面的数值不要超过1000个

6、提示：采用join关联，注意关联字段要都加上索引，如on a.id=b.id

7、提示：MySQL对多表join关联性能低下，建议不要超过3个表以上的关联

8、警告！like '%%'双百分号无法用到索引，like 'mysql%'这样是可以利用到索引的

9、提示：默认情况下，MySQL对所有GROUP BY col1，col2...的字段进行排序。如果查询包括GROUP BY，想要避免排序结果的消耗，则可以指定ORDER BY NULL禁止排序。

10、警告！MySQL里用到order by rand()在数据量比较多的时候是很慢的，因为会导致MySQL全表扫描，故也不会用到索引

11、提示：是否要加一个having过滤下？

12、警告！禁止不必要的order by排序，因为前面已经count统计了

13、警告！MySQL里不支持函数索引，例DATE_FORMAT('create_time','%Y-%m-%d')='2016-01-01'是无法用到索引的，需要改写为
create_time>='2016-01-01 00:00:00' and create_time<='2016-01-01 23:59:59'

14、检查更新字段有无索引


