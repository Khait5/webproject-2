-- ----------------------------
-- Table structure for `web_reward`
-- ----------------------------
DROP TABLE IF EXISTS `web_reward`;
CREATE TABLE `web_reward` (
  `unique` int(11) NOT NULL AUTO_INCREMENT,
  `item_owner` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_count` decimal(20,0) NOT NULL DEFAULT '1',
  `rewarded` tinyint(1) NOT NULL DEFAULT '0',
  `added` varchar(70) DEFAULT '',
  `received` varchar(70) DEFAULT '',
  PRIMARY KEY (`unique`),
  KEY `item_owner` (`item_owner`),
  CONSTRAINT `web_reward_ibfk_1` FOREIGN KEY (`item_owner`) REFERENCES `players` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for inventory
-- ----------------------------
DROP TABLE IF EXISTS `inventory`;
  `authorize` int(11) NOT NULL DEFAULT '0',
) ENGINE=InnoDB DEFAULT CHARSET=utf8;