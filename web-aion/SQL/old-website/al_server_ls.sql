-- ----------------------------
-- Table structure for `account_data`
-- ----------------------------
DROP TABLE IF EXISTS `account_data`;
CREATE TABLE `account_data` (
  `confirmed` varchar(65) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
  `email` varchar(60) DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `hash` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
) ENGINE=InnoDB AUTO_INCREMENT=11029 DEFAULT CHARSET=utf8;