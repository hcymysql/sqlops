-- MySQL dump 10.15  Distrib 10.0.36-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: sql_db
-- ------------------------------------------------------
-- Server version	10.0.36-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `sql_db`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `sql_db` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `sql_db`;

--
-- Table structure for table `dbinfo`
--

DROP TABLE IF EXISTS `dbinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dbinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `ip` varchar(100) DEFAULT NULL COMMENT '远程数据库IP',
  `dbname` varchar(100) DEFAULT NULL COMMENT '远程数据库名称',
  `user` varchar(100) DEFAULT NULL COMMENT '远程数据库用户名',
  `pwd` varchar(100) DEFAULT NULL COMMENT '远程数据库密码',
  `port` int(11) DEFAULT NULL COMMENT '远程数据库端口',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='数据库上线信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dbinfo`
--

LOCK TABLES `dbinfo` WRITE;
/*!40000 ALTER TABLE `dbinfo` DISABLE KEYS */;
INSERT INTO `dbinfo` VALUES (1,'192.168.199.199','test','admin','123456',3306);
/*!40000 ALTER TABLE `dbinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_user`
--

DROP TABLE IF EXISTS `login_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `user` varchar(100) DEFAULT NULL COMMENT '登录账号',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `pwd` varchar(256) DEFAULT NULL COMMENT 'MD5密码',
  `privilege` int(11) DEFAULT '0' COMMENT '审批权限',
  PRIMARY KEY (`id`),
  KEY `IX_user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='sqlops平台系统登录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_user`
--

LOCK TABLES `login_user` WRITE;
/*!40000 ALTER TABLE `login_user` DISABLE KEYS */;
INSERT INTO `login_user` VALUES (1,'admin','admin@126.com','e10adc3949ba59abbe56e057f20f883e',1),(2,'guest','guest@126.com','e10adc3949ba59abbe56e057f20f883e',0);
/*!40000 ALTER TABLE `login_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sql_order_wait`
--

DROP TABLE IF EXISTS `sql_order_wait`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sql_order_wait` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `ops_order` bigint(11) NOT NULL COMMENT '工单号',
  `ops_name` varchar(100) DEFAULT NULL COMMENT '上线提交人',
  `ops_db` varchar(50) DEFAULT NULL COMMENT '上线数据库名字',
  `ops_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上线提交时间',
  `ops_order_name` varchar(5000) DEFAULT NULL COMMENT '上线工单名称',
  `ops_content` longtext COMMENT '记录上线SQL信息',
  `status` tinyint(4) DEFAULT '0' COMMENT '0:等待管理员审批,1:审批完毕,2:审批不通过',
  `finish_status` tinyint(4) DEFAULT '0' COMMENT '0:未审批不能执行,1:执行,2:执行完毕',
  `approver` varchar(100) DEFAULT NULL COMMENT '审批人',
  `binlog_information` varchar(500) DEFAULT NULL COMMENT '记录binlog信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='工单记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sql_order_wait`
--

LOCK TABLES `sql_order_wait` WRITE;
/*!40000 ALTER TABLE `sql_order_wait` DISABLE KEYS */;
INSERT INTO `sql_order_wait` VALUES (22,2018102011430473,'guest','test','2018-10-20 03:43:04','测试1','insert into t1 values(1,\'张三\');',1,2,'admin','mysql-bin.000004	10836		\nmysql-bin.000004	10987		\n'),(24,2018102021524666,'admin','test','2018-10-20 13:52:46','更改名字','update t1 set name =\'test\' where id = 1;',1,2,'admin','mysql-bin.000004	13350		\nmysql-bin.000004	13513		\n');
/*!40000 ALTER TABLE `sql_order_wait` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-22 10:52:09
