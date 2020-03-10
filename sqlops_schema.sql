CREATE TABLE `dbinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) DEFAULT NULL,
  `dbname` varchar(100) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IX_dbname` (`dbname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据库信息表';

CREATE TABLE `login_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) DEFAULT NULL,
  `real_user` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pwd` varchar(256) DEFAULT NULL,
  `privilege` int(11) DEFAULT '0' COMMENT '0:普通研发;1:业务方领导',
  PRIMARY KEY (`id`),
  UNIQUE KEY `IX_user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录表';

CREATE TABLE `sql_order_wait` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ops_order` bigint(11) NOT NULL COMMENT '工单号',
  `ops_name` varchar(100) DEFAULT NULL COMMENT '上线人',
  `ops_db` varchar(50) DEFAULT NULL COMMENT '上线DB',
  `ops_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上线申请时间',
  `ops_order_name` varchar(5000) DEFAULT NULL COMMENT '工单名称',
  `ops_reason` varchar(100) DEFAULT NULL COMMENT '上线申请原因',
  `ops_content` mediumtext COMMENT 'SQL详细信息',
  `status` tinyint(4) DEFAULT '0' COMMENT '0:等待管理员审批,1:审批完毕,2:审批不通过',
  `finish_status` tinyint(4) DEFAULT '0' COMMENT '0:未审批不能执行,1:执行,2:执行完毕',
  `approver` varchar(100) DEFAULT NULL COMMENT '工单审批人',
  `binlog_information` varchar(500) DEFAULT NULL COMMENT 'binlog位置信息记录',
  `is_ddl` tinyint(4) DEFAULT NULL COMMENT '0:没有ALTER操作;1:有ALTER操作',
  `is_big_table` tinyint(4) DEFAULT '0' COMMENT '是否为大表',
  `big_table_information` text COMMENT '不能上线执行DDL警告提示',
  `dml_big_information` text COMMENT '不能上线执行DML警告提示',
  PRIMARY KEY (`id`),
  KEY `ops_name` (`ops_name`),
  KEY `ops_time` (`ops_time`),
  KEY `IX_ops_order` (`ops_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工单表';



