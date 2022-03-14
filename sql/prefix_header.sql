-- ----------------------------
--  Table structure for `{{%form_category}}`
-- ----------------------------
CREATE TABLE `{{%header_category}}` (
  `key` varchar(100) NOT NULL COMMENT '表头标记',
  `name` varchar(100) NOT NULL COMMENT '表头标志别名',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '表头描述',
  `sort_order` int(8) unsigned NOT NULL DEFAULT '1000' COMMENT '排序',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开放表头，否时非超级管理员不可操作（不可见）',
  PRIMARY KEY (`key`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `idx_sortOrder` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='表头设置分类';


-- ----------------------------
--  Table structure for `{{%header_option}}`
-- ----------------------------
CREATE TABLE `{{%header_option}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `key` varchar(100) NOT NULL COMMENT '所属表头标记',
  `field` varchar(60) NOT NULL COMMENT '字段名',
  `label` varchar(50) NOT NULL COMMENT '显示名',
  `width` varchar(20) NOT NULL DEFAULT '' COMMENT '固定宽度',
  `fixed` varchar(20) NOT NULL DEFAULT '' COMMENT '列固定:[left,right,""]',
  `default` varchar(100) NOT NULL DEFAULT ' - ' COMMENT '默认值,当字段没有是返回，基本无用',
  `align` varchar(20) NOT NULL DEFAULT 'center' COMMENT '表格内容对齐方式:[center,left,right]',
  `is_tooltip` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '当内容过长被隐藏时显示 tooltip',
  `is_resizable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '对应列是否可以通过拖动改变宽度',
  `is_editable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '当为编辑表格时，字段是否可在table中编辑',
  `component` varchar(60) NOT NULL DEFAULT '' COMMENT '使用组件',
  `options` json DEFAULT NULL COMMENT '字段选项映射关系',
  `params` json DEFAULT NULL COMMENT '参数内容',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '分类排序',
  `is_required` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否必选，为"是"时不能没取消',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否默认开启',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `operate_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '操作IP',
  `operate_uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '操作UID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key_field` (`key`,`field`),
  UNIQUE KEY `uk_key_label` (`key`,`label`),
  KEY `idx_sortOrder` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='表头配置选项';


