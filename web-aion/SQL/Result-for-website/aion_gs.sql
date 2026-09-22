-- ----------------------------
-- inventory (Merged with BeyondAion structure + Website columns)
-- ----------------------------
DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory` (
  `item_unique_id` int NOT NULL,
  `item_id` int NOT NULL,
  `item_count` bigint NOT NULL DEFAULT '0',
  `item_color` mediumint unsigned DEFAULT NULL,
  `color_expires` int NOT NULL DEFAULT '0',
  `item_creator` varchar(50) DEFAULT NULL,
  `expire_time` int NOT NULL DEFAULT '0',
  `activation_count` int NOT NULL DEFAULT '0',
  `item_owner` int NOT NULL,
  `is_equipped` boolean NOT NULL DEFAULT '0',
  `is_soul_bound` boolean NOT NULL DEFAULT '0',
  `slot` bigint NOT NULL DEFAULT '0',
  `item_location` tinyint DEFAULT '0',
  `enchant` tinyint unsigned NOT NULL DEFAULT '0',
  `enchant_bonus` tinyint NOT NULL DEFAULT '0',
  `item_skin` int NOT NULL DEFAULT '0',
  `fusioned_item` int NOT NULL DEFAULT '0',
  `optional_socket` tinyint unsigned NOT NULL DEFAULT '0',
  `optional_fusion_socket` tinyint unsigned NOT NULL DEFAULT '0',
  `charge` mediumint NOT NULL DEFAULT '0',
  `tune_count` smallint NOT NULL DEFAULT '0',
  `rnd_bonus` smallint NOT NULL DEFAULT '0',
  `fusion_rnd_bonus` smallint NOT NULL DEFAULT '0',
  `tempering` tinyint unsigned NOT NULL DEFAULT '0',
  `pack_count` smallint NOT NULL DEFAULT '0',
  `is_amplified` boolean NOT NULL DEFAULT '0',
  `buff_skill` int NOT NULL DEFAULT '0',
  `rnd_plume_bonus` smallint NOT NULL DEFAULT '0',
  `rank_limit_expire_time` int NOT NULL DEFAULT '0',
  `authorize` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_unique_id`),
  KEY `item_location` (`item_location`),
  KEY `index3` (`item_owner`,`item_location`,`is_equipped`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
