/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : qingting

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2018-11-16 15:56:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for qt_article
-- ----------------------------
DROP TABLE IF EXISTS `qt_article`;
CREATE TABLE `qt_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL COMMENT '频道',
  `category_1` int(11) NOT NULL COMMENT '栏目1',
  `category_2` int(11) NOT NULL COMMENT '栏目2',
  `user_id` int(11) NOT NULL COMMENT '文章作者',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态 0=关闭 1=开放 2=推荐首页',
  `meta_keyword` varchar(255) NOT NULL DEFAULT '' COMMENT 'Meta 关键字',
  `meta_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Meta 描述',
  `summary` varchar(255) NOT NULL DEFAULT '' COMMENT '摘要',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `url` varchar(255) NOT NULL,
  `content` text NOT NULL COMMENT '内容',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `delete` int(1) NOT NULL DEFAULT '0' COMMENT '1=删除',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qt_article
-- ----------------------------
INSERT INTO `qt_article` VALUES ('1', '20', '0', '0', '11', '2', '11', '11', '11', '11122', '', '11', '', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `qt_article` VALUES ('2', '6', '0', '0', '112', '1', '11', '11', '11', '1112', '', '11', '', '1', '0000-00-00 00:00:00', '2018-05-10 16:07:21');
INSERT INTO `qt_article` VALUES ('3', '20', '0', '0', '11', '2', '11', '11', '111', '11', '', '11', '', '1', '0000-00-00 00:00:00', '2018-05-10 16:07:41');
INSERT INTO `qt_article` VALUES ('4', '6', '0', '0', '11', '1', '11', '11', '11', '121', '', '11', '', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `qt_article` VALUES ('5', '1', '1', '0', '121', '1', '新浪', '新浪', '新浪', '新浪', 'http://www.sina.com.cn', '新浪', '166', '0', '2018-05-10 16:08:00', '2018-11-14 14:08:34');
INSERT INTO `qt_article` VALUES ('6', '1', '1', '0', '11', '1', '网易', '网易', '网易', '网易', 'http://www.163.com', '网易', '133', '0', '2018-05-10 16:22:25', '2018-11-13 15:44:50');
INSERT INTO `qt_article` VALUES ('7', '1', '1', '0', '11', '1', '凤凰网', '凤凰网', '凤凰网', '凤凰网', 'http://www.ifeng.com', '凤凰网', '134', '0', '2018-05-10 16:22:36', '2018-11-13 15:45:54');
INSERT INTO `qt_article` VALUES ('8', '1', '1', '0', '11', '1', '腾讯', '腾讯', '腾讯', '腾讯', 'http://www.qq.com', '腾讯', '135', '0', '2018-05-10 16:23:07', '2018-11-13 15:47:20');
INSERT INTO `qt_article` VALUES ('9', '1', '1', '0', '11', '1', '人民网', '人民网', '人民网', '人民网', 'http://www.peopledaily.com.cn/', '人民网', '136', '0', '2018-05-10 16:23:18', '2018-11-13 15:47:56');
INSERT INTO `qt_article` VALUES ('10', '1', '1', '0', '1', '1', '新华网', '新华网', '新华网', '新华网', 'http://www.xinhuanet.com/', '新华网', '137', '0', '2018-05-10 16:23:31', '2018-11-13 15:48:25');
INSERT INTO `qt_article` VALUES ('11', '1', '1', '0', '11', '1', '中国网', '中国网', '中国网', '中国网', 'http://www.china.com.cn/', '中国网', '138', '0', '2018-05-10 16:23:51', '2018-11-13 15:48:59');
INSERT INTO `qt_article` VALUES ('12', '1', '1', '0', '13', '1', '光明网', '光明网', '光明网', '光明网', 'http://www.gmw.cn/', '光明网', '167', '0', '2018-05-10 16:24:04', '2018-11-14 14:08:47');
INSERT INTO `qt_article` VALUES ('13', '1', '1', '0', '12', '1', '环球网', '环球网', '环球网', '环球网', 'http://www.huanqiu.com/', '环球网', '139', '0', '2018-05-10 16:24:16', '2018-11-13 15:50:28');
INSERT INTO `qt_article` VALUES ('14', '1', '1', '0', '12', '1', '大公网', '大公网', '大公网', '大公网', 'http://www.takungpao.com/', '大公网', '141', '0', '2018-05-10 16:24:26', '2018-11-14 11:54:06');
INSERT INTO `qt_article` VALUES ('15', '1', '1', '0', '121', '1', '求是网', '求是网', '求是网', '求是网', 'http://www.qstheory.cn/', '求是网', '140', '0', '2018-05-11 17:07:32', '2018-11-14 11:54:19');
INSERT INTO `qt_article` VALUES ('16', '1', '1', '0', '11', '1', '今日头条', '今日头条', '今日头条', '今日头条', 'https://www.toutiao.com/', '<p>\n	今日头条\n</p>\n<p>\n	<br />\n</p>', '142', '0', '2018-05-17 10:54:44', '2018-11-14 11:55:47');
INSERT INTO `qt_article` VALUES ('17', '1', '1', '0', '11', '1', 'Zaker', 'Zaker', 'Zaker', 'Zaker', 'http://www.myzaker.com/', 'Zaker', '143', '0', '2018-05-17 15:03:56', '2018-11-14 11:56:26');
INSERT INTO `qt_article` VALUES ('18', '6', '0', '0', '0', '1', '11112', '22212', '22212', 'aaa12', '', '22212312312', '', '1', '2018-10-16 10:13:48', '2018-10-16 10:37:20');
INSERT INTO `qt_article` VALUES ('19', '3', '0', '0', '0', '1', '澎湃新闻', '澎湃新闻', '澎湃新闻', '澎湃新闻', 'http://www.thepaper.cn/', '<a href=\"http://www.thepaper.cn/\" target=\"_blank\">澎湃新闻</a>', '144', '0', '2018-10-16 10:45:13', '2018-11-14 11:56:51');
INSERT INTO `qt_article` VALUES ('20', '0', '0', '0', '0', '1', '', '', '', '', '', '', '', '1', '2018-10-17 10:36:45', '0000-00-00 00:00:00');
INSERT INTO `qt_article` VALUES ('21', '0', '0', '0', '0', '1', '', '', '', '', '', '', '', '1', '2018-10-17 11:01:18', '0000-00-00 00:00:00');
INSERT INTO `qt_article` VALUES ('22', '3', '0', '0', '0', '1', '界面新闻', '界面新闻', '界面新闻', '界面新闻', 'https://www.jiemian.com/', '<a href=\"http://www.jiemian.com/\" target=\"_blank\">界面新闻</a>', '145', '0', '2018-10-17 13:40:59', '2018-11-14 11:57:18');
INSERT INTO `qt_article` VALUES ('23', '1', '1', '0', '0', '1', '微博', '微博', '微博', '微博', 'https://weibo.com/', '<a href=\"http://weibo.com/\" target=\"_blank\">微博</a>', '146', '0', '2018-10-17 13:47:00', '2018-11-14 11:57:47');
INSERT INTO `qt_article` VALUES ('24', '7', '0', '0', '0', '1', '简书', '简书', '简书', '简书', 'https://www.jianshu.com/', '<a href=\"http://www.jianshu.com/\" target=\"_blank\">简书</a>', '147', '0', '2018-10-17 13:50:04', '2018-11-14 11:59:10');
INSERT INTO `qt_article` VALUES ('25', '7', '0', '0', '0', '1', '读书网', '读书网', '读书网', '读书网', 'https://www.dushu.com/', '<a href=\"http://www.dushu.com/\" target=\"_blank\">读书网</a>', '148', '0', '2018-10-17 13:51:13', '2018-11-14 11:59:44');
INSERT INTO `qt_article` VALUES ('26', '7', '0', '0', '0', '1', '乐读网', '乐读网', '乐读网', '乐读网', 'http://www.ledu365.com', '乐读网', '149', '0', '2018-10-17 13:53:27', '2018-11-14 12:00:18');
INSERT INTO `qt_article` VALUES ('27', '8', '0', '0', '0', '1', '博客园', '博客园', '博客园', '博客园', 'https://www.cnblogs.com/', '<a href=\"http://www.cnblogs.com/\" target=\"_blank\">博客园</a>', '150', '0', '2018-10-17 13:57:22', '2018-11-14 12:01:21');
INSERT INTO `qt_article` VALUES ('28', '0', '0', '0', '0', '1', '', '', '', '', '', '', '', '1', '2018-10-17 13:57:48', '0000-00-00 00:00:00');
INSERT INTO `qt_article` VALUES ('29', '8', '0', '0', '0', '1', '博客中国', '博客中国', '博客中国', '博客中国', 'http://www.blogchina.com/', '<a href=\"http://www.blogchina.com/\" target=\"_blank\">博客中国</a>', '151', '0', '2018-10-17 14:01:08', '2018-11-14 13:23:39');
INSERT INTO `qt_article` VALUES ('30', '8', '0', '0', '0', '1', '博客大巴', '博客大巴', '博客大巴', '博客大巴', 'http://www.blogbus.com/', '<a href=\"http://www.blogbus.com/\" target=\"_blank\">博客大巴</a>', '152', '0', '2018-10-17 14:04:10', '2018-11-14 13:24:16');
INSERT INTO `qt_article` VALUES ('31', '8', '0', '0', '0', '1', '博客网', '博客网', '博客网', '博客网', 'http://www.bokee.com/', '<a href=\"http://www.bokee.com/\" target=\"_blank\">博客网</a>', '153', '0', '2018-10-17 14:13:43', '2018-11-14 13:24:48');
INSERT INTO `qt_article` VALUES ('32', '8', '0', '0', '0', '1', '博客日报', '博客日报', '博客日报', '博客日报', 'http://www.bokerb.com/', '<a href=\"http://www.bokerb.com/\" target=\"_blank\">博客日报</a>', '154', '0', '2018-10-17 14:29:40', '2018-11-14 13:25:25');
INSERT INTO `qt_article` VALUES ('33', '4', '0', '0', '0', '1', '月光博客', '月光博客', '月光博客', '月光博客', 'https://www.williamlong.info/', '<a href=\"http://www.williamlong.info/\" target=\"_blank\">月光博客</a>', '155', '0', '2018-10-17 14:31:55', '2018-11-14 13:25:59');
INSERT INTO `qt_article` VALUES ('34', '8', '0', '0', '0', '1', '卢松松博客', '卢松松博客', '卢松松博客', '卢松松博客', 'http://lusongsong.com/', '<a href=\"http://lusongsong.com/\" target=\"_blank\">卢松松博客</a>', '156', '0', '2018-10-17 14:53:52', '2018-11-14 13:26:27');
INSERT INTO `qt_article` VALUES ('35', '2', '12', '0', '0', '1', '起点中文网', '起点中文网', '起点中文网', '起点中文网', 'https://www.qidian.com/', '起点中文网', '157', '0', '2018-10-17 14:54:25', '2018-11-14 13:26:59');
INSERT INTO `qt_article` VALUES ('36', '0', '0', '0', '0', '1', '', '', '', '', '', '', '35', '1', '2018-10-17 15:27:05', '0000-00-00 00:00:00');
INSERT INTO `qt_article` VALUES ('37', '0', '0', '0', '0', '1', '', '', '', '', '', '', '36', '1', '2018-10-17 15:28:13', '0000-00-00 00:00:00');
INSERT INTO `qt_article` VALUES ('38', '0', '0', '0', '0', '1', '', '', '', '', '', '', '37', '1', '2018-10-17 15:31:02', '0000-00-00 00:00:00');
INSERT INTO `qt_article` VALUES ('39', '0', '0', '0', '0', '1', '', '', '', '', '', '', '38', '1', '2018-10-17 15:32:14', '0000-00-00 00:00:00');
INSERT INTO `qt_article` VALUES ('40', '2', '0', '0', '0', '1', '红袖添香', '红袖添香', '红袖添香', '红袖添香', 'https://www.hongxiu.com/', '<a href=\"http://www.hongxiu.com/\" target=\"_blank\">红袖添香</a>', '158', '0', '2018-10-17 15:34:24', '2018-11-14 13:28:29');
INSERT INTO `qt_article` VALUES ('41', '2', '12', '0', '0', '1', '潇湘书院', '潇湘书院', '潇湘书院', '潇湘书院', 'http://www.xxsy.net/', '<p>\n	<a href=\"http://www.xxsy.net/\" target=\"_blank\">潇湘书院</a>\n</p>\n<p>\n	<strong></strong> \n</p>\n<p>\n	<img src=\"http://localhost/nongjia/public/kindeditor/attached/image/20181017/20181017171949_60325.jpg\" alt=\"\" /> \n</p>', '159', '0', '2018-10-17 15:35:53', '2018-11-14 13:28:53');
INSERT INTO `qt_article` VALUES ('42', '2', '12', '0', '0', '1', '连城书盟', '连城书盟', '连城书盟', '连城书盟', 'http://www.lcread.com/', '连城书盟', '160', '0', '2018-10-17 17:34:53', '2018-11-14 13:29:43');
INSERT INTO `qt_article` VALUES ('43', '2', '12', '0', '0', '1', ' 纵横中文网', ' 纵横中文网', ' 纵横中文网', ' 纵横中文网', 'http://www.zongheng.com/', '纵横中文网', '161', '0', '2018-10-17 17:49:23', '2018-11-14 13:30:20');
INSERT INTO `qt_article` VALUES ('44', '3', '0', '0', '0', '1', '斗鱼', '斗鱼', '斗鱼', '斗鱼', 'https://www.douyu.com/', '斗鱼', '162', '0', '2018-10-18 11:50:23', '2018-11-14 13:30:53');
INSERT INTO `qt_article` VALUES ('45', '3', '0', '0', '0', '1', '虎牙', '虎牙', '虎牙', '虎牙', 'http://www.huya.com/', '<a href=\"http://www.huya.com/\" target=\"_blank\">虎牙</a>', '163', '0', '2018-10-23 13:34:58', '2018-11-14 13:31:20');
INSERT INTO `qt_article` VALUES ('46', '3', '0', '0', '0', '1', ' 熊猫直播', ' 熊猫直播', ' 熊猫直播', ' 熊猫直播', 'https://www.panda.tv/', '熊猫直播', '164', '0', '2018-10-25 15:00:09', '2018-11-14 13:32:09');
INSERT INTO `qt_article` VALUES ('47', '3', '0', '0', '0', '1', '花椒直播', '花椒直播', '花椒直播', '花椒直播', 'http://www.huajiao.com/', '<a href=\"http://www.huajiao.com/\" target=\"_blank\">花椒直播</a>', '165', '0', '2018-11-13 13:21:40', '2018-11-14 13:35:10');
INSERT INTO `qt_article` VALUES ('48', '1', '1', '0', '0', '1', '搜狐', '搜狐', '搜狐', '搜狐', 'http://www.sohu.com', '搜狐', '132', '0', '2018-11-13 15:36:58', '2018-11-13 15:42:27');

-- ----------------------------
-- Table structure for qt_category
-- ----------------------------
DROP TABLE IF EXISTS `qt_category`;
CREATE TABLE `qt_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT '频道id',
  `name` varchar(255) NOT NULL COMMENT '栏目名称',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态0=关闭，1=开放',
  `path` varchar(255) NOT NULL DEFAULT '',
  `weight` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `keyword` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` text COMMENT '描述',
  `delete` int(11) NOT NULL COMMENT '1=删除',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qt_category
-- ----------------------------
INSERT INTO `qt_category` VALUES ('1', '1', '国内', '1', '', '1', '国内', '国内', '0', '2018-05-14 11:53:52', '2018-11-13 10:38:01');
INSERT INTO `qt_category` VALUES ('2', '1', '国际', '1', '', '2', '国际', '国际', '0', '2018-05-14 13:48:43', '2018-11-13 10:41:02');
INSERT INTO `qt_category` VALUES ('3', '1', '山东', '1', '', '3', '山东', '山东', '0', '2018-05-14 13:54:30', '2018-10-23 14:24:21');
INSERT INTO `qt_category` VALUES ('4', '1', '山西', '1', '', '3', '山西', '山西', '0', '2018-05-14 13:56:13', '2018-10-23 14:24:01');
INSERT INTO `qt_category` VALUES ('5', '1', '内蒙古', '1', '', '5', '内蒙古', '内蒙古', '0', '2018-05-14 15:41:52', '2018-10-23 14:24:43');
INSERT INTO `qt_category` VALUES ('6', '1', '辽宁', '1', '', '1', '辽宁', '辽宁', '0', '2018-05-14 15:42:55', '2018-10-23 14:24:55');
INSERT INTO `qt_category` VALUES ('7', '1', '陕西', '1', '', '1', '陕西', '陕西', '0', '2018-05-14 15:58:56', '2018-10-23 14:25:10');
INSERT INTO `qt_category` VALUES ('8', '1', '重庆', '1', 'page', '1', '重庆', '重庆', '0', '2018-05-14 17:20:10', '2018-10-23 15:09:02');
INSERT INTO `qt_category` VALUES ('9', '1', 'test22', '1', '11112', '11122', '111122', '222211', '1', '2018-10-15 15:02:58', '2018-10-15 15:15:40');
INSERT INTO `qt_category` VALUES ('10', '2', 'tetset2', '1', '112', '112', '112', '112', '1', '2018-10-16 10:45:47', '2018-10-16 10:49:41');
INSERT INTO `qt_category` VALUES ('11', '1', '河南', '1', '', '0', '河南', '河南', '0', '2018-10-23 15:45:38', '0000-00-00 00:00:00');
INSERT INTO `qt_category` VALUES ('12', '2', '玄幻', '1', '', '0', '玄幻', '玄幻', '0', '2018-10-24 15:22:32', '2018-11-14 13:27:22');
INSERT INTO `qt_category` VALUES ('13', '2', '武侠', '1', '', '0', '武侠', '武侠', '0', '2018-10-24 15:28:14', '2018-11-14 13:27:37');
INSERT INTO `qt_category` VALUES ('14', '2', '言情', '1', '', '0', '言情', '言情', '0', '2018-10-24 15:28:31', '2018-11-14 13:27:48');

-- ----------------------------
-- Table structure for qt_category_2
-- ----------------------------
DROP TABLE IF EXISTS `qt_category_2`;
CREATE TABLE `qt_category_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` varchar(255) NOT NULL COMMENT '栏目名称',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态0=关闭，1=开放',
  `path` varchar(255) NOT NULL DEFAULT '',
  `weight` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `keyword` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` text COMMENT '描述',
  `delete` int(11) NOT NULL COMMENT '1=删除',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qt_category_2
-- ----------------------------
INSERT INTO `qt_category_2` VALUES ('11', '1', '雁西湖', '1', '', '0', '雁西湖', '雁西湖', '0', '2018-10-23 15:48:17', '0000-00-00 00:00:00');
INSERT INTO `qt_category_2` VALUES ('12', '1', '十度', '1', '', '0', '十度', '十度', '0', '2018-10-23 15:59:42', '0000-00-00 00:00:00');
INSERT INTO `qt_category_2` VALUES ('13', '2', '坝上草原', '1', '', '0', '坝上草原', '坝上草原', '0', '2018-10-23 16:00:15', '0000-00-00 00:00:00');
INSERT INTO `qt_category_2` VALUES ('14', '2', '野山坡', '1', '', '0', '野山坡', '野山坡', '0', '2018-10-23 16:00:36', '2018-10-23 16:00:55');

-- ----------------------------
-- Table structure for qt_channel
-- ----------------------------
DROP TABLE IF EXISTS `qt_channel`;
CREATE TABLE `qt_channel` (
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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qt_channel
-- ----------------------------
INSERT INTO `qt_channel` VALUES ('1', '新闻', '0', 'xinwen', '1', '新闻', '新闻', '0', '2018-05-11 16:42:44', '2018-11-13 10:35:44');
INSERT INTO `qt_channel` VALUES ('2', '小说', '0', 'xiaoshuo', '2', '小说', '小说', '0', '2018-09-17 16:20:57', '2018-11-13 10:35:54');
INSERT INTO `qt_channel` VALUES ('3', '视频', '0', 'shipin', '3', '视频', '视频', '0', '2018-09-17 16:21:48', '2018-11-13 10:34:45');
INSERT INTO `qt_channel` VALUES ('4', '图片', '0', 'tupian', '4', '图片', '图片', '0', '2018-09-17 17:18:57', '2018-11-13 10:35:09');
INSERT INTO `qt_channel` VALUES ('5', '331', '0', '33', '33', '33', '33', '1', '2018-09-17 17:19:08', '2018-09-17 17:26:30');
INSERT INTO `qt_channel` VALUES ('6', '旅游', '0', 'lvyou', '5', '旅游', '旅游', '0', '2018-10-08 14:03:05', '2018-11-13 10:35:37');
INSERT INTO `qt_channel` VALUES ('7', '阅读', '0', 'yuedu', '0', '阅读', '阅读', '0', '2018-11-14 11:58:26', '2018-11-14 11:58:35');
INSERT INTO `qt_channel` VALUES ('8', '博客', '0', 'boke', '0', '博客', '博客', '0', '2018-11-14 12:00:47', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for qt_province
-- ----------------------------
DROP TABLE IF EXISTS `qt_province`;
CREATE TABLE `qt_province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(32) NOT NULL,
  `weight` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qt_province
-- ----------------------------
INSERT INTO `qt_province` VALUES ('1', '北京', '1');
INSERT INTO `qt_province` VALUES ('2', '天津', '2');
INSERT INTO `qt_province` VALUES ('3', '上海', '3');
INSERT INTO `qt_province` VALUES ('4', '重庆', '4');
INSERT INTO `qt_province` VALUES ('5', '黑龙江', '5');
INSERT INTO `qt_province` VALUES ('6', '吉林', '6');
INSERT INTO `qt_province` VALUES ('7', '辽宁', '7');
INSERT INTO `qt_province` VALUES ('8', '河北', '8');
INSERT INTO `qt_province` VALUES ('9', '山西', '9');
INSERT INTO `qt_province` VALUES ('10', '青海', '10');
INSERT INTO `qt_province` VALUES ('11', '山东', '11');
INSERT INTO `qt_province` VALUES ('12', '河南', '12');
INSERT INTO `qt_province` VALUES ('13', '江苏', '13');
INSERT INTO `qt_province` VALUES ('14', '安徽', '14');
INSERT INTO `qt_province` VALUES ('15', '浙江', '15');
INSERT INTO `qt_province` VALUES ('16', '福建', '16');
INSERT INTO `qt_province` VALUES ('17', '江西', '17');
INSERT INTO `qt_province` VALUES ('18', '湖南', '18');
INSERT INTO `qt_province` VALUES ('19', '湖北', '19');
INSERT INTO `qt_province` VALUES ('20', '广东', '20');
INSERT INTO `qt_province` VALUES ('21', '台湾', '21');
INSERT INTO `qt_province` VALUES ('22', '海南', '22');
INSERT INTO `qt_province` VALUES ('23', '甘肃', '23');
INSERT INTO `qt_province` VALUES ('24', '陕西', '24');
INSERT INTO `qt_province` VALUES ('25', '四川', '25');
INSERT INTO `qt_province` VALUES ('26', '贵州', '26');
INSERT INTO `qt_province` VALUES ('27', '云南', '27');
INSERT INTO `qt_province` VALUES ('28', '内蒙古', '28');
INSERT INTO `qt_province` VALUES ('29', '西藏', '29');
INSERT INTO `qt_province` VALUES ('30', '宁夏', '30');
INSERT INTO `qt_province` VALUES ('31', '广西', '31');

-- ----------------------------
-- Table structure for qt_upload
-- ----------------------------
DROP TABLE IF EXISTS `qt_upload`;
CREATE TABLE `qt_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL,
  `module` varchar(32) NOT NULL COMMENT '模块: 文章 | 广告',
  `type` tinyint(1) NOT NULL COMMENT '类型: 1=图片 | 2=视频',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=不显示 1=显示',
  `filename` varchar(255) NOT NULL COMMENT '文件名',
  `size` varchar(32) NOT NULL COMMENT '文件大小',
  `save_path` varchar(255) NOT NULL COMMENT '保存路径',
  `extension` varchar(32) NOT NULL COMMENT '扩展名',
  `delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=不删除 1=删除',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=168 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qt_upload
-- ----------------------------
INSERT INTO `qt_upload` VALUES ('72', '43', 'article', '1', '1', '3adc45d2c61617b4f3f2b531eb29bf61.jpg', '120429', '/upload/kindeditor/article/20181018/3adc45d2c61617b4f3f2b531eb29bf61.jpg', 'jpg', '0', '2018-10-18 10:54:20');
INSERT INTO `qt_upload` VALUES ('73', '43', 'article', '1', '1', '7598a64eb56742705bd68b3fb2405840.jpg', '60932', '/upload/kindeditor/article/20181018/7598a64eb56742705bd68b3fb2405840.jpg', 'jpg', '0', '2018-10-18 10:56:34');
INSERT INTO `qt_upload` VALUES ('74', '43', 'article', '1', '1', '3afd7b80271366b338d07c586270f984.jpg', '67317', '/upload/article/20181018/3afd7b80271366b338d07c586270f984.jpg', 'jpg', '0', '2018-10-18 10:56:37');
INSERT INTO `qt_upload` VALUES ('75', '43', 'kindeditor/article', '1', '1', 'ed1d226cad12ec28628ed2acde88b9a8.jpg', '155600', '/upload/kindeditor/article/20181018/ed1d226cad12ec28628ed2acde88b9a8.jpg', 'jpg', '0', '2018-10-18 10:58:03');
INSERT INTO `qt_upload` VALUES ('76', '43', 'kindeditor/article', '1', '1', '7ab8ebce3b60104525a6ce2555976720.jpg', '94282', '/upload/kindeditor/article/20181018/7ab8ebce3b60104525a6ce2555976720.jpg', 'jpg', '0', '2018-10-18 10:58:08');
INSERT INTO `qt_upload` VALUES ('77', '43', 'article', '1', '1', '5a5d59d8b588a8b94677c23ee82d99e3.jpg', '60932', '/upload/article/20181018/5a5d59d8b588a8b94677c23ee82d99e3.jpg', 'jpg', '0', '2018-10-18 10:58:09');
INSERT INTO `qt_upload` VALUES ('78', '0', 'kindeditor/article', '1', '1', '77243e34e23de6d0f86697c202e76f61.jpg', '119480', '/upload/kindeditor/article/20181018/77243e34e23de6d0f86697c202e76f61.jpg', 'jpg', '0', '2018-10-18 11:49:01');
INSERT INTO `qt_upload` VALUES ('79', '0', 'kindeditor/article', '1', '1', '6e747e415378e6e103f4bb1563dec7f5.jpg', '67317', '/upload/kindeditor/article/20181018/6e747e415378e6e103f4bb1563dec7f5.jpg', 'jpg', '0', '2018-10-18 11:49:14');
INSERT INTO `qt_upload` VALUES ('80', '0', 'article', '1', '1', '1cca64db467bfb338c2c7792360d5175.jpg', '120429', '/upload/article/20181018/1cca64db467bfb338c2c7792360d5175.jpg', 'jpg', '0', '2018-10-18 11:49:18');
INSERT INTO `qt_upload` VALUES ('81', '44', 'kindeditor/article', '1', '1', '49324d27cf9f20e38b9c219d0b7129b2.jpg', '120429', '/upload/kindeditor/article/20181018/49324d27cf9f20e38b9c219d0b7129b2.jpg', 'jpg', '0', '2018-10-18 11:50:17');
INSERT INTO `qt_upload` VALUES ('82', '44', 'kindeditor/article', '1', '1', '117c864e9f20de7307f52fbd315188b9.jpg', '119480', '/upload/kindeditor/article/20181018/117c864e9f20de7307f52fbd315188b9.jpg', 'jpg', '0', '2018-10-18 11:50:21');
INSERT INTO `qt_upload` VALUES ('83', '44', 'article', '1', '1', '16fa30b8e8c7188d855fe626e152ac2c.jpg', '120429', '/upload/article/20181018/16fa30b8e8c7188d855fe626e152ac2c.jpg', 'jpg', '0', '2018-10-18 11:50:23');
INSERT INTO `qt_upload` VALUES ('84', '44', 'kindeditor/article', '1', '1', '0bb842afaf631b5e019b05c352a615e3.jpg', '108693', '/upload/kindeditor/article/20181018/0bb842afaf631b5e019b05c352a615e3.jpg', 'jpg', '0', '2018-10-18 11:51:06');
INSERT INTO `qt_upload` VALUES ('85', '44', 'kindeditor/article', '1', '1', '68fb1648b73da9d44017f84eb6a94904.jpg', '230666', '/upload/kindeditor/article/20181018/68fb1648b73da9d44017f84eb6a94904.jpg', 'jpg', '0', '2018-10-18 11:51:10');
INSERT INTO `qt_upload` VALUES ('97', '0', 'article', '1', '1', '06dd4a8ccfb0639724e256655cb25b63.jpg', '56551', '/upload/article/20181018/06dd4a8ccfb0639724e256655cb25b63.jpg', 'jpg', '0', '2018-10-18 15:55:22');
INSERT INTO `qt_upload` VALUES ('87', '35', 'article', '1', '1', '6fca5f106420df12645a6a3d44ea4ca3.jpg', '67317', '/upload/article/20181018/6fca5f106420df12645a6a3d44ea4ca3.jpg', 'jpg', '0', '2018-10-18 13:56:13');
INSERT INTO `qt_upload` VALUES ('92', '0', 'article', '1', '1', '5dd52613d48151a2d6d4f7f0ac8c8de1.jpg', '164006', '/upload/article/20181018/5dd52613d48151a2d6d4f7f0ac8c8de1.jpg', 'jpg', '0', '2018-10-18 15:50:53');
INSERT INTO `qt_upload` VALUES ('93', '0', 'article', '1', '1', '1dfdce5a92632673f7b85d8b2067c766.jpg', '164006', '/upload/article/20181018/1dfdce5a92632673f7b85d8b2067c766.jpg', 'jpg', '0', '2018-10-18 15:50:58');
INSERT INTO `qt_upload` VALUES ('94', '0', 'article', '1', '1', 'fe6ee1cb501cf0a542d3631ad3ab5feb.jpg', '164006', '/upload/article/20181018/fe6ee1cb501cf0a542d3631ad3ab5feb.jpg', 'jpg', '0', '2018-10-18 15:51:54');
INSERT INTO `qt_upload` VALUES ('95', '0', 'article', '1', '1', '840271f5e482670ea8ed1ed94bae995b.jpg', '155600', '/upload/article/20181018/840271f5e482670ea8ed1ed94bae995b.jpg', 'jpg', '0', '2018-10-18 15:52:52');
INSERT INTO `qt_upload` VALUES ('99', '44', 'article', '1', '1', 'ac83ed9f0f46ab2aaaf5c0aa5f48c0c7.jpg', '60932', '/upload/article/20181018/ac83ed9f0f46ab2aaaf5c0aa5f48c0c7.jpg', 'jpg', '0', '2018-10-18 16:53:12');
INSERT INTO `qt_upload` VALUES ('100', '0', 'article', '1', '1', '97c7c7bea3185ce6c63bcf1927a64428.jpg', '119480', '/upload/article/20181018/97c7c7bea3185ce6c63bcf1927a64428.jpg', 'jpg', '0', '2018-10-18 17:12:15');
INSERT INTO `qt_upload` VALUES ('101', '44', 'article', '1', '1', '9648ebd8c3cb839cb666d129a9e2d4c3.jpg', '120429', '/upload/article/20181018/9648ebd8c3cb839cb666d129a9e2d4c3.jpg', 'jpg', '0', '2018-10-18 17:45:02');
INSERT INTO `qt_upload` VALUES ('102', '43', 'article', '1', '1', '2e27a46f238aa285f845da9f47e198ce.jpg', '164006', '/upload/article/20181018/2e27a46f238aa285f845da9f47e198ce.jpg', 'jpg', '0', '2018-10-18 17:46:01');
INSERT INTO `qt_upload` VALUES ('103', '42', 'article', '1', '1', '5273284b8446a7e226d384359943fb79.jpg', '7236', '/upload/article/20181018/5273284b8446a7e226d384359943fb79.jpg', 'jpg', '0', '2018-10-18 17:46:33');
INSERT INTO `qt_upload` VALUES ('104', '44', 'article', '1', '1', 'c19bb3e19e800f77660000d0c26ceac5.jpg', '120429', '/upload/article/20181018/c19bb3e19e800f77660000d0c26ceac5.jpg', 'jpg', '0', '2018-10-18 18:10:25');
INSERT INTO `qt_upload` VALUES ('105', '44', 'article', '1', '1', 'a1ba7be86faa9198899f1b17d0576b3a.jpg', '120429', '/upload/article/20181018/a1ba7be86faa9198899f1b17d0576b3a.jpg', 'jpg', '0', '2018-10-18 18:15:22');
INSERT INTO `qt_upload` VALUES ('106', '44', 'article', '1', '1', 'afc1a3da4bb5976e64b596677466b860.jpg', '120429', '/upload/article/20181019/afc1a3da4bb5976e64b596677466b860.jpg', 'jpg', '0', '2018-10-19 09:38:06');
INSERT INTO `qt_upload` VALUES ('107', '45', 'article', '1', '1', 'b74409fca3318d8f21d0e80057b32ab9.jpg', '120429', '/upload/article/20181025/b74409fca3318d8f21d0e80057b32ab9.jpg', 'jpg', '0', '2018-10-25 14:52:21');
INSERT INTO `qt_upload` VALUES ('109', '46', 'article', '1', '1', '83f281d2514d3e97e50ba49978b9245d.jpg', '39764', '/upload/article/20181109/83f281d2514d3e97e50ba49978b9245d.jpg', 'jpg', '0', '2018-11-09 11:04:33');
INSERT INTO `qt_upload` VALUES ('110', '0', 'article', '1', '1', '93d4236e2b843fff5722cb47e886a6eb.jpg', '656', '/upload/article/20181113/93d4236e2b843fff5722cb47e886a6eb.jpg', 'jpg', '0', '2018-11-13 10:50:15');
INSERT INTO `qt_upload` VALUES ('111', '0', 'article', '1', '1', 'c9eeeaf6a9480864003ef63bee48235b.jpg', '656', '/upload/article/20181113/c9eeeaf6a9480864003ef63bee48235b.jpg', 'jpg', '0', '2018-11-13 10:50:24');
INSERT INTO `qt_upload` VALUES ('112', '0', 'article', '1', '1', '3b98fba0c3aed3b57589c8ad1271bd26.jpg', '656', '/upload/article/20181113/3b98fba0c3aed3b57589c8ad1271bd26.jpg', 'jpg', '0', '2018-11-13 10:50:28');
INSERT INTO `qt_upload` VALUES ('113', '0', 'article', '1', '1', '4fb8645602ed14da5d7a0d2d5cb352e1.jpg', '656', '/upload/article/20181113/4fb8645602ed14da5d7a0d2d5cb352e1.jpg', 'jpg', '0', '2018-11-13 10:50:35');
INSERT INTO `qt_upload` VALUES ('114', '0', 'article', '1', '1', '06b3c33844d90bdd647c582c9c48e42c.jpg', '656', '/upload/article/20181113/06b3c33844d90bdd647c582c9c48e42c.jpg', 'jpg', '0', '2018-11-13 10:51:33');
INSERT INTO `qt_upload` VALUES ('115', '5', 'article', '1', '1', '1bbd1deff5b1836c9f70c66877b9c3cf.jpg', '656', '/upload/article/20181113/1bbd1deff5b1836c9f70c66877b9c3cf.jpg', 'jpg', '0', '2018-11-13 10:51:44');
INSERT INTO `qt_upload` VALUES ('131', '48', 'article', '1', '1', 'd25f5b5fa5c8d22770f7ee67a92fe69b.jpg', '656', '/upload/article/20181113/d25f5b5fa5c8d22770f7ee67a92fe69b.jpg', 'jpg', '0', '2018-11-13 15:42:03');
INSERT INTO `qt_upload` VALUES ('132', '48', 'article', '1', '1', '04302d7d64013e804baeab77fc3dd670.jpg', '813', '/upload/article/20181113/04302d7d64013e804baeab77fc3dd670.jpg', 'jpg', '0', '2018-11-13 15:42:24');
INSERT INTO `qt_upload` VALUES ('133', '6', 'article', '1', '1', '53f08afc13715de2e7d8354fb5135cc1.jpg', '185', '/upload/article/20181113/53f08afc13715de2e7d8354fb5135cc1.jpg', 'jpg', '0', '2018-11-13 15:44:43');
INSERT INTO `qt_upload` VALUES ('134', '7', 'article', '1', '1', '894211af6bd4123439904e253c37cd10.jpg', '819', '/upload/article/20181113/894211af6bd4123439904e253c37cd10.jpg', 'jpg', '0', '2018-11-13 15:45:50');
INSERT INTO `qt_upload` VALUES ('135', '8', 'article', '1', '1', '5ab658711a9b59c8bc8fbb31b3162465.jpg', '858', '/upload/article/20181113/5ab658711a9b59c8bc8fbb31b3162465.jpg', 'jpg', '0', '2018-11-13 15:47:16');
INSERT INTO `qt_upload` VALUES ('136', '9', 'article', '1', '1', 'ff4dcb2ca677e2e817097d6a178561af.jpg', '780', '/upload/article/20181113/ff4dcb2ca677e2e817097d6a178561af.jpg', 'jpg', '0', '2018-11-13 15:47:49');
INSERT INTO `qt_upload` VALUES ('137', '10', 'article', '1', '1', '2b2b2d8f9fb1ab44b60f058db7ea649d.jpg', '361', '/upload/article/20181113/2b2b2d8f9fb1ab44b60f058db7ea649d.jpg', 'jpg', '0', '2018-11-13 15:48:19');
INSERT INTO `qt_upload` VALUES ('138', '11', 'article', '1', '1', '20f6af98a3b0f15c3037242d0b725904.jpg', '629', '/upload/article/20181113/20f6af98a3b0f15c3037242d0b725904.jpg', 'jpg', '0', '2018-11-13 15:48:53');
INSERT INTO `qt_upload` VALUES ('139', '13', 'article', '1', '1', '9268df273c3f6153077799f699a844de.jpg', '406', '/upload/article/20181113/9268df273c3f6153077799f699a844de.jpg', 'jpg', '0', '2018-11-13 15:50:20');
INSERT INTO `qt_upload` VALUES ('140', '15', 'article', '1', '1', '825ee03be9cf66381f8b415ff6a06208.jpg', '303', '/upload/article/20181113/825ee03be9cf66381f8b415ff6a06208.jpg', 'jpg', '0', '2018-11-13 15:51:23');
INSERT INTO `qt_upload` VALUES ('141', '14', 'article', '1', '1', 'f692dea43b1e972ceacc33a1deef9f27.jpg', '725', '/upload/article/20181114/f692dea43b1e972ceacc33a1deef9f27.jpg', 'jpg', '0', '2018-11-14 11:54:03');
INSERT INTO `qt_upload` VALUES ('142', '16', 'article', '1', '1', '0304bda749107e582747d58e6b569249.jpg', '597', '/upload/article/20181114/0304bda749107e582747d58e6b569249.jpg', 'jpg', '0', '2018-11-14 11:55:40');
INSERT INTO `qt_upload` VALUES ('143', '17', 'article', '1', '1', 'dbfe4b7847c9171148edcaacd1575336.jpg', '485', '/upload/article/20181114/dbfe4b7847c9171148edcaacd1575336.jpg', 'jpg', '0', '2018-11-14 11:56:21');
INSERT INTO `qt_upload` VALUES ('130', '48', 'article', '1', '1', 'e5a2e96f403d7882cd993279f35214e0.jpg', '813', '/upload/article/20181113/e5a2e96f403d7882cd993279f35214e0.jpg', 'jpg', '0', '2018-11-13 15:36:51');
INSERT INTO `qt_upload` VALUES ('144', '19', 'article', '1', '1', '4572c10f9f2258649147a8e2f5dd6b8d.jpg', '800', '/upload/article/20181114/4572c10f9f2258649147a8e2f5dd6b8d.jpg', 'jpg', '0', '2018-11-14 11:56:47');
INSERT INTO `qt_upload` VALUES ('145', '22', 'article', '1', '1', 'cd39127fc812012dcc3edcf6adbe0248.jpg', '572', '/upload/article/20181114/cd39127fc812012dcc3edcf6adbe0248.jpg', 'jpg', '0', '2018-11-14 11:57:14');
INSERT INTO `qt_upload` VALUES ('146', '23', 'article', '1', '1', 'e4a0a0a55c1c68cad573ca877010ec5e.jpg', '653', '/upload/article/20181114/e4a0a0a55c1c68cad573ca877010ec5e.jpg', 'jpg', '0', '2018-11-14 11:57:44');
INSERT INTO `qt_upload` VALUES ('147', '24', 'article', '1', '1', 'e6945b6f6cb97eb193b9f59d668f76f8.jpg', '726', '/upload/article/20181114/e6945b6f6cb97eb193b9f59d668f76f8.jpg', 'jpg', '0', '2018-11-14 11:59:06');
INSERT INTO `qt_upload` VALUES ('148', '25', 'article', '1', '1', '820085f215cb2e0afbd9bd0ad126f069.jpg', '660', '/upload/article/20181114/820085f215cb2e0afbd9bd0ad126f069.jpg', 'jpg', '0', '2018-11-14 11:59:41');
INSERT INTO `qt_upload` VALUES ('149', '26', 'article', '1', '1', '252a196656b9eef63137a3c17db258ca.jpg', '588', '/upload/article/20181114/252a196656b9eef63137a3c17db258ca.jpg', 'jpg', '0', '2018-11-14 12:00:11');
INSERT INTO `qt_upload` VALUES ('150', '27', 'article', '1', '1', '74a254e8243163572e18831fa97fb1d1.jpg', '559', '/upload/article/20181114/74a254e8243163572e18831fa97fb1d1.jpg', 'jpg', '0', '2018-11-14 12:01:09');
INSERT INTO `qt_upload` VALUES ('151', '29', 'article', '1', '1', 'c48cf40c770e6d4522ecac51cdac14a3.jpg', '661', '/upload/article/20181114/c48cf40c770e6d4522ecac51cdac14a3.jpg', 'jpg', '0', '2018-11-14 13:23:35');
INSERT INTO `qt_upload` VALUES ('152', '30', 'article', '1', '1', 'ab452c8d84ade318b813a3c3dffe5795.jpg', '726', '/upload/article/20181114/ab452c8d84ade318b813a3c3dffe5795.jpg', 'jpg', '0', '2018-11-14 13:24:08');
INSERT INTO `qt_upload` VALUES ('153', '31', 'article', '1', '1', '4907a0faf0baecc93bcae17e74b35d74.jpg', '492', '/upload/article/20181114/4907a0faf0baecc93bcae17e74b35d74.jpg', 'jpg', '0', '2018-11-14 13:24:44');
INSERT INTO `qt_upload` VALUES ('154', '32', 'article', '1', '1', 'ae4fcf22cd268067b1debfbba0a2c347.jpg', '726', '/upload/article/20181114/ae4fcf22cd268067b1debfbba0a2c347.jpg', 'jpg', '0', '2018-11-14 13:25:19');
INSERT INTO `qt_upload` VALUES ('155', '33', 'article', '1', '1', 'acdd76e247657593370bcdc76b7fa7aa.jpg', '744', '/upload/article/20181114/acdd76e247657593370bcdc76b7fa7aa.jpg', 'jpg', '0', '2018-11-14 13:25:55');
INSERT INTO `qt_upload` VALUES ('156', '34', 'article', '1', '1', 'be65f14f2cfe2a3d9bf9b841ffce6a83.jpg', '401', '/upload/article/20181114/be65f14f2cfe2a3d9bf9b841ffce6a83.jpg', 'jpg', '0', '2018-11-14 13:26:23');
INSERT INTO `qt_upload` VALUES ('157', '35', 'article', '1', '1', '3f703372fdcbbd9f33e9f22c7eec17b0.jpg', '606', '/upload/article/20181114/3f703372fdcbbd9f33e9f22c7eec17b0.jpg', 'jpg', '0', '2018-11-14 13:26:55');
INSERT INTO `qt_upload` VALUES ('158', '40', 'article', '1', '1', '56903cb3b136626aaa78cbbaf2d37320.jpg', '621', '/upload/article/20181114/56903cb3b136626aaa78cbbaf2d37320.jpg', 'jpg', '0', '2018-11-14 13:28:26');
INSERT INTO `qt_upload` VALUES ('159', '41', 'article', '1', '1', 'b628d3835ff7136e7c33d4b2c579e9d9.jpg', '763', '/upload/article/20181114/b628d3835ff7136e7c33d4b2c579e9d9.jpg', 'jpg', '0', '2018-11-14 13:28:49');
INSERT INTO `qt_upload` VALUES ('160', '42', 'article', '1', '1', '9bc0e7e6c6dbbf0b2c874a2052d5a161.jpg', '590', '/upload/article/20181114/9bc0e7e6c6dbbf0b2c874a2052d5a161.jpg', 'jpg', '0', '2018-11-14 13:29:40');
INSERT INTO `qt_upload` VALUES ('161', '43', 'article', '1', '1', 'ca6150d35d1557a866318ee58f787c91.jpg', '726', '/upload/article/20181114/ca6150d35d1557a866318ee58f787c91.jpg', 'jpg', '0', '2018-11-14 13:30:16');
INSERT INTO `qt_upload` VALUES ('162', '44', 'article', '1', '1', 'cae3f5729a1c2677601873b50d4b8452.jpg', '501', '/upload/article/20181114/cae3f5729a1c2677601873b50d4b8452.jpg', 'jpg', '0', '2018-11-14 13:30:49');
INSERT INTO `qt_upload` VALUES ('163', '45', 'article', '1', '1', 'bd436da480163cbe57a9bfda69bb7c54.jpg', '818', '/upload/article/20181114/bd436da480163cbe57a9bfda69bb7c54.jpg', 'jpg', '0', '2018-11-14 13:31:16');
INSERT INTO `qt_upload` VALUES ('164', '46', 'article', '1', '1', '4472e68bd40e3cc46b44e9f5b520ce42.jpg', '766', '/upload/article/20181114/4472e68bd40e3cc46b44e9f5b520ce42.jpg', 'jpg', '0', '2018-11-14 13:31:47');
INSERT INTO `qt_upload` VALUES ('165', '47', 'article', '1', '1', '78c91ca9fd48d21260490496c0530d17.jpg', '697', '/upload/article/20181114/78c91ca9fd48d21260490496c0530d17.jpg', 'jpg', '0', '2018-11-14 13:32:36');
INSERT INTO `qt_upload` VALUES ('166', '5', 'article', '1', '1', '561df5d10b3550c0b6412b30fc6a5e75.jpg', '656', '/upload/article/20181114/561df5d10b3550c0b6412b30fc6a5e75.jpg', 'jpg', '0', '2018-11-14 14:08:30');
INSERT INTO `qt_upload` VALUES ('167', '12', 'article', '1', '1', 'ddb52552d68d2173b7bd92eaa2c1a95b.jpg', '583', '/upload/article/20181114/ddb52552d68d2173b7bd92eaa2c1a95b.jpg', 'jpg', '0', '2018-11-14 14:08:44');

-- ----------------------------
-- Table structure for qt_user
-- ----------------------------
DROP TABLE IF EXISTS `qt_user`;
CREATE TABLE `qt_user` (
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
-- Records of qt_user
-- ----------------------------
INSERT INTO `qt_user` VALUES ('1', '0', '1', '1', '0', '13520651050', 'c4ca4238a0b923820dcc509a6f75849b', '', '1', '', '', '1', '1', '13520651050', '1', '::1', '0', '0', '0', '0', '0', '0', '0', '2018-09-18 16:12:36', '0000-00-00 00:00:00');
INSERT INTO `qt_user` VALUES ('2', '0', '1', '1', '0', '13520651051', '6512bd43d9caa6e02c990b0a82652dca', '', '11', '', '', '1', '1', '13520651051', '1', '0.0.0.0', '0', '0', '0', '0', '0', '0', '0', '2018-09-18 16:27:21', '0000-00-00 00:00:00');
INSERT INTO `qt_user` VALUES ('3', '0', '1', '1', '0', '13511111113', 'c4ca4238a0b923820dcc509a6f75849b', '', '1', '', '', '1', '1', '13511111113', '1', '0.0.0.0', '0', '0', '0', '0', '0', '0', '0', '2018-10-08 14:10:23', '0000-00-00 00:00:00');
