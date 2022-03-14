-- ----------------------------
--  Table structure for `{{%block_category}}`
-- ----------------------------
CREATE TABLE `{{%block_category}}` (
  `key` varchar(100) NOT NULL COMMENT '引用标识',
  `type` varchar(20) NOT NULL DEFAULT '1' COMMENT '页面区块类型[content, image-link, cloud-words, cloud-words-links, list, list-links, images, image-links]',
  `name` varchar(100) NOT NULL COMMENT '区块名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '区块描述',
  `sort_order` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `is_open` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否开放，否时管理员不可操作（不可见）',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '启用状态',
  `src` varchar(200) NOT NULL DEFAULT '' COMMENT 'type为image-link时，为图片地址',
  `content` text COMMENT 'type为content时存放内容，为image-link时存放图片链接',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`key`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `idx_type` (`type`),
  KEY `idx_isOpen` (`is_open`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站页面区块类型';

-- ----------------------------
--  Table structure for `{{%block_option}}`
-- ----------------------------
CREATE TABLE `{{%block_option}}` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `key` varchar(100) NOT NULL COMMENT '区块标识',
  `label` varchar(100) NOT NULL DEFAULT '' COMMENT '链接显示名称',
  `link` varchar(200) NOT NULL DEFAULT '' COMMENT '链接地址',
  `src` varchar(200) NOT NULL DEFAULT '' COMMENT '图片地址',
  `sort_order` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `is_enable` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否启用发布显示',
  `is_blank` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '如果为链接，是否新开窗口',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key_label` (`key`,`label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='页面区块详情';
