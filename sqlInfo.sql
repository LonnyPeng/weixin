-- ----------------------------
-- Table structure for t_member
-- ----------------------------
DROP TABLE IF EXISTS `t_member`;
CREATE TABLE `t_member` (
  `member_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `member_perms` set('member_grant','product_read','product_export','product_report','product_create','product_update','product_delete') NOT NULL DEFAULT '',
  `member_name` varchar(90) NOT NULL,
  `member_password` char(35) NOT NULL,
  `member_regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `member_logtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `member_logip` char(15) NOT NULL DEFAULT '',
  `member_lognum` int(10) unsigned NOT NULL DEFAULT '1',
  `member_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `member_name` (`member_name`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8;
