-- ----------------------------
-- Adding extra columns for `inventory`
-- ----------------------------
ALTER TABLE `inventory` ADD COLUMN `authorize` int(11) NOT NULL DEFAULT '0';
