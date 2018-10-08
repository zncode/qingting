/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : nongjia

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2018-10-08 16:01:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for nj_category
-- ----------------------------
DROP TABLE IF EXISTS `nj_category`;
CREATE TABLE `nj_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT '父级id',
  `channel_id` int(11) NOT NULL COMMENT '频道id',
  `name` varchar(255) NOT NULL COMMENT '栏目名称',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态0=关闭，1=开放',
  `weight` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `keyword` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` text COMMENT '描述',
  `delete` int(11) NOT NULL COMMENT '1=删除',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nj_category
-- ----------------------------
INSERT INTO `nj_category` VALUES ('1', '0', '1', '1133', '1', '1', '1', '111', '0', '2018-05-14 11:53:52', '2018-05-14 17:37:00');
INSERT INTO `nj_category` VALUES ('2', '1', '1', '12', '1', '1', '1', '11', '0', '2018-05-14 13:48:43', '0000-00-00 00:00:00');
INSERT INTO `nj_category` VALUES ('3', '0', '1', '每日养眼', '1', '1', '每日养眼', '每日养眼每日养眼每日养眼', '0', '2018-05-14 13:54:30', '0000-00-00 00:00:00');
INSERT INTO `nj_category` VALUES ('4', '0', '1', '趣味植物', '1', '1', '1', '趣味植物', '0', '2018-05-14 13:56:13', '2018-05-14 15:57:49');
INSERT INTO `nj_category` VALUES ('5', '0', '1', '植物新闻', '1', '1', '1', '植物新闻', '0', '2018-05-14 15:41:52', '2018-05-14 15:58:12');
INSERT INTO `nj_category` VALUES ('6', '0', '1', '植物科学', '1', '1', '1', '植物科学', '0', '2018-05-14 15:42:55', '2018-05-14 15:58:31');
INSERT INTO `nj_category` VALUES ('7', '4', '1', 'test1', '1', '1', '1', '1', '0', '2018-05-14 15:58:56', '2018-05-14 16:44:26');
INSERT INTO `nj_category` VALUES ('8', '6', '1', 'test1', '1', '1', '1', '1', '0', '2018-05-14 17:20:10', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for nj_channel
-- ----------------------------
DROP TABLE IF EXISTS `nj_channel`;
CREATE TABLE `nj_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '频道名称',
  `status` int(1) NOT NULL,
  `path` varchar(255) NOT NULL COMMENT '访问路径',
  `weight` int(10) NOT NULL COMMENT '比重',
  `keyword` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` text COMMENT '描述',
  `delete` int(1) NOT NULL DEFAULT '0' COMMENT '1=删除',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nj_channel
-- ----------------------------
INSERT INTO `nj_channel` VALUES ('1', '文章', '0', 'article', '1', '内容, 文章', '内容文章', '0', '2018-05-11 16:42:44', '2018-05-11 16:47:57');
INSERT INTO `nj_channel` VALUES ('2', 'test11', '0', 'test', '12', 'test', 'testestest', '0', '2018-09-17 16:20:57', '2018-09-17 17:16:52');
INSERT INTO `nj_channel` VALUES ('3', 'test11112', '0', 't2', '0', 't2', '1112223333213213', '0', '2018-09-17 16:21:48', '2018-09-17 17:16:42');
INSERT INTO `nj_channel` VALUES ('4', '1212333', '0', '1212', '1212', '1212', '1212', '0', '2018-09-17 17:18:57', '2018-09-18 11:13:53');
INSERT INTO `nj_channel` VALUES ('5', '331', '0', '33', '33', '33', '33', '1', '2018-09-17 17:19:08', '2018-09-17 17:26:30');
INSERT INTO `nj_channel` VALUES ('6', '111222', '0', '111', '1', '111', '111', '0', '2018-10-08 14:03:05', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for nj_user
-- ----------------------------
DROP TABLE IF EXISTS `nj_user`;
CREATE TABLE `nj_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '判断是否登录的session_id',
  `role_id` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '角色id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '默认是正常状态,0为禁止',
  `delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除 1正常 2已删除',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(16) NOT NULL DEFAULT '' COMMENT '邮箱',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `address` varchar(16) NOT NULL DEFAULT '' COMMENT '地址',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别 1男2女',
  `register_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=手机号 2=微信 3=qq',
  `register_account` varchar(16) NOT NULL DEFAULT '' COMMENT '注册账号',
  `register_source` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '注册来源:1=PC, 2=IOS, 3=Android, 4=后台添加,5=webapp',
  `register_ip` varchar(100) NOT NULL DEFAULT '0' COMMENT '注册ip',
  `login_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '登录方式:0未知1网站,2APP,3微信,4,Android,5iOS,6QQ登录 ',
  `login_device_id` varchar(32) NOT NULL DEFAULT '0' COMMENT 'App最后登录的设备ID',
  `login_app_version` varchar(16) NOT NULL DEFAULT '0' COMMENT 'App最后登录的版本号',
  `login_mobile_models` varchar(64) NOT NULL DEFAULT '0' COMMENT 'App最后登录的手机型号',
  `login_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `login_last_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `login_last_ip` varchar(100) NOT NULL DEFAULT '0' COMMENT '最后登陆ip',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '注册时间',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE,
  KEY `register_ip` (`register_ip`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `delete` (`delete`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of nj_user
-- ----------------------------
INSERT INTO `nj_user` VALUES ('1', '0', '1', '1', '0', '13520651050', 'c4ca4238a0b923820dcc509a6f75849b', '', '1', '', '', '1', '1', '13520651050', '1', '::1', '0', '0', '0', '0', '0', '0', '0', '2018-09-18 16:12:36', '0000-00-00 00:00:00');
INSERT INTO `nj_user` VALUES ('2', '0', '1', '1', '0', '13520651051', '6512bd43d9caa6e02c990b0a82652dca', '', '11', '', '', '1', '1', '13520651051', '1', '0.0.0.0', '0', '0', '0', '0', '0', '0', '0', '2018-09-18 16:27:21', '0000-00-00 00:00:00');
INSERT INTO `nj_user` VALUES ('3', '0', '1', '1', '0', '13511111113', 'c4ca4238a0b923820dcc509a6f75849b', '', '1', '', '', '1', '1', '13511111113', '1', '0.0.0.0', '0', '0', '0', '0', '0', '0', '0', '2018-10-08 14:10:23', '0000-00-00 00:00:00');
