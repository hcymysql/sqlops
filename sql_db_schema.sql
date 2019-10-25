-- MySQL dump 10.16  Distrib 10.1.31-MariaDB, for Linux (x86_64)
--
-- Host: 192.168.198.242    Database: sql_db
-- ------------------------------------------------------
-- Server version	10.1.10-MariaDB-enterprise-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `column_name_rules`
--

DROP TABLE IF EXISTS `column_name_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `column_name_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `correct_name` varchar(200) DEFAULT NULL,
  `information` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `column_name_rules1`
--

DROP TABLE IF EXISTS `column_name_rules1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `column_name_rules1` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `correct_name` varchar(200) DEFAULT NULL,
  `information` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dbinfo`
--

DROP TABLE IF EXISTS `dbinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dbinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) DEFAULT NULL,
  `dbname` varchar(100) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dbinfo_bak`
--

DROP TABLE IF EXISTS `dbinfo_bak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dbinfo_bak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) DEFAULT NULL,
  `dbname` varchar(100) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dbinfo_prvi`
--

DROP TABLE IF EXISTS `dbinfo_prvi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dbinfo_prvi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dbuser` varchar(50) DEFAULT NULL,
  `approver` varchar(50) DEFAULT NULL,
  `dbname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dbname` (`dbname`),
  KEY `dbuser` (`dbuser`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login_user`
--

DROP TABLE IF EXISTS `login_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) DEFAULT NULL,
  `real_user` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pwd` varchar(256) DEFAULT NULL,
  `privilege` int(11) DEFAULT '0' COMMENT '0:普通研发;1:业务方领导;2:内审和安全;3:大数据;100:DBA管理员',
  PRIMARY KEY (`id`),
  UNIQUE KEY `IX_user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login_user_bak`
--

DROP TABLE IF EXISTS `login_user_bak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_user_bak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) DEFAULT NULL,
  `real_user` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pwd` varchar(256) DEFAULT NULL,
  `privilege` int(11) DEFAULT '0' COMMENT '0:普通研发;1:管理员;2:内审和安全;3:大数据',
  PRIMARY KEY (`id`),
  UNIQUE KEY `IX_user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mysql_repl_info`
--

DROP TABLE IF EXISTS `mysql_repl_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mysql_repl_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) DEFAULT NULL,
  `dbname` varchar(100) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mysql_repl_status`
--

DROP TABLE IF EXISTS `mysql_repl_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mysql_repl_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) DEFAULT NULL,
  `host` varchar(30) DEFAULT NULL,
  `dbname` varchar(100) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `role` tinyint(2) DEFAULT NULL,
  `is_live` tinyint(4) DEFAULT NULL,
  `read_only` varchar(10) DEFAULT NULL,
  `gtid_mode` varchar(10) DEFAULT NULL,
  `Master_Host` varchar(30) DEFAULT NULL,
  `Master_Port` varchar(100) DEFAULT NULL,
  `Slave_IO_Running` varchar(20) DEFAULT NULL,
  `Slave_SQL_Running` varchar(20) DEFAULT NULL,
  `Seconds_Behind_Master` varchar(20) DEFAULT NULL,
  `Master_Log_File` varchar(30) DEFAULT NULL,
  `Relay_Master_Log_File` varchar(30) DEFAULT NULL,
  `Read_Master_Log_Pos` varchar(30) DEFAULT NULL,
  `Exec_Master_Log_Pos` varchar(30) DEFAULT NULL,
  `Last_IO_Error` varchar(500) DEFAULT NULL,
  `Last_SQL_Error` varchar(500) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mysql_slow_info`
--

DROP TABLE IF EXISTS `mysql_slow_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mysql_slow_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) DEFAULT NULL,
  `dbname` varchar(100) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mysql_slow_query_review`
--

DROP TABLE IF EXISTS `mysql_slow_query_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mysql_slow_query_review` (
  `checksum` bigint(20) unsigned NOT NULL,
  `fingerprint` text NOT NULL,
  `sample` text NOT NULL,
  `first_seen` datetime DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL,
  `reviewed_by` varchar(20) DEFAULT NULL,
  `reviewed_on` datetime DEFAULT NULL,
  `comments` text,
  PRIMARY KEY (`checksum`),
  KEY `idx_last_seen` (`last_seen`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mysql_slow_query_review_history`
--

DROP TABLE IF EXISTS `mysql_slow_query_review_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mysql_slow_query_review_history` (
  `serverid_max` int(4) NOT NULL,
  `db_max` varchar(100) DEFAULT NULL,
  `user_max` varchar(100) DEFAULT NULL,
  `checksum` bigint(20) unsigned NOT NULL,
  `sample` text NOT NULL,
  `ts_min` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ts_max` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ts_cnt` float DEFAULT NULL,
  `Query_time_sum` float DEFAULT NULL,
  `Query_time_min` float DEFAULT NULL,
  `Query_time_max` float DEFAULT NULL,
  `Query_time_pct_95` float DEFAULT NULL,
  `Query_time_stddev` float DEFAULT NULL,
  `Query_time_median` float DEFAULT NULL,
  `Lock_time_sum` float DEFAULT NULL,
  `Lock_time_min` float DEFAULT NULL,
  `Lock_time_max` float DEFAULT NULL,
  `Lock_time_pct_95` float DEFAULT NULL,
  `Lock_time_stddev` float DEFAULT NULL,
  `Lock_time_median` float DEFAULT NULL,
  `Rows_sent_sum` float DEFAULT NULL,
  `Rows_sent_min` float DEFAULT NULL,
  `Rows_sent_max` float DEFAULT NULL,
  `Rows_sent_pct_95` float DEFAULT NULL,
  `Rows_sent_stddev` float DEFAULT NULL,
  `Rows_sent_median` float DEFAULT NULL,
  `Rows_examined_sum` float DEFAULT NULL,
  `Rows_examined_min` float DEFAULT NULL,
  `Rows_examined_max` float DEFAULT NULL,
  `Rows_examined_pct_95` float DEFAULT NULL,
  `Rows_examined_stddev` float DEFAULT NULL,
  `Rows_examined_median` float DEFAULT NULL,
  `Rows_affected_sum` float DEFAULT NULL,
  `Rows_affected_min` float DEFAULT NULL,
  `Rows_affected_max` float DEFAULT NULL,
  `Rows_affected_pct_95` float DEFAULT NULL,
  `Rows_affected_stddev` float DEFAULT NULL,
  `Rows_affected_median` float DEFAULT NULL,
  `Rows_read_sum` float DEFAULT NULL,
  `Rows_read_min` float DEFAULT NULL,
  `Rows_read_max` float DEFAULT NULL,
  `Rows_read_pct_95` float DEFAULT NULL,
  `Rows_read_stddev` float DEFAULT NULL,
  `Rows_read_median` float DEFAULT NULL,
  `Merge_passes_sum` float DEFAULT NULL,
  `Merge_passes_min` float DEFAULT NULL,
  `Merge_passes_max` float DEFAULT NULL,
  `Merge_passes_pct_95` float DEFAULT NULL,
  `Merge_passes_stddev` float DEFAULT NULL,
  `Merge_passes_median` float DEFAULT NULL,
  `InnoDB_IO_r_ops_min` float DEFAULT NULL,
  `InnoDB_IO_r_ops_max` float DEFAULT NULL,
  `InnoDB_IO_r_ops_pct_95` float DEFAULT NULL,
  `InnoDB_IO_r_ops_stddev` float DEFAULT NULL,
  `InnoDB_IO_r_ops_median` float DEFAULT NULL,
  `InnoDB_IO_r_bytes_min` float DEFAULT NULL,
  `InnoDB_IO_r_bytes_max` float DEFAULT NULL,
  `InnoDB_IO_r_bytes_pct_95` float DEFAULT NULL,
  `InnoDB_IO_r_bytes_stddev` float DEFAULT NULL,
  `InnoDB_IO_r_bytes_median` float DEFAULT NULL,
  `InnoDB_IO_r_wait_min` float DEFAULT NULL,
  `InnoDB_IO_r_wait_max` float DEFAULT NULL,
  `InnoDB_IO_r_wait_pct_95` float DEFAULT NULL,
  `InnoDB_IO_r_wait_stddev` float DEFAULT NULL,
  `InnoDB_IO_r_wait_median` float DEFAULT NULL,
  `InnoDB_rec_lock_wait_min` float DEFAULT NULL,
  `InnoDB_rec_lock_wait_max` float DEFAULT NULL,
  `InnoDB_rec_lock_wait_pct_95` float DEFAULT NULL,
  `InnoDB_rec_lock_wait_stddev` float DEFAULT NULL,
  `InnoDB_rec_lock_wait_median` float DEFAULT NULL,
  `InnoDB_queue_wait_min` float DEFAULT NULL,
  `InnoDB_queue_wait_max` float DEFAULT NULL,
  `InnoDB_queue_wait_pct_95` float DEFAULT NULL,
  `InnoDB_queue_wait_stddev` float DEFAULT NULL,
  `InnoDB_queue_wait_median` float DEFAULT NULL,
  `InnoDB_pages_distinct_min` float DEFAULT NULL,
  `InnoDB_pages_distinct_max` float DEFAULT NULL,
  `InnoDB_pages_distinct_pct_95` float DEFAULT NULL,
  `InnoDB_pages_distinct_stddev` float DEFAULT NULL,
  `InnoDB_pages_distinct_median` float DEFAULT NULL,
  `QC_Hit_cnt` float DEFAULT NULL,
  `QC_Hit_sum` float DEFAULT NULL,
  `Full_scan_cnt` float DEFAULT NULL,
  `Full_scan_sum` float DEFAULT NULL,
  `Full_join_cnt` float DEFAULT NULL,
  `Full_join_sum` float DEFAULT NULL,
  `Tmp_table_cnt` float DEFAULT NULL,
  `Tmp_table_sum` float DEFAULT NULL,
  `Tmp_table_on_disk_cnt` float DEFAULT NULL,
  `Tmp_table_on_disk_sum` float DEFAULT NULL,
  `Filesort_cnt` float DEFAULT NULL,
  `Filesort_sum` float DEFAULT NULL,
  `Filesort_on_disk_cnt` float DEFAULT NULL,
  `Filesort_on_disk_sum` float DEFAULT NULL,
  PRIMARY KEY (`checksum`,`ts_min`,`ts_max`),
  KEY `idx_serverid_max` (`serverid_max`) USING BTREE,
  KEY `idx_query_time_max` (`Query_time_max`) USING BTREE,
  KEY `idx_db_ts` (`db_max`,`ts_max`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mysql_status`
--

DROP TABLE IF EXISTS `mysql_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mysql_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `host` varchar(30) DEFAULT NULL,
  `dbname` varchar(100) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `role` tinyint(4) DEFAULT NULL,
  `is_live` tinyint(4) DEFAULT NULL,
  `max_connections` int(11) DEFAULT NULL,
  `threads_connected` int(11) DEFAULT NULL,
  `qps_select` int(11) DEFAULT NULL,
  `qps_insert` int(11) DEFAULT NULL,
  `qps_update` int(11) DEFAULT NULL,
  `qps_delete` int(11) DEFAULT NULL,
  `runtime` int(11) DEFAULT NULL,
  `db_version` varchar(100) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=549634 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mysql_status_history`
--

DROP TABLE IF EXISTS `mysql_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mysql_status_history` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `host` varchar(30) DEFAULT NULL,
  `dbname` varchar(100) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `role` tinyint(4) DEFAULT NULL,
  `is_live` tinyint(4) DEFAULT NULL,
  `max_connections` int(11) DEFAULT NULL,
  `threads_connected` int(11) DEFAULT NULL,
  `qps_select` int(11) DEFAULT NULL,
  `qps_insert` int(11) DEFAULT NULL,
  `qps_update` int(11) DEFAULT NULL,
  `qps_delete` int(11) DEFAULT NULL,
  `runtime` int(11) DEFAULT NULL,
  `db_version` varchar(100) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `IX_h_d_p` (`host`,`dbname`,`port`)
) ENGINE=InnoDB AUTO_INCREMENT=7718368 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mysql_status_info`
--

DROP TABLE IF EXISTS `mysql_status_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mysql_status_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) DEFAULT NULL,
  `dbname` varchar(100) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `operation`
--

DROP TABLE IF EXISTS `operation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ops_name` varchar(100) DEFAULT NULL,
  `ops_db` varchar(50) DEFAULT NULL,
  `ops_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ops_content` longtext,
  `binlog_information` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12400 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `operation_failure`
--

DROP TABLE IF EXISTS `operation_failure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operation_failure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ops_name` varchar(100) DEFAULT NULL,
  `ops_db` varchar(50) DEFAULT NULL,
  `ops_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ops_content` longtext,
  `binlog_information` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=626 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sql_order_error`
--

DROP TABLE IF EXISTS `sql_order_error`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sql_order_error` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ops_order` bigint(11) NOT NULL COMMENT '工单号',
  `ops_name` varchar(100) DEFAULT NULL,
  `ops_db` varchar(50) DEFAULT NULL,
  `ops_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ops_order_name` varchar(5000) DEFAULT NULL,
  `ops_reason` varchar(100) DEFAULT NULL,
  `ops_content` mediumtext,
  `prompt_message` text,
  PRIMARY KEY (`id`),
  KEY `ops_name` (`ops_name`),
  KEY `ops_time` (`ops_time`)
) ENGINE=InnoDB AUTO_INCREMENT=420 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sql_order_wait`
--

DROP TABLE IF EXISTS `sql_order_wait`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sql_order_wait` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ops_order` bigint(11) NOT NULL COMMENT '工单号',
  `ops_name` varchar(100) DEFAULT NULL,
  `ops_db` varchar(50) DEFAULT NULL,
  `ops_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ops_order_name` varchar(5000) DEFAULT NULL,
  `ops_reason` varchar(100) DEFAULT NULL,
  `ops_content` mediumtext,
  `status` tinyint(4) DEFAULT '0' COMMENT '0:等待管理员审批,1:审批完毕,2:审批不通过',
  `status_second` tinyint(4) DEFAULT '0' COMMENT '0:等待管理员审批,1:审批完毕,2:审批不通过',
  `finish_status` tinyint(4) DEFAULT '0' COMMENT '0:未审批不能执行,1:执行,2:执行完毕',
  `finish_status_second` tinyint(4) DEFAULT '0' COMMENT '0:未审批不能执行,1:执行,2:执行完毕',
  `approver` varchar(100) DEFAULT NULL,
  `approver2` varchar(100) DEFAULT NULL,
  `binlog_information` varchar(500) DEFAULT NULL,
  `is_ddl` tinyint(4) DEFAULT NULL COMMENT '0:没有ALTER操作;1:有ALTER操作',
  `is_big_table` tinyint(4) DEFAULT '0',
  `big_table_information` text,
  `dml_big_information` text,
  PRIMARY KEY (`id`),
  KEY `ops_name` (`ops_name`),
  KEY `is_ddl` (`is_ddl`),
  KEY `ops_time` (`ops_time`)
) ENGINE=InnoDB AUTO_INCREMENT=4118 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sql_order_wait_test`
--

DROP TABLE IF EXISTS `sql_order_wait_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sql_order_wait_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ops_order` bigint(11) NOT NULL COMMENT '工单号',
  `ops_name` varchar(100) DEFAULT NULL,
  `ops_db` varchar(50) DEFAULT NULL,
  `ops_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ops_order_name` varchar(5000) DEFAULT NULL,
  `ops_content` text,
  `status` tinyint(4) DEFAULT '0' COMMENT '0:等待管理员审批,1:审批完毕,2:审批不通过',
  `status_second` tinyint(4) DEFAULT NULL COMMENT '0:等待管理员审批,1:审批完毕,2:审批不通过',
  `finish_status` tinyint(4) DEFAULT '0' COMMENT '0:未审批不能执行,1:执行,2:执行完毕',
  `finish_status_second` tinyint(4) DEFAULT '0' COMMENT '0:未审批不能执行,1:执行,2:执行完毕',
  `approver` varchar(100) DEFAULT NULL,
  `approver2` varchar(100) DEFAULT NULL,
  `binlog_information` varchar(500) DEFAULT NULL,
  `is_ddl` tinyint(1) DEFAULT '0' COMMENT '0:没有ALTER操作;1:有ALTER操作',
  PRIMARY KEY (`id`),
  KEY `ops_name` (`ops_name`)
) ENGINE=InnoDB AUTO_INCREMENT=732 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-25 18:01:17
