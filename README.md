2020-03-11更新：

__1）sql_check.php --> SQL语法校验功能__

![image](https://raw.githubusercontent.com/hcymysql/sqlops/master/image/SQL%E8%AF%AD%E8%A8%80%E6%A0%A1%E9%AA%8C.png)

这里须要调用MariaDB的mysql客户端，因为MySQL5.6高版本会抛出警告，命令行里带密码不安全，会影响SQL语言检测结果。

mysql: [Warning] Using a password on the command line interface can be insecure.

---------------------------------------------------------------------------------------
__execute_status.php 执行上线__

这里须要调用MariaDB的mysql客户端，因为MySQL5.6高版本会抛出警告，命令行里带密码不安全，会影响SQL语言检测结果。

mysql: [Warning] Using a password on the command line interface can be insecure.

-----------------------------------------------------------------------------------------
__2）用户权限增加研发经理角色__

login_user表privilege字段（0:普通研发;1:研发经理;100:DBA）

区别：研发经理只能审批SQL，不能执行SQL；DBA可以审批SQL且可以执行SQL

----------------------------------------------------------------------------

为了让DBA从日常繁琐的工作中解放出来，通过SQL自助平台，可以让开发自上线，开发提交SQL后就会自动返回优化建议，无需DBA的再次审核，
从而提升上线效率，有利于建立数据库开发规范。

![image](https://raw.githubusercontent.com/hcymysql/sqlops/master/image/sqlops.png)

借鉴了去哪网Inception的思路并且把美团网SQLAdvisor（索引优化建议）集成在一起，并结合了之前写的《DBA的40条军规》纳入了审核规则里，用PHP实现。
目前在我公司内部使用。


SQL自动审核主要完成两方面目的：

1、避免性能太差的SQL进入生产系统，导致整体性能降低。

2、检查开发设计的索引是否合理，是否需要添加索引。


思路其实很简单:

1、获取开发提交的SQL

2、对要执行的SQL做分析，触碰事先定义好的规则来判断这个SQL是否可以自动审核通过，未通过审核的需要人工处理。


# 环境安装

1、PHP环境安装

# yum install httpd php mysql php-mysql php-devel php-pear libssh2 libssh2-devel -y

2、安装PHP SSH2扩展

pecl install -f ssh2


3、修改/etc/php.ini

在最后一行添加

extension=ssh2.so

4、关闭selinux

# vim /etc/selinux/config

SELINUX=disabled


5、美团网SQLAdvisor安装

请移步 

https://github.com/Meituan-Dianping/SQLAdvisor/blob/master/doc/QUICK_START.md


# 部署

 

将php-sqlreview.zip解压缩到/var/www/html/目录下

 

1、导入dbinfo.sql（DB配置信息表）和operation.sql（SQL工单记录表）

 

2、修改db_config.php（DB配置信息的IP、端口、用户名、密码、库名）

 

3、修改sqladvisor_config.php（访问SQLAdvisor服务器的IP、SSH端口、SSH用户名、SSH密码）

 

4、修改sql_submit.php（记录工单表的IP、端口、用户名、密码）和（调用mysql客户端的IP、SSH端口、SSH用户名、SSH密码）
