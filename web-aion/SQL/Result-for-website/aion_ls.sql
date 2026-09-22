-- ----------------------------
-- account_data (Merged with BeyondAion structure + Website columns)
-- ----------------------------
DROP TABLE IF EXISTS `account_data`;
CREATE TABLE `account_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `ext_auth_name` varchar(45) DEFAULT NULL,
  `password` varchar(65) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activated` boolean NOT NULL DEFAULT TRUE,
  `access_level` tinyint NOT NULL DEFAULT '0',
  `membership` tinyint NOT NULL DEFAULT '0',
  `old_membership` tinyint NOT NULL DEFAULT '0',
  `last_server` tinyint NOT NULL DEFAULT '-1',
  `last_ip` varchar(20) DEFAULT NULL,
  `last_mac` varchar(20) NOT NULL DEFAULT 'xx-xx-xx-xx-xx-xx',
  `last_hdd_serial` varchar(100) DEFAULT NULL,
  `allowed_hdd_serial` varchar(100) DEFAULT NULL,
  `ip_force` varchar(20) DEFAULT NULL,
  `expire` date DEFAULT NULL,
  `confirmed` varchar(65) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `hash` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `toll` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `ext_auth_name` (`ext_auth_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Add new tables needed by website logic to the Login DB
-- ----------------------------
CREATE TABLE IF NOT EXISTS `account_security` (
  `id` int(11) NOT NULL,
  `email_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `email_confirm_date` datetime DEFAULT NULL,
  `question_1` varchar(100) DEFAULT NULL,
  `answer_1` varchar(50) DEFAULT NULL,
  `question_2` varchar(100) DEFAULT NULL,
  `answer_2` varchar(50) DEFAULT NULL,
  `questions_date` datetime DEFAULT NULL,
  `security_pin` int(4) DEFAULT NULL,
  `security_pin_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `aion_itemlist` (
  `item_id` int(11) unsigned NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `item_quality` varchar(25) DEFAULT NULL,
  `item_icon` varchar(50) DEFAULT NULL,
  `updated` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `aion_skilllist` (
  `skill_id` int(11) unsigned NOT NULL,
  `skill_name` varchar(100) DEFAULT NULL,
  `is_breakthrough` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`skill_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `lottery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `current_jackpot` int(10) unsigned NOT NULL,
  `current_stage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `start_timestamp` varchar(25) NOT NULL,
  `end_timestamp` varchar(25) NOT NULL,
  `win_n1` tinyint(2) unsigned DEFAULT NULL,
  `win_n2` tinyint(2) unsigned DEFAULT NULL,
  `win_n3` tinyint(2) unsigned DEFAULT NULL,
  `win_n4` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `lottery_stash` (
  `userid` int(11) NOT NULL,
  `credits` int(6) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `note` text,
  PRIMARY KEY (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `lottery_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lottery_id` varchar(25) NOT NULL,
  `userid` int(11) NOT NULL,
  `buydate` datetime NOT NULL,
  `number1` tinyint(2) unsigned NOT NULL,
  `number2` tinyint(2) unsigned NOT NULL,
  `number3` tinyint(2) unsigned NOT NULL,
  `number4` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
