2020-03-11更新：

__1）sql_check.php --> SQL语法校验功能__

![image](https://raw.githubusercontent.com/hcymysql/sqlops/master/image/SQL%E8%AF%AD%E8%A8%80%E6%A0%A1%E9%AA%8C.png)

这里须要调用MariaDB的mysql客户端，因为MySQL5.6高版本会抛出警告，命令行里带密码不安全，会影响SQL语言检测结果。

mysql: [Warning] Using a password on the command line interface can be insecure.

---------------------------------------------------------------------------------------

__2）用户权限增加研发经理角色__

login_user表privilege字段（0:普通研发;1:研发经理;100:DBA）

区别：研发经理只能审批SQL，不能执行SQL；DBA可以审批SQL且可以执行SQL

__3) 增加dbinfo_prvi审批权限表__

在待审批工单栏目下，研发经理可以只审批自己的业务库，别的项目库，看不到审批内容。

INSERT INTO `dbinfo_prvi`(dbuser,approver,dbname) VALUES('hechunyang','贺春旸','test');
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


----------------------------------------------------------------------------
# 环境安装

1、PHP环境安装

# yum install httpd php mysql php-mysql php-devel php-pear libssh2 libssh2-devel -y

2、安装PHP SSH2扩展

pecl install -f ssh2


3、修改/etc/php.ini

在最后一行添加

extension=ssh2.so

然后重启httpd

systemctl restart  httpd.service

4、关闭selinux

# vim /etc/selinux/config

SELINUX=disabled


5、美团网SQLAdvisor安装

请移步 

https://github.com/Meituan-Dianping/SQLAdvisor/blob/master/doc/QUICK_START.md

6、binlog2sql安装

请移步

https://github.com/danfengcao/binlog2sql


# 部署

将https://github.com/hcymysql/sqlops/archive/master.zip
解压缩到/var/www/html/目录下

1、导入表结构sqlops_schema/sqlops_schema.sql（DB配置信息表）和（SQL工单记录表）

2、修改conn.php（sqlops数据库的配置信息的IP、端口、用户名、密码、库名）

录入开发上线的生产数据库信息

insert  into `dbinfo`(`id`,`ip`,`dbname`,`user`,`pwd`,`port`) values (1,'10.10.159.31','test','admin','hechunyang',3306);


3、修改sqladvisor_config.php（访问SQLAdvisor服务器的IP、SSH端口、SSH用户名、SSH密码）

4、修改rollback.php（访问binlog2sql服务器的IP、SSH端口、SSH用户名、SSH密码）

5、__execute_status.php 执行上线__

这里须要调用MariaDB的mysql客户端，因为MySQL5.6高版本会抛出警告，命令行里带密码不安全，会影响SQL语言检测结果。

mysql: [Warning] Using a password on the command line interface can be insecure.


