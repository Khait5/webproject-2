/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.7.27 : Database - aioncms
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`aioncms` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `aioncms`;

/*Table structure for table `account_access` */

DROP TABLE IF EXISTS `account_access`;

CREATE TABLE `account_access` (
  `username` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `access_level` tinyint(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `item_tracker_list` */

DROP TABLE IF EXISTS `item_tracker_list`;

CREATE TABLE `item_tracker_list` (
  `item_unique_id` int(11) NOT NULL,
  `item_owner` varchar(45) COLLATE utf8_bin NOT NULL,
  `item_count` int(11) DEFAULT '1',
  `item_enchant` int(3) DEFAULT '0',
  `item_tempering` int(3) DEFAULT '0',
  `list_date` datetime NOT NULL,
  `last_change` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `item_tracker_logs` */

DROP TABLE IF EXISTS `item_tracker_logs`;

CREATE TABLE `item_tracker_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_unique_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `old_owner_account` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `old_owner_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `old_owner` int(11) DEFAULT NULL,
  `new_owner_account` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `new_owner_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_owner` int(11) DEFAULT NULL,
  `log_date` datetime NOT NULL,
  `log_type` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `mail_logs` */

DROP TABLE IF EXISTS `mail_logs`;

CREATE TABLE `mail_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sent_by` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `sent_by_ip` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `username` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `playername` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `itemid` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ban_id` int(11) NOT NULL,
  `ban_type` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `message` text,
  `username` varchar(45) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `online_count` */

DROP TABLE IF EXISTS `online_count`;

CREATE TABLE `online_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `count_siel` int(6) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `paymentwall` */

DROP TABLE IF EXISTS `paymentwall`;

CREATE TABLE `paymentwall` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `reference_id` varchar(50) DEFAULT NULL,
  `credits` int(6) DEFAULT NULL,
  `order_date` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `paymentwall_logs` */

DROP TABLE IF EXISTS `paymentwall_logs`;

CREATE TABLE `paymentwall_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(45) COLLATE utf8_bin NOT NULL,
  `currency` int(3) NOT NULL,
  `type` int(3) NOT NULL,
  `ref` varchar(100) COLLATE utf8_bin NOT NULL,
  `sig` varchar(32) COLLATE utf8_bin NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `paypal` */

DROP TABLE IF EXISTS `paypal`;

CREATE TABLE `paypal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(100) DEFAULT NULL,
  `payment_date` varchar(50) DEFAULT NULL,
  `payment_gross` float DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `custom` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `permanent_bans` */

DROP TABLE IF EXISTS `permanent_bans`;

CREATE TABLE `permanent_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(45) NOT NULL,
  `staff` varchar(25) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `proof` text,
  `date` datetime DEFAULT NULL,
  `last_message_by` varchar(45) DEFAULT NULL,
  `requires_action` tinyint(1) DEFAULT '0',
  `user_lock` tinyint(1) DEFAULT '0',
  `staff_lock` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `players_exchange_logs` */

DROP TABLE IF EXISTS `players_exchange_logs`;

CREATE TABLE `players_exchange_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `exchange_date` datetime NOT NULL,
  `exchange_amount` int(11) NOT NULL,
  `old_kinah` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `players_lastexchange` */

DROP TABLE IF EXISTS `players_lastexchange`;

CREATE TABLE `players_lastexchange` (
  `account_id` int(11) NOT NULL,
  `last_exchange` datetime NOT NULL,
  PRIMARY KEY (`account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `players_onlinetime` */

DROP TABLE IF EXISTS `players_onlinetime`;

CREATE TABLE `players_onlinetime` (
  `account_id` int(11) NOT NULL,
  `total_onlinetime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `profiles_legion` */

DROP TABLE IF EXISTS `profiles_legion`;

CREATE TABLE `profiles_legion` (
  `id` int(11) unsigned NOT NULL,
  `custom_message` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `custom_background` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_background` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `custom_font` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `custom_color` varchar(25) COLLATE utf8_bin DEFAULT 'gray',
  `youtube_video` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_cache` datetime DEFAULT NULL,
  `requires_approval` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `redeem_codes` */

DROP TABLE IF EXISTS `redeem_codes`;

CREATE TABLE `redeem_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `redeem_code` varchar(50) COLLATE utf8_bin NOT NULL,
  `redeem_type` varchar(50) COLLATE utf8_bin NOT NULL,
  `redeem_limit` int(6) DEFAULT NULL,
  `redeem_user` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `redeem_credit_amount` int(6) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `redeem_codes_logs` */

DROP TABLE IF EXISTS `redeem_codes_logs`;

CREATE TABLE `redeem_codes_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_id` int(11) NOT NULL,
  `date_redeemed` datetime NOT NULL,
  `user_identifier` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `referrals` */

DROP TABLE IF EXISTS `referrals`;

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) COLLATE utf8_bin NOT NULL,
  `userid` int(11) unsigned NOT NULL,
  `referral` varchar(45) COLLATE utf8_bin NOT NULL,
  `join_date` datetime NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `reward_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `superrewards_logs` */

DROP TABLE IF EXISTS `superrewards_logs`;

CREATE TABLE `superrewards_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) DEFAULT NULL,
  `user_id` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `offer_id` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `total` int(6) DEFAULT NULL,
  `signature` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `error` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `temp_passwords` */

DROP TABLE IF EXISTS `temp_passwords`;

CREATE TABLE `temp_passwords` (
  `name` varchar(45) NOT NULL,
  `current_password` varchar(65) NOT NULL,
  `temp_password` varchar(50) NOT NULL,
  `temp_date` datetime NOT NULL,
  `staff` varchar(50) NOT NULL,
  PRIMARY KEY (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `temporal_bans` */

DROP TABLE IF EXISTS `temporal_bans`;

CREATE TABLE `temporal_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(45) NOT NULL,
  `staff` varchar(25) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `proof` text,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `last_message_by` varchar(45) DEFAULT NULL,
  `requires_action` tinyint(1) DEFAULT '0',
  `user_lock` tinyint(1) DEFAULT '0',
  `staff_lock` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `ticket_banned_accounts` */

DROP TABLE IF EXISTS `ticket_banned_accounts`;

CREATE TABLE `ticket_banned_accounts` (
  `username` varchar(45) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `ticket_messages` */

DROP TABLE IF EXISTS `ticket_messages`;

CREATE TABLE `ticket_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  `username` varchar(45) COLLATE utf8_bin NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `tickets` */

DROP TABLE IF EXISTS `tickets`;

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) COLLATE utf8_bin NOT NULL,
  `username` varchar(45) COLLATE utf8_bin NOT NULL,
  `create_date` datetime NOT NULL,
  `last_reply_by` varchar(45) COLLATE utf8_bin NOT NULL,
  `last_reply_date` datetime NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

/*Table structure for table `unstick` */

DROP TABLE IF EXISTS `unstick`;

CREATE TABLE `unstick` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `player` varchar(50) NOT NULL,
  `race` varchar(25) NOT NULL,
  `account` varchar(45) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `votes` */

DROP TABLE IF EXISTS `votes`;

CREATE TABLE `votes` (
  `name` varchar(45) NOT NULL,
  `ip` varchar(45) NOT NULL DEFAULT '0.0.0.0',
  `site` tinyint(2) unsigned NOT NULL,
  `newdate` varchar(25) NOT NULL,
  `mac` varchar(20) DEFAULT '00-00-00-00-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `votes_count` */

DROP TABLE IF EXISTS `votes_count`;

CREATE TABLE `votes_count` (
  `id` int(11) DEFAULT NULL,
  `character` varchar(50) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `month` int(2) DEFAULT NULL,
  `votes` int(6) DEFAULT '0',
  `last_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `web_logs` */

DROP TABLE IF EXISTS `web_logs`;

CREATE TABLE `web_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(45) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `message` varchar(100) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `website_emails` */

DROP TABLE IF EXISTS `website_emails`;

CREATE TABLE `website_emails` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `filename` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `language` varchar(3) NOT NULL DEFAULT 'en',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Extraindo dados da tabela `website_emails`
--

INSERT INTO `website_emails` (`id`, `filename`, `subject`, `language`) VALUES
(1, 'VERIFY_EMAIL', 'Verify Your Email', 'en'),
(2, 'CHANGE_PWD', 'Change Password', 'en'),
(3, 'RECOVER_PWD', 'Recover Password', 'en'),
(4, 'RECOVER_USERNAME', 'Recover Username', 'en');

-- --------------------------------------------------------

/*Table structure for table `website_enchant_tokens` */

DROP TABLE IF EXISTS `website_enchant_tokens`;

CREATE TABLE `website_enchant_tokens` (
  `account_id` int(11) NOT NULL,
  `tokens` int(6) unsigned DEFAULT '0',
  PRIMARY KEY (`account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `website_modules` */

DROP TABLE IF EXISTS `website_modules`;

CREATE TABLE `website_modules` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `parent` varchar(25) DEFAULT NULL,
  `file` varchar(25) NOT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Table structure for table `website_modules` */
INSERT INTO `website_modules` VALUES (194, NULL, 'home', 1, 1);
INSERT INTO `website_modules` VALUES (195, NULL, '404', 1, 1);
INSERT INTO `website_modules` VALUES (196, NULL, 'connect', 1, 1);
INSERT INTO `website_modules` VALUES (197, NULL, 'donate', 1, 1);
INSERT INTO `website_modules` VALUES (198, NULL, 'forumevents', 1, 1);
INSERT INTO `website_modules` VALUES (200, NULL, 'ipcheck', 1, 1);
INSERT INTO `website_modules` VALUES (201, NULL, 'login', 1, 1);
INSERT INTO `website_modules` VALUES (202, NULL, 'logout', 1, 1);
INSERT INTO `website_modules` VALUES (203, NULL, 'lottery', 1, 1);
INSERT INTO `website_modules` VALUES (204, NULL, 'rankings', 1, 1);
INSERT INTO `website_modules` VALUES (205, NULL, 'recovery', 1, 1);
INSERT INTO `website_modules` VALUES (206, NULL, 'register', 1, 1);
INSERT INTO `website_modules` VALUES (207, NULL, 'rules', 1, 1);
INSERT INTO `website_modules` VALUES (208, NULL, 'shop', 1, 1);
INSERT INTO `website_modules` VALUES (209, NULL, 'support', 1, 1);
INSERT INTO `website_modules` VALUES (210, NULL, 'usercp', 2, 1);
INSERT INTO `website_modules` VALUES (211, 'usercp', 'account', 2, 1);
INSERT INTO `website_modules` VALUES (212, 'usercp', 'account', 2, 1);
INSERT INTO `website_modules` VALUES (213, 'usercp', 'boost', 2, 1);
INSERT INTO `website_modules` VALUES (214, 'usercp', 'changeskill', 2, 1);
INSERT INTO `website_modules` VALUES (215, 'usercp', 'characters', 2, 1);
INSERT INTO `website_modules` VALUES (216, 'usercp', 'email', 2, 1);
INSERT INTO `website_modules` VALUES (217, 'usercp', 'enchant', 2, 1);
INSERT INTO `website_modules` VALUES (218, 'usercp', 'enchantable', 2, 1);
INSERT INTO `website_modules` VALUES (219, 'usercp', 'inventory', 2, 1);
INSERT INTO `website_modules` VALUES (220, 'usercp', 'legionprofile', 2, 1);
INSERT INTO `website_modules` VALUES (221, 'usercp', 'password', 2, 1);
INSERT INTO `website_modules` VALUES (222, 'usercp', 'redeem', 2, 1);
INSERT INTO `website_modules` VALUES (223, 'usercp', 'redeemlogs', 2, 1);
INSERT INTO `website_modules` VALUES (224, 'usercp', 'referrals', 2, 1);
INSERT INTO `website_modules` VALUES (225, 'usercp', 'reset', 2, 1);
INSERT INTO `website_modules` VALUES (226, 'usercp', 'securitypin', 2, 1);
INSERT INTO `website_modules` VALUES (227, 'usercp', 'securityquestions', 2, 1);
INSERT INTO `website_modules` VALUES (228, 'usercp', 'timexchange', 2, 1);
INSERT INTO `website_modules` VALUES (229, 'usercp', 'unstuck', 2, 1);
INSERT INTO `website_modules` VALUES (230, 'usercp', 'upgrade', 2, 1);
INSERT INTO `website_modules` VALUES (231, 'usercp', 'validate', 2, 1);
INSERT INTO `website_modules` VALUES (232, 'usercp', 'verifyemail', 2, 1);
INSERT INTO `website_modules` VALUES (233, 'usercp', 'vote', 2, 1);
INSERT INTO `website_modules` VALUES (234, 'usercp', 'voteranking', 2, 1);
INSERT INTO `website_modules` VALUES (235, NULL, 'verification', 1, 1);
INSERT INTO `website_modules` VALUES (236, 'verification', 'email', 1, 1);
INSERT INTO `website_modules` VALUES (237, 'verification', 'forgotpassword', 1, 1);
INSERT INTO `website_modules` VALUES (238, 'verification', 'password', 1, 1);
INSERT INTO `website_modules` VALUES (239, NULL, 'tickets', 1, 1);
INSERT INTO `website_modules` VALUES (240, 'tickets', 'list', 1, 1);
INSERT INTO `website_modules` VALUES (241, 'tickets', 'new', 1, 1);
INSERT INTO `website_modules` VALUES (242, 'tickets', 'view', 1, 1);
INSERT INTO `website_modules` VALUES (243, 'shop', 'history', 1, 1);
INSERT INTO `website_modules` VALUES (244, 'shop', 'item', 1, 1);
INSERT INTO `website_modules` VALUES (245, 'shop', 'list', 1, 1);
INSERT INTO `website_modules` VALUES (246, 'recovery', 'password', 1, 1);
INSERT INTO `website_modules` VALUES (247, 'recovery', 'username', 1, 1);
INSERT INTO `website_modules` VALUES (248, 'rankings', 'abyss', 1, 1);
INSERT INTO `website_modules` VALUES (249, 'rankings', 'glory', 1, 1);
INSERT INTO `website_modules` VALUES (250, 'rankings', 'kills', 1, 1);
INSERT INTO `website_modules` VALUES (251, 'rankings', 'legions', 1, 1);
INSERT INTO `website_modules` VALUES (252, 'rankings', 'votes', 1, 1);
INSERT INTO `website_modules` VALUES (253, NULL, 'profile', 1, 1);
INSERT INTO `website_modules` VALUES (254, 'profile', 'legion', 1, 1);
INSERT INTO `website_modules` VALUES (255, 'lottery', 'buy', 1, 1);
INSERT INTO `website_modules` VALUES (256, 'lottery', 'results', 1, 1);
INSERT INTO `website_modules` VALUES (257, 'lottery', 'stash', 1, 1);
INSERT INTO `website_modules` VALUES (258, 'lottery', 'tickets', 1, 1);
INSERT INTO `website_modules` VALUES (259, 'donate', 'freekassa', 1, 1);
INSERT INTO `website_modules` VALUES (260, 'donate', 'paymentwall', 1, 1);
INSERT INTO `website_modules` VALUES (261, 'donate', 'paypal', 1, 1);
INSERT INTO `website_modules` VALUES (262, 'donate', 'superrewards', 1, 1);
INSERT INTO `website_modules` VALUES (263, 'bansystem', 'view', 1, 1);
INSERT INTO `website_modules` VALUES (264, NULL, 'admin', 1, 1);
INSERT INTO `website_modules` VALUES (265, 'admin', 'abysseditor', 2, 1);
INSERT INTO `website_modules` VALUES (266, 'admin', 'access', 2, 1);
INSERT INTO `website_modules` VALUES (267, 'admin', 'accounteditor', 2, 1);
INSERT INTO `website_modules` VALUES (268, 'admin', 'addlogs', 2, 1);
INSERT INTO `website_modules` VALUES (269, 'admin', 'ban', 2, 1);
INSERT INTO `website_modules` VALUES (270, 'admin', 'editaccesslvl', 2, 1);
INSERT INTO `website_modules` VALUES (271, 'admin', 'onlineplayers', 2, 1);
INSERT INTO `website_modules` VALUES (272, 'admin', 'paymentwall', 2, 1);
INSERT INTO `website_modules` VALUES (273, 'admin', 'paypal', 2, 1);
INSERT INTO `website_modules` VALUES (274, 'admin', 'playereditor', 2, 1);
INSERT INTO `website_modules` VALUES (275, 'admin', 'playerskills', 2, 1);
INSERT INTO `website_modules` VALUES (276, 'admin', 'redeemcodes', 2, 1);
INSERT INTO `website_modules` VALUES (277, 'admin', 'redeemcodeslogs', 2, 1);
INSERT INTO `website_modules` VALUES (278, 'admin', 'superrewards', 2, 1);
INSERT INTO `website_modules` VALUES (279, 'admin', 'tempaccess', 2, 1);
INSERT INTO `website_modules` VALUES (280, 'admin', 'topcredits', 2, 1);
INSERT INTO `website_modules` VALUES (281, 'admin', 'unban', 2, 1);
INSERT INTO `website_modules` VALUES (282, 'admin', 'weblogs', 2, 1);
INSERT INTO `website_modules` VALUES (283, 'admin', 'websessions', 2, 1);
INSERT INTO `website_modules` VALUES (284, NULL, 'bans', 2, 1);
INSERT INTO `website_modules` VALUES (285, 'bans', 'latest', 2, 1);
INSERT INTO `website_modules` VALUES (286, 'bans', 'list', 2, 1);
INSERT INTO `website_modules` VALUES (287, 'bans', 'new', 2, 1);
INSERT INTO `website_modules` VALUES (288, 'bans', 'search', 2, 1);
INSERT INTO `website_modules` VALUES (289, 'bans', 'view', 2, 1);
INSERT INTO `website_modules` VALUES (290, NULL, 'hgm', 1, 1);
INSERT INTO `website_modules` VALUES (291, 'hgm', 'emailsearch', 1, 1);
INSERT INTO `website_modules` VALUES (292, 'hgm', 'extrasec', 1, 1);
INSERT INTO `website_modules` VALUES (293, 'hgm', 'ipsearch', 1, 1);
INSERT INTO `website_modules` VALUES (294, 'hgm', 'macsearch', 1, 1);
INSERT INTO `website_modules` VALUES (295, 'hgm', 'staff', 1, 1);
INSERT INTO `website_modules` VALUES (296, 'hgm', 'tracker', 1, 1);
INSERT INTO `website_modules` VALUES (297, 'lottery', 'list', 1, 1);
INSERT INTO `website_modules` VALUES (298, 'lottery', 'similar', 1, 1);
INSERT INTO `website_modules` VALUES (299, 'lottery', 'stash', 1, 1);
INSERT INTO `website_modules` VALUES (300, 'lottery', 'status', 1, 1);
INSERT INTO `website_modules` VALUES (301, NULL, 'sam', 1, 1);
INSERT INTO `website_modules` VALUES (302, 'sam', 'legionprofiles', 1, 1);
INSERT INTO `website_modules` VALUES (303, 'sam', 'mailitems', 1, 1);
INSERT INTO `website_modules` VALUES (304, 'sam', 'online', 1, 1);
INSERT INTO `website_modules` VALUES (305, 'sam', 'referrals', 1, 1);
INSERT INTO `website_modules` VALUES (306, 'sam', 'unstickplayer', 1, 1);
INSERT INTO `website_modules` VALUES (307, 'sam', 'unstickrequest', 1, 1);
INSERT INTO `website_modules` VALUES (308, 'tickets', 'banned', 1, 1);
INSERT INTO `website_modules` VALUES (309, 'tickets', 'closed', 1, 1);
INSERT INTO `website_modules` VALUES (310, 'tickets', 'open', 1, 1);
INSERT INTO `website_modules` VALUES (311, 'tickets', 'view', 1, 1);
INSERT INTO `website_modules` VALUES (312, NULL, 'tools', 1, 1);
INSERT INTO `website_modules` VALUES (313, 'tools', 'accountdata', 1, 1);
INSERT INTO `website_modules` VALUES (314, 'tools', 'legiondetails', 1, 1);
INSERT INTO `website_modules` VALUES (315, 'tools', 'playerdetails', 1, 1);
INSERT INTO `website_modules` VALUES (316, NULL, 'webshop', 1, 1);
INSERT INTO `website_modules` VALUES (317, 'webshop', 'add', 1, 1);
INSERT INTO `website_modules` VALUES (318, 'webshop', 'categories', 1, 1);
INSERT INTO `website_modules` VALUES (319, 'webshop', 'edit', 1, 1);
INSERT INTO `website_modules` VALUES (320, 'webshop', 'items', 1, 1);
INSERT INTO `website_modules` VALUES (321, 'webshop', 'logs', 1, 1);
INSERT INTO `website_modules` VALUES (322, 'webshop', 'specialshoplist', 1, 1);
INSERT INTO `website_modules` VALUES (323, NULL, 'info', 1, 1);

/*Table structure for table `website_session_control` */

DROP TABLE IF EXISTS `website_session_control`;

CREATE TABLE `website_session_control` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `session_id` varchar(64) NOT NULL,
  `last_location` varchar(255) NOT NULL,
  `active` datetime NOT NULL,
  `session_ip` varchar(70) NOT NULL,
  PRIMARY KEY (`session_id`) USING BTREE,
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `website_shop_categories` */

DROP TABLE IF EXISTS `website_shop_categories`;

CREATE TABLE `website_shop_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) unsigned DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `order` int(6) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Extraindo dados da tabela `website_shop_categories`
--

INSERT INTO `website_shop_categories` (`id`, `parent`, `title`, `order`, `status`) VALUES
(1, NULL, 'Armor', NULL, 1),
(2, 1, 'Cloth', NULL, 0),
(3, 1, 'Leather', NULL, 0),
(4, 1, 'Chain', NULL, 0),
(5, 1, 'Plate', NULL, 0),
(6, 1, 'Helms', NULL, 0),
(7, 1, 'Shields', NULL, 0),
(8, NULL, 'weapons', NULL, 0),
(9, 8, 'weapon', NULL, 0),
(10, 8, 'extendables', NULL, 0),
(11, NULL, 'accessories', NULL, 0),
(12, 11, 'Rings', NULL, 0),
(13, 11, 'Earrings', NULL, 0),
(14, 11, 'Wings', NULL, 0),
(15, 11, 'Necklaces', NULL, 0),
(16, 11, 'Belts', NULL, 0),
(17, NULL, 'Consumables', NULL, 0),
(18, 17, 'Scrolls', NULL, 1),
(19, 17, 'Candies', NULL, 1),
(20, 17, 'Potions', NULL, 0),
(21, 17, 'Idian', NULL, 1),
(22, 17, 'Dyes', NULL, 1),
(23, NULL, 'Stables', NULL, 0),
(24, 23, 'Pets', NULL, 1),
(25, 23, 'Sidekicks', NULL, 1),
(26, 23, 'Mounts', NULL, 1),
(27, NULL, 'Miscellaneous', NULL, 0),
(28, 27, 'Titles', NULL, 1),
(29, 27, 'Tickets', NULL, 1),
(30, 27, 'Kisks', NULL, 1),
(31, 27, 'Emotes', NULL, 0),
(32, 27, 'Skill Skins', NULL, 1),
(33, NULL, 'Modification', NULL, 0),
(34, 33, 'Godstones', NULL, 1),
(35, 33, 'Manastone: dual stats', NULL, 1),
(36, 33, 'Manastone: archdaeva', NULL, 1),
(37, 33, 'Enchanting', NULL, 1),
(38, NULL, 'Remodels', NULL, 0),
(39, 38, 'Weapons', NULL, 0),
(40, NULL, 'Housing', NULL, 0),
(41, 40, 'Lamps', NULL, 1),
(42, 40, 'Tables', NULL, 1),
(43, 40, 'Wallpaper', NULL, 1),
(44, 40, 'Seating', NULL, 1),
(45, 40, 'Bedding', NULL, 1),
(46, 40, 'Carpeting', NULL, 1),
(47, 40, 'Interior Decorations', NULL, 1),
(48, 40, 'Storage', NULL, 1),
(49, 40, 'Exterior Decorations', NULL, 1),
(50, 40, 'Misc', NULL, 1),
(51, 27, 'motion', NULL, 1),
(52, 27, 'tiamat bloody tears', NULL, 1),
(53, 8, 'shadow pact weapons', NULL, 0),
(54, 27, 'kinah', NULL, 1),
(55, 11, 'plumes', NULL, 0),
(56, 11, 'bracelets', NULL, 0),
(57, 11, 'plumes', NULL, 0),
(58, 11, 'essence core', NULL, 0),
(59, 23, 'minions', NULL, 1),
(60, 33, 'manastone: acient', NULL, 0),
(61, 23, 'ring of ancient\'s', NULL, 0),
(62, 38, 'costume-58', NULL, 0),
(63, 38, 'hat-58', NULL, 0),
(64, 33, 'manastone: ancient', NULL, 0),
(65, 27, 'level reduction stone', NULL, 1),
(66, 33, 'ancient manastones', NULL, 0),
(67, 1, 'clothing', NULL, 1),
(68, 11, 'headgear', NULL, 0),
(69, NULL, 'weapon', NULL, 1),
(70, 69, 'sword', NULL, 1),
(71, 69, 'dagger', NULL, 1),
(72, 69, 'mace', NULL, 1),
(73, 69, 'orb', NULL, 1),
(74, 69, 'spellbook', NULL, 1),
(75, 69, 'greatsword', NULL, 1),
(76, 69, 'polearm', NULL, 1),
(77, 69, 'staff', NULL, 1),
(78, 69, 'bow', NULL, 1),
(79, 69, 'pistol', NULL, 1),
(80, 69, 'aethercannon', NULL, 1),
(81, 69, 'cipher-blade', NULL, 1),
(82, 69, 'harp', NULL, 1),
(83, NULL, 'armor', NULL, 0),
(84, NULL, 'wings', NULL, 0),
(85, NULL, 'accessory', NULL, 0),
(86, NULL, 'skill related', NULL, 0),
(87, NULL, 'home décor', NULL, 0),
(88, NULL, 'furniture', NULL, 0),
(89, NULL, 'craft', NULL, 0),
(90, NULL, 'consumables', NULL, 0),
(91, NULL, 'other', NULL, 0),
(92, NULL, 'event', NULL, 1),
(93, 92, 'joker\'s wild', NULL, 1),
(94, 92, 'beginner\'s event', NULL, 1);

-- --------------------------------------------------------

/*Table structure for table `website_shop_items` */

DROP TABLE IF EXISTS `website_shop_items`;

CREATE TABLE `website_shop_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT '1',
  `cost` int(6) NOT NULL DEFAULT '1000',
  `category` int(6) NOT NULL,
  `enchantment` int(3) unsigned NOT NULL DEFAULT '0',
  `temperance` int(3) unsigned NOT NULL DEFAULT '0',
  `order` int(6) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Extraindo dados da tabela `website_shop_items`
--

INSERT INTO `website_shop_items` (`id`, `item_id`, `name`, `count`, `cost`, `category`, `enchantment`, `temperance`, `order`, `status`) VALUES
(1, 186000202, 'test', 1, 1000, 67, 0, 0, NULL, 1);

-- --------------------------------------------------------

/*Table structure for table `website_shop_logs` */

DROP TABLE IF EXISTS `website_shop_logs`;

CREATE TABLE `website_shop_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acc` varchar(45) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `item` int(11) DEFAULT NULL,
  `uid` int(6) DEFAULT NULL,
  `time` varchar(25) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `weeklyspecial_activeitems` */

DROP TABLE IF EXISTS `weeklyspecial_activeitems`;

CREATE TABLE `weeklyspecial_activeitems` (
  `item_id` int(11) unsigned NOT NULL,
  `quantity` int(6) NOT NULL,
  `cost` int(6) NOT NULL,
  `item_qty` int(6) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*Table structure for table `weeklyspecial_itemlist` */

DROP TABLE IF EXISTS `weeklyspecial_itemlist`;

CREATE TABLE `weeklyspecial_itemlist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL,
  `qty` int(6) DEFAULT '1',
  `cost` int(6) DEFAULT '9999',
  `chance` int(6) DEFAULT '0',
  `limit` int(6) DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Extraindo dados da tabela `weeklyspecial_itemlist`
--

INSERT INTO `weeklyspecial_itemlist` (`id`, `item_id`, `qty`, `cost`, `chance`, `limit`) VALUES
(1, 186000202, 1, 500, 0, 0);

-- --------------------------------------------------------

/*Table structure for table `weeklyspecial_logs` */

DROP TABLE IF EXISTS `weeklyspecial_logs`;

CREATE TABLE `weeklyspecial_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_name` varchar(45) DEFAULT NULL,
  `player_name` varchar(50) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_cost` int(6) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `purchase_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
