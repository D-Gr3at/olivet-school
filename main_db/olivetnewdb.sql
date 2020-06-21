/*
Navicat MySQL Data Transfer

Source Server         : connection
Source Server Version : 50142
Source Host           : localhost:3307
Source Database       : olivet

Target Server Type    : MYSQL
Target Server Version : 50142
File Encoding         : 65001

Date: 2020-03-02 11:22:35
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `app_account_settup`
-- ----------------------------
DROP TABLE IF EXISTS `app_account_settup`;
CREATE TABLE `app_account_settup` (
  `accId` int(11) NOT NULL,
  `programId` varchar(24) DEFAULT NULL,
  `beneficiaryName` varchar(128) DEFAULT NULL,
  `beneficiaryAccount` varchar(10) DEFAULT NULL,
  `bankcode` varchar(4) DEFAULT '0',
  `deductFrom` varchar(8) DEFAULT '0',
  `lineItemId` varchar(12) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `edited` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `phone_number` varchar(15) DEFAULT '0',
  `beneficiaryAmount` double DEFAULT '0',
  `status` int(1) DEFAULT '0',
  PRIMARY KEY (`accId`) USING BTREE,
  KEY `accId` (`accId`,`programId`,`phone_number`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_account_settup
-- ----------------------------
INSERT INTO `app_account_settup` VALUES ('0', '100', 'Project Account,State Ministry of Health Lafia', '1010681652', '057', '1', '3288', '2018-09-05 11:34:46', '2018-09-05 08:20:11', '08032882888', '5500', '1');
INSERT INTO `app_account_settup` VALUES ('1', '100', 'Access Solution Limited FCTERC', '5080009074', '070', '0', '3288', '2018-05-10 11:28:08', '2018-05-11 08:20:16', '07032366293', '1500', '1');
INSERT INTO `app_account_settup` VALUES ('2', '101', 'Nasarawa state revenue account', '1013920831', '033', '1', '2888', '2019-01-03 10:08:17', '2019-01-03 10:08:52', '08032882888', '6000', '1');
INSERT INTO `app_account_settup` VALUES ('3', '101', 'Access Solution Limited FCTERC', '5080009074', '070', '0', '3288', '2019-01-03 10:08:20', '2019-01-03 10:08:55', '07032366293', '2000', '1');
INSERT INTO `app_account_settup` VALUES ('4', '1021', 'School of Nursing and midwifery Lafia', '0015361683', '063', '1', '3289', '2018-09-05 11:34:46', '2018-09-05 11:34:46', '08032882888', '2500', '1');
INSERT INTO `app_account_settup` VALUES ('5', '102', 'Access Solution Limited FCTERC', '5080009074', '070', '1', '3289', '2018-09-05 11:34:46', '2018-09-05 11:34:46', '07032366293', '3000', '1');

-- ----------------------------
-- Table structure for `app_additional_qualification_tb`
-- ----------------------------
DROP TABLE IF EXISTS `app_additional_qualification_tb`;
CREATE TABLE `app_additional_qualification_tb` (
  `qid` varchar(8) NOT NULL,
  `reg_id` varchar(12) NOT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `certification` varchar(36) DEFAULT NULL,
  `year_completed` varchar(8) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`qid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_additional_qualification_tb
-- ----------------------------

-- ----------------------------
-- Table structure for `app_applicant_account_setup`
-- ----------------------------
DROP TABLE IF EXISTS `app_applicant_account_setup`;
CREATE TABLE `app_applicant_account_setup` (
  `reg_id` varchar(16) NOT NULL,
  `session` varchar(10) NOT NULL,
  `email` varchar(64) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `othernaame` varchar(50) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT '0',
  `program` varchar(20) NOT NULL,
  `userpassword` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rrr` varchar(20) DEFAULT NULL,
  `reg_status` tinyint(4) DEFAULT '0',
  `rrr_status` varchar(4) DEFAULT '0' COMMENT 'status of RRR from remita',
  `linkCode` text NOT NULL,
  `date_of_birth` varchar(100) DEFAULT NULL,
  `gender` varchar(12) DEFAULT NULL,
  `marital_status` varchar(12) DEFAULT NULL,
  `Nationality` varchar(32) DEFAULT NULL,
  `state_of_origin` varchar(32) DEFAULT NULL,
  `local_Gov_Area` varchar(32) DEFAULT NULL,
  `District_word` varchar(32) DEFAULT NULL,
  `tribe` varchar(32) DEFAULT NULL,
  `religion` varchar(20) DEFAULT NULL,
  `postal_address` varchar(255) DEFAULT NULL,
  `exam_center` varchar(64) DEFAULT NULL,
  `ip` varchar(24) DEFAULT '0',
  `gname` varchar(44) DEFAULT NULL,
  `gaddress` varchar(255) DEFAULT NULL,
  `statusCode` varchar(10) DEFAULT NULL,
  `pay_status` int(2) DEFAULT NULL,
  `pbirth` text,
  `passport` varchar(255) DEFAULT NULL,
  `fname` varchar(105) DEFAULT NULL,
  `rrr_acceptance` varchar(45) DEFAULT NULL,
  `rrr_acceptance_date` datetime DEFAULT NULL,
  `rrr_acceptance_status` int(10) unsigned DEFAULT '0',
  `application_lock` int(10) unsigned DEFAULT '0',
  `educational_status` int(10) unsigned DEFAULT '0',
  `admissionstatus` int(10) unsigned DEFAULT '0',
  `date_adm` datetime DEFAULT NULL,
  `exam_center_id` varchar(45) DEFAULT NULL,
  `ward` varchar(145) DEFAULT NULL,
  `date_locked` varchar(45) DEFAULT NULL,
  `application_count` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`application_count`) USING BTREE,
  UNIQUE KEY `regid` (`reg_id`) USING BTREE,
  KEY `email` (`email`,`surname`,`othernaame`,`program`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_applicant_account_setup
-- ----------------------------
INSERT INTO `app_applicant_account_setup` VALUES ('20202020000337', '2020', '\"innococentedwin25@yahoo.com\"', '\"Agbo\"', '\"idoko\"', '\"08089708251\"', '', ' 0x9abb3a12734be5c8', '2020-03-02 04:38:00', null, '0', '0', 'ga5raQsEgtOKencBrT3kUzIvKpEmEiVFV0ahb5LCZ6q2raJPZfDM6tHtPnGrb6OV', null, '\"Male\"', null, null, null, null, null, null, null, null, null, '0', null, null, null, null, null, null, '\"Innocent\"', null, null, '0', '0', '0', '0', null, null, null, null, '26');
INSERT INTO `app_applicant_account_setup` VALUES ('20202020000338', '2020', 'innococentedwin26@yahoo.com', 'Akor', '', '08089708251', '', ' 0x39782474d2d96130', '2020-03-02 04:47:29', null, '33', '0', '3Fb5Zmc1eQ3XWx8QeMmHllmw1oYBh5KU3RXjz2w5sWtbPntnjYz7vdjzCoWXtUHK', '5666', 'Male', 'single', '160', '2653', '212', null, 'idoma', 'Christian', 'hello%2520world', '002', '::1', 'akor', '002', null, null, 'abuja', null, 'innocent', null, null, '0', '0', '0', '0', null, '002', 'ok', null, '27');

-- ----------------------------
-- Table structure for `app_applicant_log`
-- ----------------------------
DROP TABLE IF EXISTS `app_applicant_log`;
CREATE TABLE `app_applicant_log` (
  `app_id` varchar(60) NOT NULL DEFAULT '',
  `subject` varchar(60) NOT NULL DEFAULT '',
  `sittings` tinyint(1) NOT NULL DEFAULT '0',
  `grades` char(255) NOT NULL,
  `exam_type` varchar(10) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `period` varchar(10) NOT NULL,
  `year` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sch_name` varchar(150) DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `completed` tinyint(1) unsigned zerofill DEFAULT '0',
  PRIMARY KEY (`app_id`,`subject`,`sittings`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_applicant_log
-- ----------------------------

-- ----------------------------
-- Table structure for `app_bankstb`
-- ----------------------------
DROP TABLE IF EXISTS `app_bankstb`;
CREATE TABLE `app_bankstb` (
  `bankName` varchar(128) DEFAULT NULL,
  `bankCode` varchar(11) NOT NULL,
  PRIMARY KEY (`bankCode`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_bankstb
-- ----------------------------
INSERT INTO `app_bankstb` VALUES ('CBN', '000');
INSERT INTO `app_bankstb` VALUES ('FIRST BANK OF NIGERIA PLC', '011');
INSERT INTO `app_bankstb` VALUES ('CITI BANK', '023');
INSERT INTO `app_bankstb` VALUES ('HERITAGE BANK', '030');
INSERT INTO `app_bankstb` VALUES ('UNION BANK OF NIGERIA PLC', '032');
INSERT INTO `app_bankstb` VALUES ('UNITED BANK FOR AFRICA PLC', '033');
INSERT INTO `app_bankstb` VALUES ('WEMA BABK PLC', '035');
INSERT INTO `app_bankstb` VALUES ('STANBIC-IBTC BANK PLC', '039');
INSERT INTO `app_bankstb` VALUES ('ACCESS BANK PLC', '044');
INSERT INTO `app_bankstb` VALUES ('ECOBANK NIGERIA PLC', '050');
INSERT INTO `app_bankstb` VALUES ('ZENITH BANK PLC', '057');
INSERT INTO `app_bankstb` VALUES ('GUARANTY TRUST BANK PLC', '058');
INSERT INTO `app_bankstb` VALUES ('DIAMOND BANK PLC', '063');
INSERT INTO `app_bankstb` VALUES ('STANDARD CHARTERED ', '068');
INSERT INTO `app_bankstb` VALUES ('FIDELITY BANK PLC', '070');
INSERT INTO `app_bankstb` VALUES ('SKYE BANK PLC', '076');
INSERT INTO `app_bankstb` VALUES ('KEYSTONE BANK', '082');
INSERT INTO `app_bankstb` VALUES ('SUNTRUST', '100');
INSERT INTO `app_bankstb` VALUES ('PROVIDOUS', '101');
INSERT INTO `app_bankstb` VALUES ('FIRST CITY MONUMENT BANK PLC', '214');
INSERT INTO `app_bankstb` VALUES ('UNITY BANK PLC', '215');
INSERT INTO `app_bankstb` VALUES ('STERLING BANK PLC', '232');
INSERT INTO `app_bankstb` VALUES ('JAIZ BANK', '301');

-- ----------------------------
-- Table structure for `app_center_tb`
-- ----------------------------
DROP TABLE IF EXISTS `app_center_tb`;
CREATE TABLE `app_center_tb` (
  `center_id` varchar(4) NOT NULL,
  `center_name` varchar(56) NOT NULL,
  `user` varchar(35) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`center_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_center_tb
-- ----------------------------
INSERT INTO `app_center_tb` VALUES ('001', 'NAS-POLY Lafia', 'cephatech', '2018-12-12 09:38:35');
INSERT INTO `app_center_tb` VALUES ('002', 'Central Pilot Primary School Wema Road, Akwanga', 'cephatech', '2018-12-12 09:39:50');
INSERT INTO `app_center_tb` VALUES ('003', 'School of Health Technology, Keffi', 'cephatech', '2018-12-12 09:40:36');

-- ----------------------------
-- Table structure for `app_cities`
-- ----------------------------
DROP TABLE IF EXISTS `app_cities`;
CREATE TABLE `app_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=827 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_cities
-- ----------------------------
INSERT INTO `app_cities` VALUES ('2', 'Asaritoru', '2679');
INSERT INTO `app_cities` VALUES ('3', 'Aboh mbaise', '2663');
INSERT INTO `app_cities` VALUES ('5', 'Oluyole', '2677');
INSERT INTO `app_cities` VALUES ('6', 'Bekwara', '2656');
INSERT INTO `app_cities` VALUES ('7', 'Abeokuta east', '2674');
INSERT INTO `app_cities` VALUES ('8', 'Yemoji', '2674');
INSERT INTO `app_cities` VALUES ('9', 'Etsakor', '2659');
INSERT INTO `app_cities` VALUES ('10', 'Ethiope west', '2657');
INSERT INTO `app_cities` VALUES ('11', 'Idemili', '2651');
INSERT INTO `app_cities` VALUES ('12', 'Ijumu iyara', '2669');
INSERT INTO `app_cities` VALUES ('13', 'Mopa-muro', '2669');
INSERT INTO `app_cities` VALUES ('14', 'Aba north', '2647');
INSERT INTO `app_cities` VALUES ('15', 'Aba south', '2647');
INSERT INTO `app_cities` VALUES ('16', 'Arochukwu', '2647');
INSERT INTO `app_cities` VALUES ('17', 'Bende', '2647');
INSERT INTO `app_cities` VALUES ('18', 'Ikwuano', '2647');
INSERT INTO `app_cities` VALUES ('19', 'Isiala-ngwa north', '2647');
INSERT INTO `app_cities` VALUES ('20', 'Isiala-ngwa south', '2647');
INSERT INTO `app_cities` VALUES ('21', 'Isukwuato', '2647');
INSERT INTO `app_cities` VALUES ('22', 'Obiomangwa', '2647');
INSERT INTO `app_cities` VALUES ('23', 'Ohafia', '2647');
INSERT INTO `app_cities` VALUES ('24', 'Osisioma ngwa', '2647');
INSERT INTO `app_cities` VALUES ('25', 'Ugwunagbo', '2647');
INSERT INTO `app_cities` VALUES ('26', 'Ukwa east', '2647');
INSERT INTO `app_cities` VALUES ('27', 'Ukwa west', '2647');
INSERT INTO `app_cities` VALUES ('28', 'Umuahia north', '2647');
INSERT INTO `app_cities` VALUES ('29', 'Umuahia south', '2647');
INSERT INTO `app_cities` VALUES ('30', 'Umu-nneochi', '2647');
INSERT INTO `app_cities` VALUES ('31', 'Demsa', '2649');
INSERT INTO `app_cities` VALUES ('32', 'Fufore', '2649');
INSERT INTO `app_cities` VALUES ('33', 'Ganye', '2649');
INSERT INTO `app_cities` VALUES ('34', 'Girei', '2649');
INSERT INTO `app_cities` VALUES ('35', 'Gombi', '2649');
INSERT INTO `app_cities` VALUES ('36', 'Guyuk', '2649');
INSERT INTO `app_cities` VALUES ('37', 'Hong', '2649');
INSERT INTO `app_cities` VALUES ('38', 'Jada', '2649');
INSERT INTO `app_cities` VALUES ('39', 'Lamurde', '2649');
INSERT INTO `app_cities` VALUES ('40', 'Madagali', '2649');
INSERT INTO `app_cities` VALUES ('41', 'Maiha', '2649');
INSERT INTO `app_cities` VALUES ('42', 'Mayo-belwa', '2649');
INSERT INTO `app_cities` VALUES ('43', 'Michika', '2649');
INSERT INTO `app_cities` VALUES ('44', 'Mubi north', '2649');
INSERT INTO `app_cities` VALUES ('45', 'Mubi south', '2649');
INSERT INTO `app_cities` VALUES ('46', 'Numan', '2649');
INSERT INTO `app_cities` VALUES ('47', 'Shelleng', '2649');
INSERT INTO `app_cities` VALUES ('48', 'Song', '2649');
INSERT INTO `app_cities` VALUES ('49', 'Toungo', '2649');
INSERT INTO `app_cities` VALUES ('50', 'Yola north', '2649');
INSERT INTO `app_cities` VALUES ('51', 'Yola south', '2649');
INSERT INTO `app_cities` VALUES ('52', 'Abak', '2650');
INSERT INTO `app_cities` VALUES ('53', 'Eastern obolo', '2650');
INSERT INTO `app_cities` VALUES ('54', 'Eket', '2650');
INSERT INTO `app_cities` VALUES ('55', 'Esit eket', '2650');
INSERT INTO `app_cities` VALUES ('56', 'Essien udim', '2650');
INSERT INTO `app_cities` VALUES ('57', 'Etim ekpo', '2650');
INSERT INTO `app_cities` VALUES ('58', 'Etinan', '2650');
INSERT INTO `app_cities` VALUES ('59', 'Ibeno', '2650');
INSERT INTO `app_cities` VALUES ('60', 'Ibesikpo asutan', '2650');
INSERT INTO `app_cities` VALUES ('61', 'Ibiono ibom', '2650');
INSERT INTO `app_cities` VALUES ('62', 'Ika', '2650');
INSERT INTO `app_cities` VALUES ('63', 'Ikono', '2650');
INSERT INTO `app_cities` VALUES ('64', 'Ikot abasi', '2650');
INSERT INTO `app_cities` VALUES ('65', 'Ikot ekpene', '2650');
INSERT INTO `app_cities` VALUES ('66', 'Ini', '2650');
INSERT INTO `app_cities` VALUES ('67', 'Itu', '2650');
INSERT INTO `app_cities` VALUES ('68', 'Mbo', '2650');
INSERT INTO `app_cities` VALUES ('69', 'Mkpat enin', '2650');
INSERT INTO `app_cities` VALUES ('70', 'Nsit atai', '2650');
INSERT INTO `app_cities` VALUES ('71', 'Nsit ibom', '2650');
INSERT INTO `app_cities` VALUES ('72', 'Nsit ubium', '2650');
INSERT INTO `app_cities` VALUES ('73', 'Uruan', '2650');
INSERT INTO `app_cities` VALUES ('74', 'Urue-offong/oruko', '2650');
INSERT INTO `app_cities` VALUES ('75', 'Uyo', '2650');
INSERT INTO `app_cities` VALUES ('76', 'Aguata', '2651');
INSERT INTO `app_cities` VALUES ('77', 'Anambra east', '2651');
INSERT INTO `app_cities` VALUES ('78', 'Anambra west', '2651');
INSERT INTO `app_cities` VALUES ('79', 'Anaocha', '2651');
INSERT INTO `app_cities` VALUES ('80', 'Awka north', '2651');
INSERT INTO `app_cities` VALUES ('81', 'Awka south', '2651');
INSERT INTO `app_cities` VALUES ('82', 'Ayamelum', '2651');
INSERT INTO `app_cities` VALUES ('83', 'Dunukofia', '2651');
INSERT INTO `app_cities` VALUES ('84', 'Ekwusigo', '2651');
INSERT INTO `app_cities` VALUES ('85', 'Idemili north', '2651');
INSERT INTO `app_cities` VALUES ('86', 'Idemili south', '2651');
INSERT INTO `app_cities` VALUES ('87', 'Ihiala', '2651');
INSERT INTO `app_cities` VALUES ('88', 'Njikoka', '2651');
INSERT INTO `app_cities` VALUES ('89', 'Nnewi north', '2651');
INSERT INTO `app_cities` VALUES ('90', 'Obanliku', '2656');
INSERT INTO `app_cities` VALUES ('91', 'Obubra', '2656');
INSERT INTO `app_cities` VALUES ('92', 'Obudu', '2656');
INSERT INTO `app_cities` VALUES ('93', 'Odukpani', '2656');
INSERT INTO `app_cities` VALUES ('94', 'Ogoja', '2656');
INSERT INTO `app_cities` VALUES ('95', 'Yakurr', '2656');
INSERT INTO `app_cities` VALUES ('96', 'Yala', '2656');
INSERT INTO `app_cities` VALUES ('97', 'Aniocha north', '2657');
INSERT INTO `app_cities` VALUES ('98', 'Aniocha south', '2657');
INSERT INTO `app_cities` VALUES ('99', 'Bomadi', '2657');
INSERT INTO `app_cities` VALUES ('100', 'Burutu', '2657');
INSERT INTO `app_cities` VALUES ('101', 'Ethiope east', '2657');
INSERT INTO `app_cities` VALUES ('102', 'Ethiope west', '2657');
INSERT INTO `app_cities` VALUES ('103', 'Ika north', '2657');
INSERT INTO `app_cities` VALUES ('104', 'Ika south', '2657');
INSERT INTO `app_cities` VALUES ('105', 'Isoko north', '2657');
INSERT INTO `app_cities` VALUES ('106', 'Isoko south', '2657');
INSERT INTO `app_cities` VALUES ('107', 'Ndokwa east', '2657');
INSERT INTO `app_cities` VALUES ('108', 'Ndokwa west', '2657');
INSERT INTO `app_cities` VALUES ('109', 'Okpe', '2657');
INSERT INTO `app_cities` VALUES ('110', 'Oshimili north', '2657');
INSERT INTO `app_cities` VALUES ('111', 'Oshimili south', '2657');
INSERT INTO `app_cities` VALUES ('112', 'Patani', '2657');
INSERT INTO `app_cities` VALUES ('113', 'Sapele', '2657');
INSERT INTO `app_cities` VALUES ('114', 'Udu', '2657');
INSERT INTO `app_cities` VALUES ('115', 'Ughelli north', '2657');
INSERT INTO `app_cities` VALUES ('116', 'Ughelli south', '2657');
INSERT INTO `app_cities` VALUES ('117', 'Ukwuani', '2657');
INSERT INTO `app_cities` VALUES ('118', 'Uvwie', '2657');
INSERT INTO `app_cities` VALUES ('119', 'Warri north', '2657');
INSERT INTO `app_cities` VALUES ('120', 'Warri south', '2657');
INSERT INTO `app_cities` VALUES ('121', 'Warri south west', '2657');
INSERT INTO `app_cities` VALUES ('122', 'Abakaliki', '2658');
INSERT INTO `app_cities` VALUES ('123', 'Afikpo north', '2658');
INSERT INTO `app_cities` VALUES ('124', 'Afikpo south', '2658');
INSERT INTO `app_cities` VALUES ('125', 'Ebonyi', '2658');
INSERT INTO `app_cities` VALUES ('126', 'Ezza north', '2658');
INSERT INTO `app_cities` VALUES ('127', 'Ezza south', '2658');
INSERT INTO `app_cities` VALUES ('128', 'Ikwo', '2658');
INSERT INTO `app_cities` VALUES ('129', 'Ishielu', '2658');
INSERT INTO `app_cities` VALUES ('130', 'Ivo', '2658');
INSERT INTO `app_cities` VALUES ('131', 'Izzi', '2658');
INSERT INTO `app_cities` VALUES ('132', 'Ohaozara', '2658');
INSERT INTO `app_cities` VALUES ('133', 'Ohaukwu', '2658');
INSERT INTO `app_cities` VALUES ('134', 'Onicha', '2658');
INSERT INTO `app_cities` VALUES ('135', 'Akoko-edo', '2659');
INSERT INTO `app_cities` VALUES ('136', 'Egor', '2659');
INSERT INTO `app_cities` VALUES ('137', 'Esan central', '2659');
INSERT INTO `app_cities` VALUES ('138', 'Esan north east', '2659');
INSERT INTO `app_cities` VALUES ('139', 'Esan south east', '2659');
INSERT INTO `app_cities` VALUES ('140', 'Esan west', '2659');
INSERT INTO `app_cities` VALUES ('141', 'Etsako central', '2659');
INSERT INTO `app_cities` VALUES ('142', 'Etsako east', '2659');
INSERT INTO `app_cities` VALUES ('143', 'Etsako west', '2659');
INSERT INTO `app_cities` VALUES ('144', 'Igueben', '2659');
INSERT INTO `app_cities` VALUES ('145', 'Ikpoba-okha', '2659');
INSERT INTO `app_cities` VALUES ('146', 'Oredo', '2659');
INSERT INTO `app_cities` VALUES ('147', 'Orhionmwon', '2659');
INSERT INTO `app_cities` VALUES ('148', 'Ovia north east', '2659');
INSERT INTO `app_cities` VALUES ('149', 'Ovia south west', '2659');
INSERT INTO `app_cities` VALUES ('150', 'Owan east', '2659');
INSERT INTO `app_cities` VALUES ('151', 'Owan west', '2659');
INSERT INTO `app_cities` VALUES ('152', 'Uhunmwonde', '2659');
INSERT INTO `app_cities` VALUES ('153', 'ADK', '2660');
INSERT INTO `app_cities` VALUES ('154', 'DEA', '2660');
INSERT INTO `app_cities` VALUES ('155', 'EFY', '2660');
INSERT INTO `app_cities` VALUES ('156', 'MUE', '2660');
INSERT INTO `app_cities` VALUES ('157', 'LAW', '2660');
INSERT INTO `app_cities` VALUES ('158', 'AMK', '2660');
INSERT INTO `app_cities` VALUES ('159', 'EMR', '2660');
INSERT INTO `app_cities` VALUES ('160', 'DEK', '2660');
INSERT INTO `app_cities` VALUES ('161', 'JER', '2660');
INSERT INTO `app_cities` VALUES ('162', 'KER', '2660');
INSERT INTO `app_cities` VALUES ('163', 'KLE', '2660');
INSERT INTO `app_cities` VALUES ('164', 'YEK', '2660');
INSERT INTO `app_cities` VALUES ('165', 'GED', '2660');
INSERT INTO `app_cities` VALUES ('166', 'SSE', '2660');
INSERT INTO `app_cities` VALUES ('167', 'TUN', '2660');
INSERT INTO `app_cities` VALUES ('168', 'YEE', '2660');
INSERT INTO `app_cities` VALUES ('169', 'Aninri', '2661');
INSERT INTO `app_cities` VALUES ('170', 'Awgu', '2661');
INSERT INTO `app_cities` VALUES ('171', 'Enugu east', '2661');
INSERT INTO `app_cities` VALUES ('172', 'Enugu north', '2661');
INSERT INTO `app_cities` VALUES ('173', 'Enugu south', '2661');
INSERT INTO `app_cities` VALUES ('174', 'Ezeagu', '2661');
INSERT INTO `app_cities` VALUES ('175', 'Enugu', '2661');
INSERT INTO `app_cities` VALUES ('176', 'Igbo-etit', '2661');
INSERT INTO `app_cities` VALUES ('177', 'Igbo-eze north', '2661');
INSERT INTO `app_cities` VALUES ('178', 'Igho-eze south', '2661');
INSERT INTO `app_cities` VALUES ('179', 'Isi-uzo', '2661');
INSERT INTO `app_cities` VALUES ('180', 'Nkanu east', '2661');
INSERT INTO `app_cities` VALUES ('181', 'Nkanu west', '2661');
INSERT INTO `app_cities` VALUES ('182', 'Nnewi south', '2651');
INSERT INTO `app_cities` VALUES ('183', 'Ogbaru', '2651');
INSERT INTO `app_cities` VALUES ('184', 'Onitsha north', '2651');
INSERT INTO `app_cities` VALUES ('185', 'Onitsha south', '2651');
INSERT INTO `app_cities` VALUES ('186', 'Orumba north', '2651');
INSERT INTO `app_cities` VALUES ('187', 'Orumba south', '2651');
INSERT INTO `app_cities` VALUES ('188', 'Oyi', '2651');
INSERT INTO `app_cities` VALUES ('189', 'Alkaleri', '2652');
INSERT INTO `app_cities` VALUES ('190', 'Bauchi', '2652');
INSERT INTO `app_cities` VALUES ('191', 'Bogoro', '2652');
INSERT INTO `app_cities` VALUES ('192', 'Damban', '2652');
INSERT INTO `app_cities` VALUES ('193', 'Darazo', '2652');
INSERT INTO `app_cities` VALUES ('194', 'Dass', '2652');
INSERT INTO `app_cities` VALUES ('195', 'Gamawa', '2652');
INSERT INTO `app_cities` VALUES ('196', 'Ganjuwa', '2652');
INSERT INTO `app_cities` VALUES ('197', 'Giade', '2652');
INSERT INTO `app_cities` VALUES ('198', 'Itas/gadau', '2652');
INSERT INTO `app_cities` VALUES ('199', 'Jama\'are', '2652');
INSERT INTO `app_cities` VALUES ('200', 'Katagun', '2652');
INSERT INTO `app_cities` VALUES ('201', 'Gusau', '2683');
INSERT INTO `app_cities` VALUES ('202', 'Kirfi', '2652');
INSERT INTO `app_cities` VALUES ('203', 'Misau', '2652');
INSERT INTO `app_cities` VALUES ('204', 'Ningi', '2652');
INSERT INTO `app_cities` VALUES ('205', 'Shira', '2652');
INSERT INTO `app_cities` VALUES ('206', 'Tafawa-balewa', '2652');
INSERT INTO `app_cities` VALUES ('207', 'Toro', '2652');
INSERT INTO `app_cities` VALUES ('208', 'Warji', '2652');
INSERT INTO `app_cities` VALUES ('209', 'Zaki', '2652');
INSERT INTO `app_cities` VALUES ('210', 'Brass', '2653');
INSERT INTO `app_cities` VALUES ('211', 'Ekeremor', '2653');
INSERT INTO `app_cities` VALUES ('212', 'Kolokuma/opokuma', '2653');
INSERT INTO `app_cities` VALUES ('213', 'Nembe', '2653');
INSERT INTO `app_cities` VALUES ('214', 'Ogbia', '2653');
INSERT INTO `app_cities` VALUES ('215', 'Sagbama', '2653');
INSERT INTO `app_cities` VALUES ('216', 'Southern ijaw', '2653');
INSERT INTO `app_cities` VALUES ('217', 'Yenegoa', '2653');
INSERT INTO `app_cities` VALUES ('218', 'Ado', '2654');
INSERT INTO `app_cities` VALUES ('219', 'Agatu', '2654');
INSERT INTO `app_cities` VALUES ('220', 'Apa', '2654');
INSERT INTO `app_cities` VALUES ('221', 'Buruku', '2654');
INSERT INTO `app_cities` VALUES ('222', 'Gboko', '2654');
INSERT INTO `app_cities` VALUES ('223', 'Guma', '2654');
INSERT INTO `app_cities` VALUES ('224', 'Gwer east', '2654');
INSERT INTO `app_cities` VALUES ('225', 'Gwer west', '2654');
INSERT INTO `app_cities` VALUES ('226', 'Katsina-ala', '2654');
INSERT INTO `app_cities` VALUES ('227', 'Konshisha', '2654');
INSERT INTO `app_cities` VALUES ('228', 'Kwande', '2654');
INSERT INTO `app_cities` VALUES ('229', 'Logo', '2654');
INSERT INTO `app_cities` VALUES ('230', 'Makurdi', '2654');
INSERT INTO `app_cities` VALUES ('231', 'Obi', '2654');
INSERT INTO `app_cities` VALUES ('232', 'Ogbadibo', '2654');
INSERT INTO `app_cities` VALUES ('233', 'Oju', '2654');
INSERT INTO `app_cities` VALUES ('234', 'Okpokwu', '2654');
INSERT INTO `app_cities` VALUES ('235', 'Ohimini', '2654');
INSERT INTO `app_cities` VALUES ('236', 'Oturkpo', '2654');
INSERT INTO `app_cities` VALUES ('237', 'Tarka', '2654');
INSERT INTO `app_cities` VALUES ('238', 'Ukum', '2654');
INSERT INTO `app_cities` VALUES ('239', 'Ushongo', '2654');
INSERT INTO `app_cities` VALUES ('240', 'Vandeikya', '2654');
INSERT INTO `app_cities` VALUES ('241', 'Abadam', '2655');
INSERT INTO `app_cities` VALUES ('242', 'Askira/uba', '2655');
INSERT INTO `app_cities` VALUES ('243', 'Bama', '2655');
INSERT INTO `app_cities` VALUES ('244', 'Bayo', '2655');
INSERT INTO `app_cities` VALUES ('245', 'Biu', '2655');
INSERT INTO `app_cities` VALUES ('246', 'Chibok', '2655');
INSERT INTO `app_cities` VALUES ('247', 'Damboa', '2655');
INSERT INTO `app_cities` VALUES ('248', 'Dikwa', '2655');
INSERT INTO `app_cities` VALUES ('249', 'Gubio', '2655');
INSERT INTO `app_cities` VALUES ('250', 'Guzamala', '2655');
INSERT INTO `app_cities` VALUES ('251', 'Gwoza', '2655');
INSERT INTO `app_cities` VALUES ('252', 'Hawul', '2655');
INSERT INTO `app_cities` VALUES ('253', 'Jere', '2655');
INSERT INTO `app_cities` VALUES ('254', 'Kaga', '2655');
INSERT INTO `app_cities` VALUES ('255', 'Kala/balge', '2655');
INSERT INTO `app_cities` VALUES ('256', 'Konduga', '2655');
INSERT INTO `app_cities` VALUES ('257', 'Kukawa', '2655');
INSERT INTO `app_cities` VALUES ('258', 'Kwaya kusar', '2655');
INSERT INTO `app_cities` VALUES ('259', 'Mafa', '2655');
INSERT INTO `app_cities` VALUES ('260', 'Magumeri', '2655');
INSERT INTO `app_cities` VALUES ('261', 'Maiduguri', '2655');
INSERT INTO `app_cities` VALUES ('262', 'Marte', '2655');
INSERT INTO `app_cities` VALUES ('263', 'Mobbar', '2655');
INSERT INTO `app_cities` VALUES ('264', 'Monguno', '2655');
INSERT INTO `app_cities` VALUES ('265', 'Ngala', '2655');
INSERT INTO `app_cities` VALUES ('266', 'Nganzai', '2655');
INSERT INTO `app_cities` VALUES ('267', 'Shani', '2655');
INSERT INTO `app_cities` VALUES ('268', 'Abi', '2656');
INSERT INTO `app_cities` VALUES ('269', 'Akamkpa', '2656');
INSERT INTO `app_cities` VALUES ('270', 'Akpabuyo', '2656');
INSERT INTO `app_cities` VALUES ('271', 'Bakassi', '2656');
INSERT INTO `app_cities` VALUES ('272', 'Bekwara', '2656');
INSERT INTO `app_cities` VALUES ('273', 'Biase', '2656');
INSERT INTO `app_cities` VALUES ('274', 'Boki', '2656');
INSERT INTO `app_cities` VALUES ('275', 'Calabar-municipal', '2656');
INSERT INTO `app_cities` VALUES ('276', 'Calabar south', '2656');
INSERT INTO `app_cities` VALUES ('277', 'Etung', '2656');
INSERT INTO `app_cities` VALUES ('278', 'Ikom', '2656');
INSERT INTO `app_cities` VALUES ('279', 'Nsukka', '2661');
INSERT INTO `app_cities` VALUES ('280', 'Oji-river', '2661');
INSERT INTO `app_cities` VALUES ('281', 'Udenu', '2661');
INSERT INTO `app_cities` VALUES ('282', 'Udi', '2661');
INSERT INTO `app_cities` VALUES ('283', 'Uzo-uwani', '2661');
INSERT INTO `app_cities` VALUES ('284', 'Akko', '2662');
INSERT INTO `app_cities` VALUES ('285', 'Balanga', '2662');
INSERT INTO `app_cities` VALUES ('286', 'Billiri', '2662');
INSERT INTO `app_cities` VALUES ('287', 'Dukku', '2662');
INSERT INTO `app_cities` VALUES ('288', 'Funakaye', '2662');
INSERT INTO `app_cities` VALUES ('289', 'Gombe', '2662');
INSERT INTO `app_cities` VALUES ('290', 'Kaltungo', '2662');
INSERT INTO `app_cities` VALUES ('291', 'Kwami', '2662');
INSERT INTO `app_cities` VALUES ('292', 'Nafada', '2662');
INSERT INTO `app_cities` VALUES ('293', 'Shomgom', '2662');
INSERT INTO `app_cities` VALUES ('294', 'Yamaltu/deba', '2662');
INSERT INTO `app_cities` VALUES ('295', 'Ahiazu-mbaise', '2663');
INSERT INTO `app_cities` VALUES ('296', 'Ehime-mbano', '2663');
INSERT INTO `app_cities` VALUES ('297', 'Ezinihitte', '2663');
INSERT INTO `app_cities` VALUES ('298', 'Ideato north', '2663');
INSERT INTO `app_cities` VALUES ('299', 'Ideato south', '2663');
INSERT INTO `app_cities` VALUES ('300', 'Ihitte-uboma', '2663');
INSERT INTO `app_cities` VALUES ('301', 'Ikeduru', '2663');
INSERT INTO `app_cities` VALUES ('302', 'Isiala mbano', '2663');
INSERT INTO `app_cities` VALUES ('303', 'Isu', '2663');
INSERT INTO `app_cities` VALUES ('304', 'Mbaitoli', '2663');
INSERT INTO `app_cities` VALUES ('305', 'Ngor-okpala', '2663');
INSERT INTO `app_cities` VALUES ('306', 'Njaba', '2663');
INSERT INTO `app_cities` VALUES ('307', 'Nwangele', '2663');
INSERT INTO `app_cities` VALUES ('308', 'Nkwerre', '2663');
INSERT INTO `app_cities` VALUES ('309', 'Obowo', '2663');
INSERT INTO `app_cities` VALUES ('310', 'Oguta', '2663');
INSERT INTO `app_cities` VALUES ('311', 'Ohaji/egbema', '2663');
INSERT INTO `app_cities` VALUES ('312', 'Okigwe', '2663');
INSERT INTO `app_cities` VALUES ('313', 'Orlu', '2663');
INSERT INTO `app_cities` VALUES ('314', 'Orsu', '2663');
INSERT INTO `app_cities` VALUES ('315', 'Oru east', '2663');
INSERT INTO `app_cities` VALUES ('316', 'Oru west', '2663');
INSERT INTO `app_cities` VALUES ('317', 'Owerri muni.', '2663');
INSERT INTO `app_cities` VALUES ('318', 'Owerri north', '2663');
INSERT INTO `app_cities` VALUES ('319', 'Owerri west', '2663');
INSERT INTO `app_cities` VALUES ('320', 'Onuimo', '2663');
INSERT INTO `app_cities` VALUES ('321', 'Auyo', '2664');
INSERT INTO `app_cities` VALUES ('322', 'Babura', '2664');
INSERT INTO `app_cities` VALUES ('323', 'Birnin kudu', '2664');
INSERT INTO `app_cities` VALUES ('324', 'Biriniwa', '2664');
INSERT INTO `app_cities` VALUES ('325', 'Buji', '2664');
INSERT INTO `app_cities` VALUES ('326', 'Dutse', '2664');
INSERT INTO `app_cities` VALUES ('327', 'Gagarawa', '2664');
INSERT INTO `app_cities` VALUES ('328', 'Garki', '2664');
INSERT INTO `app_cities` VALUES ('329', 'Gumel', '2664');
INSERT INTO `app_cities` VALUES ('330', 'Guri', '2664');
INSERT INTO `app_cities` VALUES ('331', 'Gwaram', '2664');
INSERT INTO `app_cities` VALUES ('332', 'Gwiwa', '2664');
INSERT INTO `app_cities` VALUES ('333', 'Hadejia', '2664');
INSERT INTO `app_cities` VALUES ('334', 'Jahun', '2664');
INSERT INTO `app_cities` VALUES ('335', 'Kafin', '2664');
INSERT INTO `app_cities` VALUES ('336', 'Hausa', '2664');
INSERT INTO `app_cities` VALUES ('337', 'Kaugama', '2664');
INSERT INTO `app_cities` VALUES ('338', 'Kazaure', '2664');
INSERT INTO `app_cities` VALUES ('339', 'Kiri kasamma', '2664');
INSERT INTO `app_cities` VALUES ('340', 'Kiyawa', '2664');
INSERT INTO `app_cities` VALUES ('341', 'Maigatari', '2664');
INSERT INTO `app_cities` VALUES ('342', 'Malam madori', '2664');
INSERT INTO `app_cities` VALUES ('343', 'Miga', '2664');
INSERT INTO `app_cities` VALUES ('344', 'Ringim', '2664');
INSERT INTO `app_cities` VALUES ('345', 'Roni', '2664');
INSERT INTO `app_cities` VALUES ('346', 'Sule-tankarkar', '2664');
INSERT INTO `app_cities` VALUES ('347', 'Taura', '2664');
INSERT INTO `app_cities` VALUES ('348', 'Yankwashi', '2664');
INSERT INTO `app_cities` VALUES ('349', 'Birnin-gwari', '2665');
INSERT INTO `app_cities` VALUES ('350', 'Chikun', '2665');
INSERT INTO `app_cities` VALUES ('351', 'Giwa', '2665');
INSERT INTO `app_cities` VALUES ('352', 'Igabi', '2665');
INSERT INTO `app_cities` VALUES ('353', 'Ikara', '2665');
INSERT INTO `app_cities` VALUES ('354', 'Jaba', '2665');
INSERT INTO `app_cities` VALUES ('355', 'Jema\'a', '2665');
INSERT INTO `app_cities` VALUES ('356', 'Kachia', '2665');
INSERT INTO `app_cities` VALUES ('357', 'Kaduna north', '2665');
INSERT INTO `app_cities` VALUES ('358', 'Kaduna south', '2665');
INSERT INTO `app_cities` VALUES ('359', 'Kagarko', '2665');
INSERT INTO `app_cities` VALUES ('360', 'Kajuru', '2665');
INSERT INTO `app_cities` VALUES ('361', 'Kaura', '2665');
INSERT INTO `app_cities` VALUES ('362', 'Kubau', '2665');
INSERT INTO `app_cities` VALUES ('363', 'Kudan', '2665');
INSERT INTO `app_cities` VALUES ('364', 'Lere', '2665');
INSERT INTO `app_cities` VALUES ('365', 'Makarfi', '2665');
INSERT INTO `app_cities` VALUES ('366', 'Sabon-gari', '2665');
INSERT INTO `app_cities` VALUES ('367', 'Sanga', '2665');
INSERT INTO `app_cities` VALUES ('368', 'Soba', '2665');
INSERT INTO `app_cities` VALUES ('369', 'Zangon-kataf', '2665');
INSERT INTO `app_cities` VALUES ('370', 'Zaria', '2665');
INSERT INTO `app_cities` VALUES ('371', 'Ajingi', '2666');
INSERT INTO `app_cities` VALUES ('372', 'Albasu', '2666');
INSERT INTO `app_cities` VALUES ('373', 'Bagwai', '2666');
INSERT INTO `app_cities` VALUES ('374', 'Bebeji', '2666');
INSERT INTO `app_cities` VALUES ('375', 'Bichi', '2666');
INSERT INTO `app_cities` VALUES ('376', 'Bunkure', '2666');
INSERT INTO `app_cities` VALUES ('377', 'Dala', '2666');
INSERT INTO `app_cities` VALUES ('378', 'Dambatta', '2666');
INSERT INTO `app_cities` VALUES ('379', 'Dawakin kudu', '2666');
INSERT INTO `app_cities` VALUES ('380', 'Dawakin tofa', '2666');
INSERT INTO `app_cities` VALUES ('381', 'Doguwa', '2666');
INSERT INTO `app_cities` VALUES ('382', 'Fagge', '2666');
INSERT INTO `app_cities` VALUES ('383', 'Gabasawa', '2666');
INSERT INTO `app_cities` VALUES ('384', 'Garko', '2666');
INSERT INTO `app_cities` VALUES ('385', 'Garum mallarn', '2666');
INSERT INTO `app_cities` VALUES ('386', 'Gaya', '2666');
INSERT INTO `app_cities` VALUES ('387', 'Gezawa', '2666');
INSERT INTO `app_cities` VALUES ('388', 'Gwale', '2666');
INSERT INTO `app_cities` VALUES ('389', 'Gwarzo', '2666');
INSERT INTO `app_cities` VALUES ('390', 'Kabo', '2666');
INSERT INTO `app_cities` VALUES ('391', 'Kano municipal', '2666');
INSERT INTO `app_cities` VALUES ('392', 'Karaye', '2666');
INSERT INTO `app_cities` VALUES ('393', 'Kibiya', '2666');
INSERT INTO `app_cities` VALUES ('394', 'Kiru', '2666');
INSERT INTO `app_cities` VALUES ('395', 'Kumbotso', '2666');
INSERT INTO `app_cities` VALUES ('396', 'Kunchi', '2666');
INSERT INTO `app_cities` VALUES ('397', 'Kura', '2666');
INSERT INTO `app_cities` VALUES ('398', 'Madobi', '2666');
INSERT INTO `app_cities` VALUES ('399', 'Makoda', '2666');
INSERT INTO `app_cities` VALUES ('400', 'Minjibir', '2666');
INSERT INTO `app_cities` VALUES ('401', 'Nasarawa', '2666');
INSERT INTO `app_cities` VALUES ('402', 'Rano', '2666');
INSERT INTO `app_cities` VALUES ('403', 'Rimin gado', '2666');
INSERT INTO `app_cities` VALUES ('404', 'Rogo', '2666');
INSERT INTO `app_cities` VALUES ('405', 'Shanono', '2666');
INSERT INTO `app_cities` VALUES ('406', 'Sumaila', '2666');
INSERT INTO `app_cities` VALUES ('407', 'Takai', '2666');
INSERT INTO `app_cities` VALUES ('408', 'Tarauni', '2666');
INSERT INTO `app_cities` VALUES ('409', 'Tofa', '2666');
INSERT INTO `app_cities` VALUES ('410', 'Tsanyawa', '2666');
INSERT INTO `app_cities` VALUES ('411', 'Tudun wada', '2666');
INSERT INTO `app_cities` VALUES ('412', 'Ungogo', '2666');
INSERT INTO `app_cities` VALUES ('413', 'Warawa', '2666');
INSERT INTO `app_cities` VALUES ('414', 'Wudil', '2666');
INSERT INTO `app_cities` VALUES ('415', 'Bakori', '2667');
INSERT INTO `app_cities` VALUES ('416', 'Batagarawa', '2667');
INSERT INTO `app_cities` VALUES ('417', 'Batsari', '2667');
INSERT INTO `app_cities` VALUES ('418', 'Baure', '2667');
INSERT INTO `app_cities` VALUES ('419', 'Bindawa', '2667');
INSERT INTO `app_cities` VALUES ('420', 'Charanchi', '2667');
INSERT INTO `app_cities` VALUES ('421', 'Dandume', '2667');
INSERT INTO `app_cities` VALUES ('422', 'Danja', '2667');
INSERT INTO `app_cities` VALUES ('423', 'Dan musa', '2667');
INSERT INTO `app_cities` VALUES ('424', 'Daura', '2667');
INSERT INTO `app_cities` VALUES ('425', 'Dutsi', '2667');
INSERT INTO `app_cities` VALUES ('426', 'Dutsin-ma', '2667');
INSERT INTO `app_cities` VALUES ('427', 'Faskari', '2667');
INSERT INTO `app_cities` VALUES ('428', 'Funtua', '2667');
INSERT INTO `app_cities` VALUES ('429', 'Ingawa', '2667');
INSERT INTO `app_cities` VALUES ('430', 'Jibia', '2667');
INSERT INTO `app_cities` VALUES ('431', 'Kafur', '2667');
INSERT INTO `app_cities` VALUES ('432', 'Kaita', '2667');
INSERT INTO `app_cities` VALUES ('433', 'Kankara', '2667');
INSERT INTO `app_cities` VALUES ('434', 'Kankia', '2667');
INSERT INTO `app_cities` VALUES ('435', 'Katsina', '2667');
INSERT INTO `app_cities` VALUES ('436', 'Kurfi', '2667');
INSERT INTO `app_cities` VALUES ('437', 'Kusada', '2667');
INSERT INTO `app_cities` VALUES ('438', 'Mai\'adua', '2667');
INSERT INTO `app_cities` VALUES ('439', 'Malumfashi', '2667');
INSERT INTO `app_cities` VALUES ('440', 'Mani', '2667');
INSERT INTO `app_cities` VALUES ('441', 'Mashi', '2667');
INSERT INTO `app_cities` VALUES ('442', 'Matazu', '2667');
INSERT INTO `app_cities` VALUES ('443', 'Musawa', '2667');
INSERT INTO `app_cities` VALUES ('444', 'Rimi', '2667');
INSERT INTO `app_cities` VALUES ('445', 'Sabuwa', '2667');
INSERT INTO `app_cities` VALUES ('446', 'Safana', '2667');
INSERT INTO `app_cities` VALUES ('447', 'Sandamu', '2667');
INSERT INTO `app_cities` VALUES ('448', 'Zongo', '2667');
INSERT INTO `app_cities` VALUES ('449', 'Aleiro', '2668');
INSERT INTO `app_cities` VALUES ('450', 'Arewa-dandi', '2668');
INSERT INTO `app_cities` VALUES ('451', 'Argungu', '2668');
INSERT INTO `app_cities` VALUES ('452', 'Augie', '2668');
INSERT INTO `app_cities` VALUES ('453', 'Bagudo', '2668');
INSERT INTO `app_cities` VALUES ('454', 'Birnin kebbi', '2668');
INSERT INTO `app_cities` VALUES ('455', 'Bunza', '2668');
INSERT INTO `app_cities` VALUES ('456', 'Dandi', '2668');
INSERT INTO `app_cities` VALUES ('457', 'Fakai', '2668');
INSERT INTO `app_cities` VALUES ('458', 'Gwandu', '2668');
INSERT INTO `app_cities` VALUES ('459', 'Jega', '2668');
INSERT INTO `app_cities` VALUES ('460', 'Kalgo', '2668');
INSERT INTO `app_cities` VALUES ('461', 'Koko/besse', '2668');
INSERT INTO `app_cities` VALUES ('462', 'Maiyama', '2668');
INSERT INTO `app_cities` VALUES ('463', 'Ngaski', '2668');
INSERT INTO `app_cities` VALUES ('464', 'Sakaba', '2668');
INSERT INTO `app_cities` VALUES ('465', 'Shanga', '2668');
INSERT INTO `app_cities` VALUES ('466', 'Suru', '2668');
INSERT INTO `app_cities` VALUES ('467', 'Wasagu/danko', '2668');
INSERT INTO `app_cities` VALUES ('468', 'Yauri', '2668');
INSERT INTO `app_cities` VALUES ('469', 'Zuru', '2668');
INSERT INTO `app_cities` VALUES ('470', 'Adavi', '2669');
INSERT INTO `app_cities` VALUES ('471', 'Ajaojuta', '2669');
INSERT INTO `app_cities` VALUES ('472', 'Ankpa', '2669');
INSERT INTO `app_cities` VALUES ('473', 'Bassa', '2669');
INSERT INTO `app_cities` VALUES ('474', 'Dekina', '2669');
INSERT INTO `app_cities` VALUES ('475', 'Ibaji', '2669');
INSERT INTO `app_cities` VALUES ('476', 'Igalamela-odolu', '2669');
INSERT INTO `app_cities` VALUES ('477', 'Ijumu', '2669');
INSERT INTO `app_cities` VALUES ('478', 'Ijumu', '2669');
INSERT INTO `app_cities` VALUES ('479', 'Kabba/bunu', '2669');
INSERT INTO `app_cities` VALUES ('480', 'Kogi', '2669');
INSERT INTO `app_cities` VALUES ('481', 'Lokoja', '2669');
INSERT INTO `app_cities` VALUES ('482', 'Mopa-muro', '2669');
INSERT INTO `app_cities` VALUES ('483', 'Ofu', '2669');
INSERT INTO `app_cities` VALUES ('484', 'Ogori/megongo', '2669');
INSERT INTO `app_cities` VALUES ('485', 'Okehi', '2669');
INSERT INTO `app_cities` VALUES ('486', 'Olamabolo', '2669');
INSERT INTO `app_cities` VALUES ('487', 'Omala', '2669');
INSERT INTO `app_cities` VALUES ('488', 'Yagba east', '2669');
INSERT INTO `app_cities` VALUES ('489', 'Yagba west', '2669');
INSERT INTO `app_cities` VALUES ('490', 'Asa', '2670');
INSERT INTO `app_cities` VALUES ('491', 'Baruten', '2670');
INSERT INTO `app_cities` VALUES ('492', 'Edu', '2670');
INSERT INTO `app_cities` VALUES ('493', 'Ekiti', '2670');
INSERT INTO `app_cities` VALUES ('494', 'Ifelodun', '2670');
INSERT INTO `app_cities` VALUES ('495', 'Ilorin south', '2670');
INSERT INTO `app_cities` VALUES ('496', 'Ilorin west', '2670');
INSERT INTO `app_cities` VALUES ('497', 'Irepodun', '2670');
INSERT INTO `app_cities` VALUES ('498', 'Isin', '2670');
INSERT INTO `app_cities` VALUES ('499', 'Kaiama', '2670');
INSERT INTO `app_cities` VALUES ('500', 'Moro', '2670');
INSERT INTO `app_cities` VALUES ('501', 'Offa', '2670');
INSERT INTO `app_cities` VALUES ('502', 'Oke-ero', '2670');
INSERT INTO `app_cities` VALUES ('503', 'Oyun', '2670');
INSERT INTO `app_cities` VALUES ('504', 'Pategi', '2670');
INSERT INTO `app_cities` VALUES ('505', 'Agege', '2671');
INSERT INTO `app_cities` VALUES ('506', 'Ajeromi-ifelodun', '2671');
INSERT INTO `app_cities` VALUES ('507', 'Alimosho', '2671');
INSERT INTO `app_cities` VALUES ('508', 'Amuwo-odofin', '2671');
INSERT INTO `app_cities` VALUES ('509', 'Apapa', '2671');
INSERT INTO `app_cities` VALUES ('510', 'Badagry', '2671');
INSERT INTO `app_cities` VALUES ('511', 'Epe', '2671');
INSERT INTO `app_cities` VALUES ('512', 'Eti-osa', '2671');
INSERT INTO `app_cities` VALUES ('513', 'Ibeju/lekki', '2671');
INSERT INTO `app_cities` VALUES ('514', 'Ifako-ijaye', '2671');
INSERT INTO `app_cities` VALUES ('515', 'Ikeja', '2671');
INSERT INTO `app_cities` VALUES ('516', 'Ikorodu', '2671');
INSERT INTO `app_cities` VALUES ('517', 'Kosofe', '2671');
INSERT INTO `app_cities` VALUES ('518', 'Lagos island', '2671');
INSERT INTO `app_cities` VALUES ('519', 'Lagos mainland', '2671');
INSERT INTO `app_cities` VALUES ('520', 'Mushin', '2671');
INSERT INTO `app_cities` VALUES ('521', 'Ojo', '2671');
INSERT INTO `app_cities` VALUES ('522', 'Oshodi-isolo', '2671');
INSERT INTO `app_cities` VALUES ('523', 'Shomolu', '2671');
INSERT INTO `app_cities` VALUES ('524', 'Surulere', '2671');
INSERT INTO `app_cities` VALUES ('525', 'Akwanga', '2672');
INSERT INTO `app_cities` VALUES ('526', 'Awe', '2672');
INSERT INTO `app_cities` VALUES ('527', 'Doma', '2672');
INSERT INTO `app_cities` VALUES ('528', 'Karu', '2672');
INSERT INTO `app_cities` VALUES ('529', 'Keana', '2672');
INSERT INTO `app_cities` VALUES ('530', 'Keffi', '2672');
INSERT INTO `app_cities` VALUES ('531', 'Kokona', '2672');
INSERT INTO `app_cities` VALUES ('532', 'Lafia', '2672');
INSERT INTO `app_cities` VALUES ('533', 'Nasarawa', '2672');
INSERT INTO `app_cities` VALUES ('534', 'Nasarawa-eggon', '2672');
INSERT INTO `app_cities` VALUES ('535', 'Obi', '2672');
INSERT INTO `app_cities` VALUES ('536', 'Toto', '2672');
INSERT INTO `app_cities` VALUES ('537', 'Wamba', '2672');
INSERT INTO `app_cities` VALUES ('538', 'Agaie', '2673');
INSERT INTO `app_cities` VALUES ('539', 'Agwara', '2673');
INSERT INTO `app_cities` VALUES ('540', 'Bida', '2673');
INSERT INTO `app_cities` VALUES ('541', 'Borgu', '2673');
INSERT INTO `app_cities` VALUES ('542', 'Bosso', '2673');
INSERT INTO `app_cities` VALUES ('543', 'Chanchaga', '2673');
INSERT INTO `app_cities` VALUES ('544', 'Edati', '2673');
INSERT INTO `app_cities` VALUES ('545', 'Gbako', '2673');
INSERT INTO `app_cities` VALUES ('546', 'Gurara', '2673');
INSERT INTO `app_cities` VALUES ('547', 'Katcha', '2673');
INSERT INTO `app_cities` VALUES ('548', 'Kontagora', '2673');
INSERT INTO `app_cities` VALUES ('549', 'Lapai', '2673');
INSERT INTO `app_cities` VALUES ('550', 'Lavun', '2673');
INSERT INTO `app_cities` VALUES ('551', 'Magama', '2673');
INSERT INTO `app_cities` VALUES ('552', 'Mariga', '2673');
INSERT INTO `app_cities` VALUES ('553', 'Mashegu', '2673');
INSERT INTO `app_cities` VALUES ('554', 'Mokwa', '2673');
INSERT INTO `app_cities` VALUES ('555', 'Muya', '2673');
INSERT INTO `app_cities` VALUES ('556', 'Paikoro', '2673');
INSERT INTO `app_cities` VALUES ('557', 'Rafi', '2673');
INSERT INTO `app_cities` VALUES ('558', 'Rajau', '2673');
INSERT INTO `app_cities` VALUES ('559', 'Shiroro', '2673');
INSERT INTO `app_cities` VALUES ('560', 'Suleja', '2673');
INSERT INTO `app_cities` VALUES ('561', 'Tafa', '2673');
INSERT INTO `app_cities` VALUES ('562', 'Wushishi', '2673');
INSERT INTO `app_cities` VALUES ('563', 'Abeokuta north', '2674');
INSERT INTO `app_cities` VALUES ('564', 'Abeokuta south', '2674');
INSERT INTO `app_cities` VALUES ('565', 'Ado-odo/ota', '2674');
INSERT INTO `app_cities` VALUES ('566', 'Egbado north', '2674');
INSERT INTO `app_cities` VALUES ('567', 'Egbado south', '2674');
INSERT INTO `app_cities` VALUES ('568', 'Ekwekoro', '2674');
INSERT INTO `app_cities` VALUES ('569', 'Ifo', '2674');
INSERT INTO `app_cities` VALUES ('570', 'Ijebu east', '2674');
INSERT INTO `app_cities` VALUES ('571', 'Ijebu north', '2674');
INSERT INTO `app_cities` VALUES ('572', 'Ijebu north east', '2674');
INSERT INTO `app_cities` VALUES ('573', 'Ijebu-ode', '2674');
INSERT INTO `app_cities` VALUES ('574', 'Ikenne', '2674');
INSERT INTO `app_cities` VALUES ('575', 'Imeko-afon', '2674');
INSERT INTO `app_cities` VALUES ('576', 'Ipokia', '2674');
INSERT INTO `app_cities` VALUES ('577', 'Obafemi-owode', '2674');
INSERT INTO `app_cities` VALUES ('578', 'Ogun waterside', '2674');
INSERT INTO `app_cities` VALUES ('579', 'Odeda', '2674');
INSERT INTO `app_cities` VALUES ('580', 'Odogbolu', '2674');
INSERT INTO `app_cities` VALUES ('581', 'Remo north', '2674');
INSERT INTO `app_cities` VALUES ('582', 'Shagamu', '2674');
INSERT INTO `app_cities` VALUES ('583', 'Akoko north east', '2675');
INSERT INTO `app_cities` VALUES ('584', 'Akoko north west', '2675');
INSERT INTO `app_cities` VALUES ('585', 'Akoko south east', '2675');
INSERT INTO `app_cities` VALUES ('586', 'Akoko south west', '2675');
INSERT INTO `app_cities` VALUES ('587', 'Akure north', '2675');
INSERT INTO `app_cities` VALUES ('588', 'Akuresouth', '2675');
INSERT INTO `app_cities` VALUES ('589', 'Ese-odo', '2675');
INSERT INTO `app_cities` VALUES ('590', 'Idanre', '2675');
INSERT INTO `app_cities` VALUES ('591', 'Ifedore', '2675');
INSERT INTO `app_cities` VALUES ('592', 'Ilaje', '2675');
INSERT INTO `app_cities` VALUES ('593', 'Ile-oluji-okeigbo', '2675');
INSERT INTO `app_cities` VALUES ('594', 'Irele', '2675');
INSERT INTO `app_cities` VALUES ('595', 'Odigbo', '2675');
INSERT INTO `app_cities` VALUES ('596', 'Okitipupa', '2675');
INSERT INTO `app_cities` VALUES ('597', 'Ondo east', '2675');
INSERT INTO `app_cities` VALUES ('598', 'Ondo west', '2675');
INSERT INTO `app_cities` VALUES ('599', 'Ose-owo', '2675');
INSERT INTO `app_cities` VALUES ('600', 'Aiyedade', '2676');
INSERT INTO `app_cities` VALUES ('601', 'Aiyedire', '2676');
INSERT INTO `app_cities` VALUES ('602', 'Atakumosa east', '2676');
INSERT INTO `app_cities` VALUES ('603', 'Atakumose-west', '2676');
INSERT INTO `app_cities` VALUES ('604', 'Boluwaduro', '2676');
INSERT INTO `app_cities` VALUES ('605', 'Boripe', '2676');
INSERT INTO `app_cities` VALUES ('606', 'Ede north', '2676');
INSERT INTO `app_cities` VALUES ('607', 'Ede south', '2676');
INSERT INTO `app_cities` VALUES ('608', 'Egbedore', '2676');
INSERT INTO `app_cities` VALUES ('609', 'Ejigbo', '2676');
INSERT INTO `app_cities` VALUES ('610', 'Ife central', '2676');
INSERT INTO `app_cities` VALUES ('611', 'Ife east', '2676');
INSERT INTO `app_cities` VALUES ('612', 'Ife north', '2676');
INSERT INTO `app_cities` VALUES ('613', 'Ife south', '2676');
INSERT INTO `app_cities` VALUES ('614', 'Ifedayo', '2676');
INSERT INTO `app_cities` VALUES ('615', 'Ifelodun', '2676');
INSERT INTO `app_cities` VALUES ('616', 'Ila', '2676');
INSERT INTO `app_cities` VALUES ('617', 'Ilasha east', '2676');
INSERT INTO `app_cities` VALUES ('618', 'Ilesha west', '2676');
INSERT INTO `app_cities` VALUES ('619', 'Irepodun', '2676');
INSERT INTO `app_cities` VALUES ('620', 'Irewole', '2676');
INSERT INTO `app_cities` VALUES ('621', 'Isokan', '2676');
INSERT INTO `app_cities` VALUES ('622', 'Iwo', '2676');
INSERT INTO `app_cities` VALUES ('623', 'Obokun', '2676');
INSERT INTO `app_cities` VALUES ('624', 'Odo-otin', '2676');
INSERT INTO `app_cities` VALUES ('625', 'Ola-oluwa', '2676');
INSERT INTO `app_cities` VALUES ('626', 'Olorunda', '2676');
INSERT INTO `app_cities` VALUES ('627', 'Oriade', '2676');
INSERT INTO `app_cities` VALUES ('628', 'Orolu', '2676');
INSERT INTO `app_cities` VALUES ('629', 'Osogbo', '2676');
INSERT INTO `app_cities` VALUES ('630', 'Afijio', '2677');
INSERT INTO `app_cities` VALUES ('631', 'Akinyele', '2677');
INSERT INTO `app_cities` VALUES ('632', 'Atiba', '2677');
INSERT INTO `app_cities` VALUES ('633', 'Atigbo', '2677');
INSERT INTO `app_cities` VALUES ('634', 'Egbeda', '2677');
INSERT INTO `app_cities` VALUES ('635', 'Ibadan central', '2677');
INSERT INTO `app_cities` VALUES ('636', 'Ibadan north', '2677');
INSERT INTO `app_cities` VALUES ('637', 'Ibadan north west', '2677');
INSERT INTO `app_cities` VALUES ('638', 'Ibadan south west', '2677');
INSERT INTO `app_cities` VALUES ('639', 'Ibadan south east', '2677');
INSERT INTO `app_cities` VALUES ('640', 'Ibarapa central', '2677');
INSERT INTO `app_cities` VALUES ('641', 'Ibarapa east', '2677');
INSERT INTO `app_cities` VALUES ('642', 'Ibarapa north', '2677');
INSERT INTO `app_cities` VALUES ('643', 'Ido', '2677');
INSERT INTO `app_cities` VALUES ('644', 'Irepo', '2677');
INSERT INTO `app_cities` VALUES ('645', 'Iseyin', '2677');
INSERT INTO `app_cities` VALUES ('646', 'Itesiwaju', '2677');
INSERT INTO `app_cities` VALUES ('647', 'Iwajowa', '2677');
INSERT INTO `app_cities` VALUES ('648', 'Kajola', '2677');
INSERT INTO `app_cities` VALUES ('649', 'Lagelu', '2677');
INSERT INTO `app_cities` VALUES ('650', 'Ogbomoso north', '2677');
INSERT INTO `app_cities` VALUES ('651', 'Ogbomoso south', '2677');
INSERT INTO `app_cities` VALUES ('652', 'Ogo oluwa', '2677');
INSERT INTO `app_cities` VALUES ('653', 'Olorunsogo', '2677');
INSERT INTO `app_cities` VALUES ('654', 'Oluyole', '2677');
INSERT INTO `app_cities` VALUES ('655', 'Ona-ara', '2677');
INSERT INTO `app_cities` VALUES ('656', 'Orelope', '2677');
INSERT INTO `app_cities` VALUES ('657', 'Ori ire', '2677');
INSERT INTO `app_cities` VALUES ('658', 'Oyo east', '2677');
INSERT INTO `app_cities` VALUES ('659', 'Oyo west', '2677');
INSERT INTO `app_cities` VALUES ('660', 'Saki east', '2677');
INSERT INTO `app_cities` VALUES ('661', 'Saki west', '2677');
INSERT INTO `app_cities` VALUES ('662', 'Surelere', '2677');
INSERT INTO `app_cities` VALUES ('663', 'Barikin ladi', '2678');
INSERT INTO `app_cities` VALUES ('664', 'Bassa', '2678');
INSERT INTO `app_cities` VALUES ('665', 'Bokkos', '2678');
INSERT INTO `app_cities` VALUES ('666', 'Jos east', '2678');
INSERT INTO `app_cities` VALUES ('667', 'Jos north', '2678');
INSERT INTO `app_cities` VALUES ('668', 'Jos south', '2678');
INSERT INTO `app_cities` VALUES ('669', 'Kanam', '2678');
INSERT INTO `app_cities` VALUES ('670', 'Kanke', '2678');
INSERT INTO `app_cities` VALUES ('671', 'Langtang north', '2678');
INSERT INTO `app_cities` VALUES ('672', 'Langtang south', '2678');
INSERT INTO `app_cities` VALUES ('673', 'Mangu', '2678');
INSERT INTO `app_cities` VALUES ('674', 'Mikang', '2678');
INSERT INTO `app_cities` VALUES ('675', 'Pankshin', '2678');
INSERT INTO `app_cities` VALUES ('676', 'Qua\'an pan', '2678');
INSERT INTO `app_cities` VALUES ('677', 'Riyom', '2678');
INSERT INTO `app_cities` VALUES ('678', 'Shendam', '2678');
INSERT INTO `app_cities` VALUES ('679', 'Wase', '2678');
INSERT INTO `app_cities` VALUES ('680', 'Abua/odual', '2679');
INSERT INTO `app_cities` VALUES ('681', 'Ahoada east', '2679');
INSERT INTO `app_cities` VALUES ('682', 'Ahoada west', '2679');
INSERT INTO `app_cities` VALUES ('683', 'Akuku toru', '2679');
INSERT INTO `app_cities` VALUES ('684', 'Andoni', '2679');
INSERT INTO `app_cities` VALUES ('685', 'Asari-toru', '2679');
INSERT INTO `app_cities` VALUES ('686', 'Bonny', '2679');
INSERT INTO `app_cities` VALUES ('687', 'Degema', '2679');
INSERT INTO `app_cities` VALUES ('688', 'Emohua', '2679');
INSERT INTO `app_cities` VALUES ('689', 'Eleme', '2679');
INSERT INTO `app_cities` VALUES ('690', 'Etche', '2679');
INSERT INTO `app_cities` VALUES ('691', 'Gokana', '2679');
INSERT INTO `app_cities` VALUES ('692', 'Ikwerre', '2679');
INSERT INTO `app_cities` VALUES ('693', 'Khana', '2679');
INSERT INTO `app_cities` VALUES ('694', 'Obia/akpor', '2679');
INSERT INTO `app_cities` VALUES ('695', 'Ogba/egbema/ndoni', '2679');
INSERT INTO `app_cities` VALUES ('696', 'Ogu/bolo', '2679');
INSERT INTO `app_cities` VALUES ('697', 'Okrika', '2679');
INSERT INTO `app_cities` VALUES ('698', 'Omumma', '2679');
INSERT INTO `app_cities` VALUES ('699', 'Opobo/nkoro', '2679');
INSERT INTO `app_cities` VALUES ('700', 'Oyigbo', '2679');
INSERT INTO `app_cities` VALUES ('701', 'Port harcourt', '2679');
INSERT INTO `app_cities` VALUES ('702', 'Tai', '2679');
INSERT INTO `app_cities` VALUES ('703', 'Binji', '2680');
INSERT INTO `app_cities` VALUES ('704', 'Bodinga', '2680');
INSERT INTO `app_cities` VALUES ('705', 'Dange-shuni', '2680');
INSERT INTO `app_cities` VALUES ('706', 'Gada', '2680');
INSERT INTO `app_cities` VALUES ('707', 'Goronyo', '2680');
INSERT INTO `app_cities` VALUES ('708', 'Gudu', '2680');
INSERT INTO `app_cities` VALUES ('709', 'Gwadabawa', '2680');
INSERT INTO `app_cities` VALUES ('710', 'Illela', '2680');
INSERT INTO `app_cities` VALUES ('711', 'Isa', '2680');
INSERT INTO `app_cities` VALUES ('712', 'Kware', '2680');
INSERT INTO `app_cities` VALUES ('713', 'Kebbe', '2680');
INSERT INTO `app_cities` VALUES ('714', 'Rabah', '2680');
INSERT INTO `app_cities` VALUES ('715', 'Sabon birni', '2680');
INSERT INTO `app_cities` VALUES ('716', 'Shagari', '2680');
INSERT INTO `app_cities` VALUES ('717', 'Silame', '2680');
INSERT INTO `app_cities` VALUES ('718', 'Sokoto north', '2680');
INSERT INTO `app_cities` VALUES ('719', 'Sokoto south', '2680');
INSERT INTO `app_cities` VALUES ('720', 'Tambuwal', '2680');
INSERT INTO `app_cities` VALUES ('721', 'Tangaza', '2680');
INSERT INTO `app_cities` VALUES ('722', 'Tureta', '2680');
INSERT INTO `app_cities` VALUES ('723', 'Wamakko', '2680');
INSERT INTO `app_cities` VALUES ('724', 'Wurno', '2680');
INSERT INTO `app_cities` VALUES ('725', 'Yabo', '2680');
INSERT INTO `app_cities` VALUES ('726', 'Ardo-kola', '2681');
INSERT INTO `app_cities` VALUES ('727', 'Bali', '2681');
INSERT INTO `app_cities` VALUES ('728', 'Donga', '2681');
INSERT INTO `app_cities` VALUES ('729', 'Gashaka', '2681');
INSERT INTO `app_cities` VALUES ('730', 'Gassol', '2681');
INSERT INTO `app_cities` VALUES ('731', 'Ibi', '2681');
INSERT INTO `app_cities` VALUES ('732', 'Jalingo', '2681');
INSERT INTO `app_cities` VALUES ('733', 'Karim-lamido', '2681');
INSERT INTO `app_cities` VALUES ('734', 'Kurmi', '2681');
INSERT INTO `app_cities` VALUES ('735', 'Lau', '2681');
INSERT INTO `app_cities` VALUES ('736', 'Sarduana', '2681');
INSERT INTO `app_cities` VALUES ('737', 'Takum', '2681');
INSERT INTO `app_cities` VALUES ('738', 'Ussa', '2681');
INSERT INTO `app_cities` VALUES ('739', 'Wukari', '2681');
INSERT INTO `app_cities` VALUES ('740', 'Yorro', '2681');
INSERT INTO `app_cities` VALUES ('741', 'Zing', '2681');
INSERT INTO `app_cities` VALUES ('742', 'Bade', '2682');
INSERT INTO `app_cities` VALUES ('743', 'Bursari', '2682');
INSERT INTO `app_cities` VALUES ('744', 'Damaturu', '2682');
INSERT INTO `app_cities` VALUES ('745', 'Fika', '2682');
INSERT INTO `app_cities` VALUES ('746', 'Fune', '2682');
INSERT INTO `app_cities` VALUES ('747', 'Geidam', '2682');
INSERT INTO `app_cities` VALUES ('748', 'Gujba', '2682');
INSERT INTO `app_cities` VALUES ('749', 'Gulani', '2682');
INSERT INTO `app_cities` VALUES ('750', 'Jakusko', '2682');
INSERT INTO `app_cities` VALUES ('751', 'Karasuwa', '2682');
INSERT INTO `app_cities` VALUES ('752', 'Machina', '2682');
INSERT INTO `app_cities` VALUES ('753', 'Nangere', '2682');
INSERT INTO `app_cities` VALUES ('754', 'Nguru', '2682');
INSERT INTO `app_cities` VALUES ('755', 'Potiskum', '2682');
INSERT INTO `app_cities` VALUES ('756', 'Tarmua', '2682');
INSERT INTO `app_cities` VALUES ('757', 'Yunusari', '2682');
INSERT INTO `app_cities` VALUES ('758', 'Yusufari', '2682');
INSERT INTO `app_cities` VALUES ('759', 'Anka', '2683');
INSERT INTO `app_cities` VALUES ('760', 'Bakurna', '2683');
INSERT INTO `app_cities` VALUES ('761', 'Birnin magaji', '2683');
INSERT INTO `app_cities` VALUES ('762', 'Bukkuyum', '2683');
INSERT INTO `app_cities` VALUES ('763', 'Bungudu', '2683');
INSERT INTO `app_cities` VALUES ('764', 'Gummi', '2683');
INSERT INTO `app_cities` VALUES ('765', 'Kaura namoda', '2683');
INSERT INTO `app_cities` VALUES ('766', 'Maradun', '2683');
INSERT INTO `app_cities` VALUES ('767', 'Maru', '2683');
INSERT INTO `app_cities` VALUES ('768', 'Shinkafi', '2683');
INSERT INTO `app_cities` VALUES ('769', 'Talata', '2683');
INSERT INTO `app_cities` VALUES ('770', 'Mafara', '2683');
INSERT INTO `app_cities` VALUES ('771', 'Tsafe', '2683');
INSERT INTO `app_cities` VALUES ('772', 'Zumi', '2683');
INSERT INTO `app_cities` VALUES ('773', 'Eggon', '2672');
INSERT INTO `app_cities` VALUES ('774', 'Ile oluji', '2675');
INSERT INTO `app_cities` VALUES ('775', 'Sagamu', '2674');
INSERT INTO `app_cities` VALUES ('776', 'Opeji', '2674');
INSERT INTO `app_cities` VALUES ('777', 'Ijebu ode', '2674');
INSERT INTO `app_cities` VALUES ('778', 'Ishan', '2659');
INSERT INTO `app_cities` VALUES ('779', 'Ondo central', '2675');
INSERT INTO `app_cities` VALUES ('780', 'Otukpo', '2654');
INSERT INTO `app_cities` VALUES ('781', 'Abaji', '2648');
INSERT INTO `app_cities` VALUES ('782', 'Abuja Municipal', '2648');
INSERT INTO `app_cities` VALUES ('783', 'Bwari', '2648');
INSERT INTO `app_cities` VALUES ('784', 'Gwagwalada', '2648');
INSERT INTO `app_cities` VALUES ('785', 'Kuje', '2648');
INSERT INTO `app_cities` VALUES ('786', 'Kwali', '2648');
INSERT INTO `app_cities` VALUES ('787', 'Ehime mbano', '2663');
INSERT INTO `app_cities` VALUES ('788', 'Oji river', '2661');
INSERT INTO `app_cities` VALUES ('789', 'Ogbomosho', '2677');
INSERT INTO `app_cities` VALUES ('790', 'Akure south', '2675');
INSERT INTO `app_cities` VALUES ('791', 'Odupani', '2656');
INSERT INTO `app_cities` VALUES ('792', 'Ngor okpala', '2663');
INSERT INTO `app_cities` VALUES ('793', 'Ador', '2654');
INSERT INTO `app_cities` VALUES ('794', 'Okobo', '2650');
INSERT INTO `app_cities` VALUES ('795', 'Idah', '2669');
INSERT INTO `app_cities` VALUES ('796', 'Ugwunagbor', '2647');
INSERT INTO `app_cities` VALUES ('797', 'Ogba/Egbem/Noom', '2679');
INSERT INTO `app_cities` VALUES ('798', 'Okene', '2669');
INSERT INTO `app_cities` VALUES ('799', 'Akoko', '2675');
INSERT INTO `app_cities` VALUES ('800', 'Owo', '2675');
INSERT INTO `app_cities` VALUES ('801', 'Kamba', '2668');
INSERT INTO `app_cities` VALUES ('802', 'Water side', '2674');
INSERT INTO `app_cities` VALUES ('803', 'Egado South', '2674');
INSERT INTO `app_cities` VALUES ('804', 'Imeko Afon', '2674');
INSERT INTO `app_cities` VALUES ('805', 'Panilshin', '2678');
INSERT INTO `app_cities` VALUES ('806', 'Ikalo', '2675');
INSERT INTO `app_cities` VALUES ('807', 'Eredo', '2671');
INSERT INTO `app_cities` VALUES ('808', 'Manufanoti', '2667');
INSERT INTO `app_cities` VALUES ('809', 'Kofa atiku', '2679');
INSERT INTO `app_cities` VALUES ('811', 'Onna', '2650');
INSERT INTO `app_cities` VALUES ('812', 'Udium', '2650');
INSERT INTO `app_cities` VALUES ('813', 'Ake', '2674');
INSERT INTO `app_cities` VALUES ('814', 'Uromi', '2659');
INSERT INTO `app_cities` VALUES ('815', 'Oron', '2650');
INSERT INTO `app_cities` VALUES ('816', 'Oruk', '2650');
INSERT INTO `app_cities` VALUES ('817', 'Aniocha', '2657');
INSERT INTO `app_cities` VALUES ('818', 'Ose', '2675');
INSERT INTO `app_cities` VALUES ('819', 'Oro', '2670');
INSERT INTO `app_cities` VALUES ('820', 'Yewa', '2674');
INSERT INTO `app_cities` VALUES ('821', 'Yewa South', '2674');
INSERT INTO `app_cities` VALUES ('822', 'Yewa North', '2674');
INSERT INTO `app_cities` VALUES ('823', 'Opobo/Nkoro', '2679');
INSERT INTO `app_cities` VALUES ('824', 'Onecities', '2679');
INSERT INTO `app_cities` VALUES ('826', 'Maiduguri .M.C', '2655');

-- ----------------------------
-- Table structure for `app_countries`
-- ----------------------------
DROP TABLE IF EXISTS `app_countries`;
CREATE TABLE `app_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortname` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phonecode` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_countries
-- ----------------------------
INSERT INTO `app_countries` VALUES ('160', 'NG', 'Nigeria', '234');

-- ----------------------------
-- Table structure for `app_course`
-- ----------------------------
DROP TABLE IF EXISTS `app_course`;
CREATE TABLE `app_course` (
  `course_id` varchar(100) NOT NULL DEFAULT '',
  `course_title` varchar(200) DEFAULT NULL,
  `course_msg` varchar(245) DEFAULT NULL,
  `course_image` varchar(100) DEFAULT NULL,
  `status` varchar(2) DEFAULT '0',
  `apply_status` varchar(2) DEFAULT '0',
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of app_course
-- ----------------------------
INSERT INTO `app_course` VALUES ('123', 'midwifery', 'hello', 'images/slider1.jpg', '1', '0');

-- ----------------------------
-- Table structure for `app_education_qualification_type`
-- ----------------------------
DROP TABLE IF EXISTS `app_education_qualification_type`;
CREATE TABLE `app_education_qualification_type` (
  `typeid` varchar(90) NOT NULL,
  `description` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of app_education_qualification_type
-- ----------------------------
INSERT INTO `app_education_qualification_type` VALUES ('1', 'Primary School Certificate');
INSERT INTO `app_education_qualification_type` VALUES ('2', 'Basic School Certificate');
INSERT INTO `app_education_qualification_type` VALUES ('3', 'Senior School Certificate');

-- ----------------------------
-- Table structure for `app_educational_qualification_tb`
-- ----------------------------
DROP TABLE IF EXISTS `app_educational_qualification_tb`;
CREATE TABLE `app_educational_qualification_tb` (
  `reg_id` varchar(16) NOT NULL,
  `pri_school_name` varchar(56) NOT NULL,
  `pri_certificate_obtained` varchar(36) NOT NULL,
  `pri_end_date` varchar(4) NOT NULL,
  `jun_school_name` varchar(56) DEFAULT NULL,
  `jun_certificate_obtained` varchar(36) DEFAULT NULL,
  `jun_end_date` varchar(4) DEFAULT NULL,
  `sec_school_name` varchar(56) DEFAULT NULL,
  `sec_certificate_obtained` varchar(36) DEFAULT NULL,
  `sec_end_date` varchar(4) DEFAULT NULL,
  `school_name_address_4` varchar(56) DEFAULT NULL,
  `certificate_obtained_4` varchar(36) DEFAULT NULL,
  `school_date_4` varchar(4) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`reg_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_educational_qualification_tb
-- ----------------------------
INSERT INTO `app_educational_qualification_tb` VALUES ('2019000058', 'frkkkkkkkkkkkkk', 'Primary School Certificate', '2019', 'gjgjgjgjgjgjg', 'Senior School Certificate', '2018', '', 'Senior School Certificate', '%23', null, null, null, '2019-07-09 17:52:31');
INSERT INTO `app_educational_qualification_tb` VALUES ('2020000000', 'saint francis', 'Primary School Certificate', '2011', 'emmanuel', 'Senior School Certificate', '2006', 'abuja', 'Senior School Certificate', '2007', null, null, null, '2020-02-10 12:33:35');
INSERT INTO `app_educational_qualification_tb` VALUES ('20202020000331', 'sedfg', 'Primary School Certificate', '2008', 'wertf', 'Senior School Certificate', '2007', 'wert', 'Senior School Certificate', '2017', null, null, null, '2020-02-10 15:18:20');

-- ----------------------------
-- Table structure for `app_lga`
-- ----------------------------
DROP TABLE IF EXISTS `app_lga`;
CREATE TABLE `app_lga` (
  `Lgaid` int(11) NOT NULL AUTO_INCREMENT,
  `Lga` varchar(50) DEFAULT NULL,
  `StateId` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Lgaid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=827 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_lga
-- ----------------------------
INSERT INTO `app_lga` VALUES ('2', 'Asaritoru', '2679');
INSERT INTO `app_lga` VALUES ('3', 'Aboh mbaise', '2663');
INSERT INTO `app_lga` VALUES ('5', 'Oluyole', '2677');
INSERT INTO `app_lga` VALUES ('6', 'Bekwara', '2656');
INSERT INTO `app_lga` VALUES ('7', 'Abeokuta east', '2674');
INSERT INTO `app_lga` VALUES ('8', 'Yemoji', '2674');
INSERT INTO `app_lga` VALUES ('9', 'Etsakor', '2659');
INSERT INTO `app_lga` VALUES ('10', 'Ethiope west', '2657');
INSERT INTO `app_lga` VALUES ('11', 'Idemili', '2651');
INSERT INTO `app_lga` VALUES ('12', 'Ijumu iyara', '2669');
INSERT INTO `app_lga` VALUES ('13', 'Mopa-muro', '2669');
INSERT INTO `app_lga` VALUES ('14', 'Aba north', '2647');
INSERT INTO `app_lga` VALUES ('15', 'Aba south', '2647');
INSERT INTO `app_lga` VALUES ('16', 'Arochukwu', '2647');
INSERT INTO `app_lga` VALUES ('17', 'Bende', '2647');
INSERT INTO `app_lga` VALUES ('18', 'Ikwuano', '2647');
INSERT INTO `app_lga` VALUES ('19', 'Isiala-ngwa north', '2647');
INSERT INTO `app_lga` VALUES ('20', 'Isiala-ngwa south', '2647');
INSERT INTO `app_lga` VALUES ('21', 'Isukwuato', '2647');
INSERT INTO `app_lga` VALUES ('22', 'Obiomangwa', '2647');
INSERT INTO `app_lga` VALUES ('23', 'Ohafia', '2647');
INSERT INTO `app_lga` VALUES ('24', 'Osisioma ngwa', '2647');
INSERT INTO `app_lga` VALUES ('25', 'Ugwunagbo', '2647');
INSERT INTO `app_lga` VALUES ('26', 'Ukwa east', '2647');
INSERT INTO `app_lga` VALUES ('27', 'Ukwa west', '2647');
INSERT INTO `app_lga` VALUES ('28', 'Umuahia north', '2647');
INSERT INTO `app_lga` VALUES ('29', 'Umuahia south', '2647');
INSERT INTO `app_lga` VALUES ('30', 'Umu-nneochi', '2647');
INSERT INTO `app_lga` VALUES ('31', 'Demsa', '2649');
INSERT INTO `app_lga` VALUES ('32', 'Fufore', '2649');
INSERT INTO `app_lga` VALUES ('33', 'Ganye', '2649');
INSERT INTO `app_lga` VALUES ('34', 'Girei', '2649');
INSERT INTO `app_lga` VALUES ('35', 'Gombi', '2649');
INSERT INTO `app_lga` VALUES ('36', 'Guyuk', '2649');
INSERT INTO `app_lga` VALUES ('37', 'Hong', '2649');
INSERT INTO `app_lga` VALUES ('38', 'Jada', '2649');
INSERT INTO `app_lga` VALUES ('39', 'Lamurde', '2649');
INSERT INTO `app_lga` VALUES ('40', 'Madagali', '2649');
INSERT INTO `app_lga` VALUES ('41', 'Maiha', '2649');
INSERT INTO `app_lga` VALUES ('42', 'Mayo-belwa', '2649');
INSERT INTO `app_lga` VALUES ('43', 'Michika', '2649');
INSERT INTO `app_lga` VALUES ('44', 'Mubi north', '2649');
INSERT INTO `app_lga` VALUES ('45', 'Mubi south', '2649');
INSERT INTO `app_lga` VALUES ('46', 'Numan', '2649');
INSERT INTO `app_lga` VALUES ('47', 'Shelleng', '2649');
INSERT INTO `app_lga` VALUES ('48', 'Song', '2649');
INSERT INTO `app_lga` VALUES ('49', 'Toungo', '2649');
INSERT INTO `app_lga` VALUES ('50', 'Yola north', '2649');
INSERT INTO `app_lga` VALUES ('51', 'Yola south', '2649');
INSERT INTO `app_lga` VALUES ('52', 'Abak', '2650');
INSERT INTO `app_lga` VALUES ('53', 'Eastern obolo', '2650');
INSERT INTO `app_lga` VALUES ('54', 'Eket', '2650');
INSERT INTO `app_lga` VALUES ('55', 'Esit eket', '2650');
INSERT INTO `app_lga` VALUES ('56', 'Essien udim', '2650');
INSERT INTO `app_lga` VALUES ('57', 'Etim ekpo', '2650');
INSERT INTO `app_lga` VALUES ('58', 'Etinan', '2650');
INSERT INTO `app_lga` VALUES ('59', 'Ibeno', '2650');
INSERT INTO `app_lga` VALUES ('60', 'Ibesikpo asutan', '2650');
INSERT INTO `app_lga` VALUES ('61', 'Ibiono ibom', '2650');
INSERT INTO `app_lga` VALUES ('62', 'Ika', '2650');
INSERT INTO `app_lga` VALUES ('63', 'Ikono', '2650');
INSERT INTO `app_lga` VALUES ('64', 'Ikot abasi', '2650');
INSERT INTO `app_lga` VALUES ('65', 'Ikot ekpene', '2650');
INSERT INTO `app_lga` VALUES ('66', 'Ini', '2650');
INSERT INTO `app_lga` VALUES ('67', 'Itu', '2650');
INSERT INTO `app_lga` VALUES ('68', 'Mbo', '2650');
INSERT INTO `app_lga` VALUES ('69', 'Mkpat enin', '2650');
INSERT INTO `app_lga` VALUES ('70', 'Nsit atai', '2650');
INSERT INTO `app_lga` VALUES ('71', 'Nsit ibom', '2650');
INSERT INTO `app_lga` VALUES ('72', 'Nsit ubium', '2650');
INSERT INTO `app_lga` VALUES ('73', 'Uruan', '2650');
INSERT INTO `app_lga` VALUES ('74', 'Urue-offong/oruko', '2650');
INSERT INTO `app_lga` VALUES ('75', 'Uyo', '2650');
INSERT INTO `app_lga` VALUES ('76', 'Aguata', '2651');
INSERT INTO `app_lga` VALUES ('77', 'Anambra east', '2651');
INSERT INTO `app_lga` VALUES ('78', 'Anambra west', '2651');
INSERT INTO `app_lga` VALUES ('79', 'Anaocha', '2651');
INSERT INTO `app_lga` VALUES ('80', 'Awka north', '2651');
INSERT INTO `app_lga` VALUES ('81', 'Awka south', '2651');
INSERT INTO `app_lga` VALUES ('82', 'Ayamelum', '2651');
INSERT INTO `app_lga` VALUES ('83', 'Dunukofia', '2651');
INSERT INTO `app_lga` VALUES ('84', 'Ekwusigo', '2651');
INSERT INTO `app_lga` VALUES ('85', 'Idemili north', '2651');
INSERT INTO `app_lga` VALUES ('86', 'Idemili south', '2651');
INSERT INTO `app_lga` VALUES ('87', 'Ihiala', '2651');
INSERT INTO `app_lga` VALUES ('88', 'Njikoka', '2651');
INSERT INTO `app_lga` VALUES ('89', 'Nnewi north', '2651');
INSERT INTO `app_lga` VALUES ('90', 'Obanliku', '2656');
INSERT INTO `app_lga` VALUES ('91', 'Obubra', '2656');
INSERT INTO `app_lga` VALUES ('92', 'Obudu', '2656');
INSERT INTO `app_lga` VALUES ('93', 'Odukpani', '2656');
INSERT INTO `app_lga` VALUES ('94', 'Ogoja', '2656');
INSERT INTO `app_lga` VALUES ('95', 'Yakurr', '2656');
INSERT INTO `app_lga` VALUES ('96', 'Yala', '2656');
INSERT INTO `app_lga` VALUES ('97', 'Aniocha north', '2657');
INSERT INTO `app_lga` VALUES ('98', 'Aniocha south', '2657');
INSERT INTO `app_lga` VALUES ('99', 'Bomadi', '2657');
INSERT INTO `app_lga` VALUES ('100', 'Burutu', '2657');
INSERT INTO `app_lga` VALUES ('101', 'Ethiope east', '2657');
INSERT INTO `app_lga` VALUES ('102', 'Ethiope west', '2657');
INSERT INTO `app_lga` VALUES ('103', 'Ika north', '2657');
INSERT INTO `app_lga` VALUES ('104', 'Ika south', '2657');
INSERT INTO `app_lga` VALUES ('105', 'Isoko north', '2657');
INSERT INTO `app_lga` VALUES ('106', 'Isoko south', '2657');
INSERT INTO `app_lga` VALUES ('107', 'Ndokwa east', '2657');
INSERT INTO `app_lga` VALUES ('108', 'Ndokwa west', '2657');
INSERT INTO `app_lga` VALUES ('109', 'Okpe', '2657');
INSERT INTO `app_lga` VALUES ('110', 'Oshimili north', '2657');
INSERT INTO `app_lga` VALUES ('111', 'Oshimili south', '2657');
INSERT INTO `app_lga` VALUES ('112', 'Patani', '2657');
INSERT INTO `app_lga` VALUES ('113', 'Sapele', '2657');
INSERT INTO `app_lga` VALUES ('114', 'Udu', '2657');
INSERT INTO `app_lga` VALUES ('115', 'Ughelli north', '2657');
INSERT INTO `app_lga` VALUES ('116', 'Ughelli south', '2657');
INSERT INTO `app_lga` VALUES ('117', 'Ukwuani', '2657');
INSERT INTO `app_lga` VALUES ('118', 'Uvwie', '2657');
INSERT INTO `app_lga` VALUES ('119', 'Warri north', '2657');
INSERT INTO `app_lga` VALUES ('120', 'Warri south', '2657');
INSERT INTO `app_lga` VALUES ('121', 'Warri south west', '2657');
INSERT INTO `app_lga` VALUES ('122', 'Abakaliki', '2658');
INSERT INTO `app_lga` VALUES ('123', 'Afikpo north', '2658');
INSERT INTO `app_lga` VALUES ('124', 'Afikpo south', '2658');
INSERT INTO `app_lga` VALUES ('125', 'Ebonyi', '2658');
INSERT INTO `app_lga` VALUES ('126', 'Ezza north', '2658');
INSERT INTO `app_lga` VALUES ('127', 'Ezza south', '2658');
INSERT INTO `app_lga` VALUES ('128', 'Ikwo', '2658');
INSERT INTO `app_lga` VALUES ('129', 'Ishielu', '2658');
INSERT INTO `app_lga` VALUES ('130', 'Ivo', '2658');
INSERT INTO `app_lga` VALUES ('131', 'Izzi', '2658');
INSERT INTO `app_lga` VALUES ('132', 'Ohaozara', '2658');
INSERT INTO `app_lga` VALUES ('133', 'Ohaukwu', '2658');
INSERT INTO `app_lga` VALUES ('134', 'Onicha', '2658');
INSERT INTO `app_lga` VALUES ('135', 'Akoko-edo', '2659');
INSERT INTO `app_lga` VALUES ('136', 'Egor', '2659');
INSERT INTO `app_lga` VALUES ('137', 'Esan central', '2659');
INSERT INTO `app_lga` VALUES ('138', 'Esan north east', '2659');
INSERT INTO `app_lga` VALUES ('139', 'Esan south east', '2659');
INSERT INTO `app_lga` VALUES ('140', 'Esan west', '2659');
INSERT INTO `app_lga` VALUES ('141', 'Etsako central', '2659');
INSERT INTO `app_lga` VALUES ('142', 'Etsako east', '2659');
INSERT INTO `app_lga` VALUES ('143', 'Etsako west', '2659');
INSERT INTO `app_lga` VALUES ('144', 'Igueben', '2659');
INSERT INTO `app_lga` VALUES ('145', 'Ikpoba-okha', '2659');
INSERT INTO `app_lga` VALUES ('146', 'Oredo', '2659');
INSERT INTO `app_lga` VALUES ('147', 'Orhionmwon', '2659');
INSERT INTO `app_lga` VALUES ('148', 'Ovia north east', '2659');
INSERT INTO `app_lga` VALUES ('149', 'Ovia south west', '2659');
INSERT INTO `app_lga` VALUES ('150', 'Owan east', '2659');
INSERT INTO `app_lga` VALUES ('151', 'Owan west', '2659');
INSERT INTO `app_lga` VALUES ('152', 'Uhunmwonde', '2659');
INSERT INTO `app_lga` VALUES ('153', 'ADK', '2660');
INSERT INTO `app_lga` VALUES ('154', 'DEA', '2660');
INSERT INTO `app_lga` VALUES ('155', 'EFY', '2660');
INSERT INTO `app_lga` VALUES ('156', 'MUE', '2660');
INSERT INTO `app_lga` VALUES ('157', 'LAW', '2660');
INSERT INTO `app_lga` VALUES ('158', 'AMK', '2660');
INSERT INTO `app_lga` VALUES ('159', 'EMR', '2660');
INSERT INTO `app_lga` VALUES ('160', 'DEK', '2660');
INSERT INTO `app_lga` VALUES ('161', 'JER', '2660');
INSERT INTO `app_lga` VALUES ('162', 'KER', '2660');
INSERT INTO `app_lga` VALUES ('163', 'KLE', '2660');
INSERT INTO `app_lga` VALUES ('164', 'YEK', '2660');
INSERT INTO `app_lga` VALUES ('165', 'GED', '2660');
INSERT INTO `app_lga` VALUES ('166', 'SSE', '2660');
INSERT INTO `app_lga` VALUES ('167', 'TUN', '2660');
INSERT INTO `app_lga` VALUES ('168', 'YEE', '2660');
INSERT INTO `app_lga` VALUES ('169', 'Aninri', '2661');
INSERT INTO `app_lga` VALUES ('170', 'Awgu', '2661');
INSERT INTO `app_lga` VALUES ('171', 'Enugu east', '2661');
INSERT INTO `app_lga` VALUES ('172', 'Enugu north', '2661');
INSERT INTO `app_lga` VALUES ('173', 'Enugu south', '2661');
INSERT INTO `app_lga` VALUES ('174', 'Ezeagu', '2661');
INSERT INTO `app_lga` VALUES ('175', 'Enugu', '2661');
INSERT INTO `app_lga` VALUES ('176', 'Igbo-etit', '2661');
INSERT INTO `app_lga` VALUES ('177', 'Igbo-eze north', '2661');
INSERT INTO `app_lga` VALUES ('178', 'Igho-eze south', '2661');
INSERT INTO `app_lga` VALUES ('179', 'Isi-uzo', '2661');
INSERT INTO `app_lga` VALUES ('180', 'Nkanu east', '2661');
INSERT INTO `app_lga` VALUES ('181', 'Nkanu west', '2661');
INSERT INTO `app_lga` VALUES ('182', 'Nnewi south', '2651');
INSERT INTO `app_lga` VALUES ('183', 'Ogbaru', '2651');
INSERT INTO `app_lga` VALUES ('184', 'Onitsha north', '2651');
INSERT INTO `app_lga` VALUES ('185', 'Onitsha south', '2651');
INSERT INTO `app_lga` VALUES ('186', 'Orumba north', '2651');
INSERT INTO `app_lga` VALUES ('187', 'Orumba south', '2651');
INSERT INTO `app_lga` VALUES ('188', 'Oyi', '2651');
INSERT INTO `app_lga` VALUES ('189', 'Alkaleri', '2652');
INSERT INTO `app_lga` VALUES ('190', 'Bauchi', '2652');
INSERT INTO `app_lga` VALUES ('191', 'Bogoro', '2652');
INSERT INTO `app_lga` VALUES ('192', 'Damban', '2652');
INSERT INTO `app_lga` VALUES ('193', 'Darazo', '2652');
INSERT INTO `app_lga` VALUES ('194', 'Dass', '2652');
INSERT INTO `app_lga` VALUES ('195', 'Gamawa', '2652');
INSERT INTO `app_lga` VALUES ('196', 'Ganjuwa', '2652');
INSERT INTO `app_lga` VALUES ('197', 'Giade', '2652');
INSERT INTO `app_lga` VALUES ('198', 'Itas/gadau', '2652');
INSERT INTO `app_lga` VALUES ('199', 'Jama\'are', '2652');
INSERT INTO `app_lga` VALUES ('200', 'Katagun', '2652');
INSERT INTO `app_lga` VALUES ('201', 'Gusau', '2683');
INSERT INTO `app_lga` VALUES ('202', 'Kirfi', '2652');
INSERT INTO `app_lga` VALUES ('203', 'Misau', '2652');
INSERT INTO `app_lga` VALUES ('204', 'Ningi', '2652');
INSERT INTO `app_lga` VALUES ('205', 'Shira', '2652');
INSERT INTO `app_lga` VALUES ('206', 'Tafawa-balewa', '2652');
INSERT INTO `app_lga` VALUES ('207', 'Toro', '2652');
INSERT INTO `app_lga` VALUES ('208', 'Warji', '2652');
INSERT INTO `app_lga` VALUES ('209', 'Zaki', '2652');
INSERT INTO `app_lga` VALUES ('210', 'Brass', '2653');
INSERT INTO `app_lga` VALUES ('211', 'Ekeremor', '2653');
INSERT INTO `app_lga` VALUES ('212', 'Kolokuma/opokuma', '2653');
INSERT INTO `app_lga` VALUES ('213', 'Nembe', '2653');
INSERT INTO `app_lga` VALUES ('214', 'Ogbia', '2653');
INSERT INTO `app_lga` VALUES ('215', 'Sagbama', '2653');
INSERT INTO `app_lga` VALUES ('216', 'Southern ijaw', '2653');
INSERT INTO `app_lga` VALUES ('217', 'Yenegoa', '2653');
INSERT INTO `app_lga` VALUES ('218', 'Ado', '2654');
INSERT INTO `app_lga` VALUES ('219', 'Agatu', '2654');
INSERT INTO `app_lga` VALUES ('220', 'Apa', '2654');
INSERT INTO `app_lga` VALUES ('221', 'Buruku', '2654');
INSERT INTO `app_lga` VALUES ('222', 'Gboko', '2654');
INSERT INTO `app_lga` VALUES ('223', 'Guma', '2654');
INSERT INTO `app_lga` VALUES ('224', 'Gwer east', '2654');
INSERT INTO `app_lga` VALUES ('225', 'Gwer west', '2654');
INSERT INTO `app_lga` VALUES ('226', 'Katsina-ala', '2654');
INSERT INTO `app_lga` VALUES ('227', 'Konshisha', '2654');
INSERT INTO `app_lga` VALUES ('228', 'Kwande', '2654');
INSERT INTO `app_lga` VALUES ('229', 'Logo', '2654');
INSERT INTO `app_lga` VALUES ('230', 'Makurdi', '2654');
INSERT INTO `app_lga` VALUES ('231', 'Obi', '2654');
INSERT INTO `app_lga` VALUES ('232', 'Ogbadibo', '2654');
INSERT INTO `app_lga` VALUES ('233', 'Oju', '2654');
INSERT INTO `app_lga` VALUES ('234', 'Okpokwu', '2654');
INSERT INTO `app_lga` VALUES ('235', 'Ohimini', '2654');
INSERT INTO `app_lga` VALUES ('236', 'Oturkpo', '2654');
INSERT INTO `app_lga` VALUES ('237', 'Tarka', '2654');
INSERT INTO `app_lga` VALUES ('238', 'Ukum', '2654');
INSERT INTO `app_lga` VALUES ('239', 'Ushongo', '2654');
INSERT INTO `app_lga` VALUES ('240', 'Vandeikya', '2654');
INSERT INTO `app_lga` VALUES ('241', 'Abadam', '2655');
INSERT INTO `app_lga` VALUES ('242', 'Askira/uba', '2655');
INSERT INTO `app_lga` VALUES ('243', 'Bama', '2655');
INSERT INTO `app_lga` VALUES ('244', 'Bayo', '2655');
INSERT INTO `app_lga` VALUES ('245', 'Biu', '2655');
INSERT INTO `app_lga` VALUES ('246', 'Chibok', '2655');
INSERT INTO `app_lga` VALUES ('247', 'Damboa', '2655');
INSERT INTO `app_lga` VALUES ('248', 'Dikwa', '2655');
INSERT INTO `app_lga` VALUES ('249', 'Gubio', '2655');
INSERT INTO `app_lga` VALUES ('250', 'Guzamala', '2655');
INSERT INTO `app_lga` VALUES ('251', 'Gwoza', '2655');
INSERT INTO `app_lga` VALUES ('252', 'Hawul', '2655');
INSERT INTO `app_lga` VALUES ('253', 'Jere', '2655');
INSERT INTO `app_lga` VALUES ('254', 'Kaga', '2655');
INSERT INTO `app_lga` VALUES ('255', 'Kala/balge', '2655');
INSERT INTO `app_lga` VALUES ('256', 'Konduga', '2655');
INSERT INTO `app_lga` VALUES ('257', 'Kukawa', '2655');
INSERT INTO `app_lga` VALUES ('258', 'Kwaya kusar', '2655');
INSERT INTO `app_lga` VALUES ('259', 'Mafa', '2655');
INSERT INTO `app_lga` VALUES ('260', 'Magumeri', '2655');
INSERT INTO `app_lga` VALUES ('261', 'Maiduguri', '2655');
INSERT INTO `app_lga` VALUES ('262', 'Marte', '2655');
INSERT INTO `app_lga` VALUES ('263', 'Mobbar', '2655');
INSERT INTO `app_lga` VALUES ('264', 'Monguno', '2655');
INSERT INTO `app_lga` VALUES ('265', 'Ngala', '2655');
INSERT INTO `app_lga` VALUES ('266', 'Nganzai', '2655');
INSERT INTO `app_lga` VALUES ('267', 'Shani', '2655');
INSERT INTO `app_lga` VALUES ('268', 'Abi', '2656');
INSERT INTO `app_lga` VALUES ('269', 'Akamkpa', '2656');
INSERT INTO `app_lga` VALUES ('270', 'Akpabuyo', '2656');
INSERT INTO `app_lga` VALUES ('271', 'Bakassi', '2656');
INSERT INTO `app_lga` VALUES ('272', 'Bekwara', '2656');
INSERT INTO `app_lga` VALUES ('273', 'Biase', '2656');
INSERT INTO `app_lga` VALUES ('274', 'Boki', '2656');
INSERT INTO `app_lga` VALUES ('275', 'Calabar-municipal', '2656');
INSERT INTO `app_lga` VALUES ('276', 'Calabar south', '2656');
INSERT INTO `app_lga` VALUES ('277', 'Etung', '2656');
INSERT INTO `app_lga` VALUES ('278', 'Ikom', '2656');
INSERT INTO `app_lga` VALUES ('279', 'Nsukka', '2661');
INSERT INTO `app_lga` VALUES ('280', 'Oji-river', '2661');
INSERT INTO `app_lga` VALUES ('281', 'Udenu', '2661');
INSERT INTO `app_lga` VALUES ('282', 'Udi', '2661');
INSERT INTO `app_lga` VALUES ('283', 'Uzo-uwani', '2661');
INSERT INTO `app_lga` VALUES ('284', 'Akko', '2662');
INSERT INTO `app_lga` VALUES ('285', 'Balanga', '2662');
INSERT INTO `app_lga` VALUES ('286', 'Billiri', '2662');
INSERT INTO `app_lga` VALUES ('287', 'Dukku', '2662');
INSERT INTO `app_lga` VALUES ('288', 'Funakaye', '2662');
INSERT INTO `app_lga` VALUES ('289', 'Gombe', '2662');
INSERT INTO `app_lga` VALUES ('290', 'Kaltungo', '2662');
INSERT INTO `app_lga` VALUES ('291', 'Kwami', '2662');
INSERT INTO `app_lga` VALUES ('292', 'Nafada', '2662');
INSERT INTO `app_lga` VALUES ('293', 'Shomgom', '2662');
INSERT INTO `app_lga` VALUES ('294', 'Yamaltu/deba', '2662');
INSERT INTO `app_lga` VALUES ('295', 'Ahiazu-mbaise', '2663');
INSERT INTO `app_lga` VALUES ('296', 'Ehime-mbano', '2663');
INSERT INTO `app_lga` VALUES ('297', 'Ezinihitte', '2663');
INSERT INTO `app_lga` VALUES ('298', 'Ideato north', '2663');
INSERT INTO `app_lga` VALUES ('299', 'Ideato south', '2663');
INSERT INTO `app_lga` VALUES ('300', 'Ihitte-uboma', '2663');
INSERT INTO `app_lga` VALUES ('301', 'Ikeduru', '2663');
INSERT INTO `app_lga` VALUES ('302', 'Isiala mbano', '2663');
INSERT INTO `app_lga` VALUES ('303', 'Isu', '2663');
INSERT INTO `app_lga` VALUES ('304', 'Mbaitoli', '2663');
INSERT INTO `app_lga` VALUES ('305', 'Ngor-okpala', '2663');
INSERT INTO `app_lga` VALUES ('306', 'Njaba', '2663');
INSERT INTO `app_lga` VALUES ('307', 'Nwangele', '2663');
INSERT INTO `app_lga` VALUES ('308', 'Nkwerre', '2663');
INSERT INTO `app_lga` VALUES ('309', 'Obowo', '2663');
INSERT INTO `app_lga` VALUES ('310', 'Oguta', '2663');
INSERT INTO `app_lga` VALUES ('311', 'Ohaji/egbema', '2663');
INSERT INTO `app_lga` VALUES ('312', 'Okigwe', '2663');
INSERT INTO `app_lga` VALUES ('313', 'Orlu', '2663');
INSERT INTO `app_lga` VALUES ('314', 'Orsu', '2663');
INSERT INTO `app_lga` VALUES ('315', 'Oru east', '2663');
INSERT INTO `app_lga` VALUES ('316', 'Oru west', '2663');
INSERT INTO `app_lga` VALUES ('317', 'Owerri muni.', '2663');
INSERT INTO `app_lga` VALUES ('318', 'Owerri north', '2663');
INSERT INTO `app_lga` VALUES ('319', 'Owerri west', '2663');
INSERT INTO `app_lga` VALUES ('320', 'Onuimo', '2663');
INSERT INTO `app_lga` VALUES ('321', 'Auyo', '2664');
INSERT INTO `app_lga` VALUES ('322', 'Babura', '2664');
INSERT INTO `app_lga` VALUES ('323', 'Birnin kudu', '2664');
INSERT INTO `app_lga` VALUES ('324', 'Biriniwa', '2664');
INSERT INTO `app_lga` VALUES ('325', 'Buji', '2664');
INSERT INTO `app_lga` VALUES ('326', 'Dutse', '2664');
INSERT INTO `app_lga` VALUES ('327', 'Gagarawa', '2664');
INSERT INTO `app_lga` VALUES ('328', 'Garki', '2664');
INSERT INTO `app_lga` VALUES ('329', 'Gumel', '2664');
INSERT INTO `app_lga` VALUES ('330', 'Guri', '2664');
INSERT INTO `app_lga` VALUES ('331', 'Gwaram', '2664');
INSERT INTO `app_lga` VALUES ('332', 'Gwiwa', '2664');
INSERT INTO `app_lga` VALUES ('333', 'Hadejia', '2664');
INSERT INTO `app_lga` VALUES ('334', 'Jahun', '2664');
INSERT INTO `app_lga` VALUES ('335', 'Kafin', '2664');
INSERT INTO `app_lga` VALUES ('336', 'Hausa', '2664');
INSERT INTO `app_lga` VALUES ('337', 'Kaugama', '2664');
INSERT INTO `app_lga` VALUES ('338', 'Kazaure', '2664');
INSERT INTO `app_lga` VALUES ('339', 'Kiri kasamma', '2664');
INSERT INTO `app_lga` VALUES ('340', 'Kiyawa', '2664');
INSERT INTO `app_lga` VALUES ('341', 'Maigatari', '2664');
INSERT INTO `app_lga` VALUES ('342', 'Malam madori', '2664');
INSERT INTO `app_lga` VALUES ('343', 'Miga', '2664');
INSERT INTO `app_lga` VALUES ('344', 'Ringim', '2664');
INSERT INTO `app_lga` VALUES ('345', 'Roni', '2664');
INSERT INTO `app_lga` VALUES ('346', 'Sule-tankarkar', '2664');
INSERT INTO `app_lga` VALUES ('347', 'Taura', '2664');
INSERT INTO `app_lga` VALUES ('348', 'Yankwashi', '2664');
INSERT INTO `app_lga` VALUES ('349', 'Birnin-gwari', '2665');
INSERT INTO `app_lga` VALUES ('350', 'Chikun', '2665');
INSERT INTO `app_lga` VALUES ('351', 'Giwa', '2665');
INSERT INTO `app_lga` VALUES ('352', 'Igabi', '2665');
INSERT INTO `app_lga` VALUES ('353', 'Ikara', '2665');
INSERT INTO `app_lga` VALUES ('354', 'Jaba', '2665');
INSERT INTO `app_lga` VALUES ('355', 'Jema\'a', '2665');
INSERT INTO `app_lga` VALUES ('356', 'Kachia', '2665');
INSERT INTO `app_lga` VALUES ('357', 'Kaduna north', '2665');
INSERT INTO `app_lga` VALUES ('358', 'Kaduna south', '2665');
INSERT INTO `app_lga` VALUES ('359', 'Kagarko', '2665');
INSERT INTO `app_lga` VALUES ('360', 'Kajuru', '2665');
INSERT INTO `app_lga` VALUES ('361', 'Kaura', '2665');
INSERT INTO `app_lga` VALUES ('362', 'Kubau', '2665');
INSERT INTO `app_lga` VALUES ('363', 'Kudan', '2665');
INSERT INTO `app_lga` VALUES ('364', 'Lere', '2665');
INSERT INTO `app_lga` VALUES ('365', 'Makarfi', '2665');
INSERT INTO `app_lga` VALUES ('366', 'Sabon-gari', '2665');
INSERT INTO `app_lga` VALUES ('367', 'Sanga', '2665');
INSERT INTO `app_lga` VALUES ('368', 'Soba', '2665');
INSERT INTO `app_lga` VALUES ('369', 'Zangon-kataf', '2665');
INSERT INTO `app_lga` VALUES ('370', 'Zaria', '2665');
INSERT INTO `app_lga` VALUES ('371', 'Ajingi', '2666');
INSERT INTO `app_lga` VALUES ('372', 'Albasu', '2666');
INSERT INTO `app_lga` VALUES ('373', 'Bagwai', '2666');
INSERT INTO `app_lga` VALUES ('374', 'Bebeji', '2666');
INSERT INTO `app_lga` VALUES ('375', 'Bichi', '2666');
INSERT INTO `app_lga` VALUES ('376', 'Bunkure', '2666');
INSERT INTO `app_lga` VALUES ('377', 'Dala', '2666');
INSERT INTO `app_lga` VALUES ('378', 'Dambatta', '2666');
INSERT INTO `app_lga` VALUES ('379', 'Dawakin kudu', '2666');
INSERT INTO `app_lga` VALUES ('380', 'Dawakin tofa', '2666');
INSERT INTO `app_lga` VALUES ('381', 'Doguwa', '2666');
INSERT INTO `app_lga` VALUES ('382', 'Fagge', '2666');
INSERT INTO `app_lga` VALUES ('383', 'Gabasawa', '2666');
INSERT INTO `app_lga` VALUES ('384', 'Garko', '2666');
INSERT INTO `app_lga` VALUES ('385', 'Garum mallarn', '2666');
INSERT INTO `app_lga` VALUES ('386', 'Gaya', '2666');
INSERT INTO `app_lga` VALUES ('387', 'Gezawa', '2666');
INSERT INTO `app_lga` VALUES ('388', 'Gwale', '2666');
INSERT INTO `app_lga` VALUES ('389', 'Gwarzo', '2666');
INSERT INTO `app_lga` VALUES ('390', 'Kabo', '2666');
INSERT INTO `app_lga` VALUES ('391', 'Kano municipal', '2666');
INSERT INTO `app_lga` VALUES ('392', 'Karaye', '2666');
INSERT INTO `app_lga` VALUES ('393', 'Kibiya', '2666');
INSERT INTO `app_lga` VALUES ('394', 'Kiru', '2666');
INSERT INTO `app_lga` VALUES ('395', 'Kumbotso', '2666');
INSERT INTO `app_lga` VALUES ('396', 'Kunchi', '2666');
INSERT INTO `app_lga` VALUES ('397', 'Kura', '2666');
INSERT INTO `app_lga` VALUES ('398', 'Madobi', '2666');
INSERT INTO `app_lga` VALUES ('399', 'Makoda', '2666');
INSERT INTO `app_lga` VALUES ('400', 'Minjibir', '2666');
INSERT INTO `app_lga` VALUES ('401', 'Nasarawa', '2666');
INSERT INTO `app_lga` VALUES ('402', 'Rano', '2666');
INSERT INTO `app_lga` VALUES ('403', 'Rimin gado', '2666');
INSERT INTO `app_lga` VALUES ('404', 'Rogo', '2666');
INSERT INTO `app_lga` VALUES ('405', 'Shanono', '2666');
INSERT INTO `app_lga` VALUES ('406', 'Sumaila', '2666');
INSERT INTO `app_lga` VALUES ('407', 'Takai', '2666');
INSERT INTO `app_lga` VALUES ('408', 'Tarauni', '2666');
INSERT INTO `app_lga` VALUES ('409', 'Tofa', '2666');
INSERT INTO `app_lga` VALUES ('410', 'Tsanyawa', '2666');
INSERT INTO `app_lga` VALUES ('411', 'Tudun wada', '2666');
INSERT INTO `app_lga` VALUES ('412', 'Ungogo', '2666');
INSERT INTO `app_lga` VALUES ('413', 'Warawa', '2666');
INSERT INTO `app_lga` VALUES ('414', 'Wudil', '2666');
INSERT INTO `app_lga` VALUES ('415', 'Bakori', '2667');
INSERT INTO `app_lga` VALUES ('416', 'Batagarawa', '2667');
INSERT INTO `app_lga` VALUES ('417', 'Batsari', '2667');
INSERT INTO `app_lga` VALUES ('418', 'Baure', '2667');
INSERT INTO `app_lga` VALUES ('419', 'Bindawa', '2667');
INSERT INTO `app_lga` VALUES ('420', 'Charanchi', '2667');
INSERT INTO `app_lga` VALUES ('421', 'Dandume', '2667');
INSERT INTO `app_lga` VALUES ('422', 'Danja', '2667');
INSERT INTO `app_lga` VALUES ('423', 'Dan musa', '2667');
INSERT INTO `app_lga` VALUES ('424', 'Daura', '2667');
INSERT INTO `app_lga` VALUES ('425', 'Dutsi', '2667');
INSERT INTO `app_lga` VALUES ('426', 'Dutsin-ma', '2667');
INSERT INTO `app_lga` VALUES ('427', 'Faskari', '2667');
INSERT INTO `app_lga` VALUES ('428', 'Funtua', '2667');
INSERT INTO `app_lga` VALUES ('429', 'Ingawa', '2667');
INSERT INTO `app_lga` VALUES ('430', 'Jibia', '2667');
INSERT INTO `app_lga` VALUES ('431', 'Kafur', '2667');
INSERT INTO `app_lga` VALUES ('432', 'Kaita', '2667');
INSERT INTO `app_lga` VALUES ('433', 'Kankara', '2667');
INSERT INTO `app_lga` VALUES ('434', 'Kankia', '2667');
INSERT INTO `app_lga` VALUES ('435', 'Katsina', '2667');
INSERT INTO `app_lga` VALUES ('436', 'Kurfi', '2667');
INSERT INTO `app_lga` VALUES ('437', 'Kusada', '2667');
INSERT INTO `app_lga` VALUES ('438', 'Mai\'adua', '2667');
INSERT INTO `app_lga` VALUES ('439', 'Malumfashi', '2667');
INSERT INTO `app_lga` VALUES ('440', 'Mani', '2667');
INSERT INTO `app_lga` VALUES ('441', 'Mashi', '2667');
INSERT INTO `app_lga` VALUES ('442', 'Matazu', '2667');
INSERT INTO `app_lga` VALUES ('443', 'Musawa', '2667');
INSERT INTO `app_lga` VALUES ('444', 'Rimi', '2667');
INSERT INTO `app_lga` VALUES ('445', 'Sabuwa', '2667');
INSERT INTO `app_lga` VALUES ('446', 'Safana', '2667');
INSERT INTO `app_lga` VALUES ('447', 'Sandamu', '2667');
INSERT INTO `app_lga` VALUES ('448', 'Zongo', '2667');
INSERT INTO `app_lga` VALUES ('449', 'Aleiro', '2668');
INSERT INTO `app_lga` VALUES ('450', 'Arewa-dandi', '2668');
INSERT INTO `app_lga` VALUES ('451', 'Argungu', '2668');
INSERT INTO `app_lga` VALUES ('452', 'Augie', '2668');
INSERT INTO `app_lga` VALUES ('453', 'Bagudo', '2668');
INSERT INTO `app_lga` VALUES ('454', 'Birnin kebbi', '2668');
INSERT INTO `app_lga` VALUES ('455', 'Bunza', '2668');
INSERT INTO `app_lga` VALUES ('456', 'Dandi', '2668');
INSERT INTO `app_lga` VALUES ('457', 'Fakai', '2668');
INSERT INTO `app_lga` VALUES ('458', 'Gwandu', '2668');
INSERT INTO `app_lga` VALUES ('459', 'Jega', '2668');
INSERT INTO `app_lga` VALUES ('460', 'Kalgo', '2668');
INSERT INTO `app_lga` VALUES ('461', 'Koko/besse', '2668');
INSERT INTO `app_lga` VALUES ('462', 'Maiyama', '2668');
INSERT INTO `app_lga` VALUES ('463', 'Ngaski', '2668');
INSERT INTO `app_lga` VALUES ('464', 'Sakaba', '2668');
INSERT INTO `app_lga` VALUES ('465', 'Shanga', '2668');
INSERT INTO `app_lga` VALUES ('466', 'Suru', '2668');
INSERT INTO `app_lga` VALUES ('467', 'Wasagu/danko', '2668');
INSERT INTO `app_lga` VALUES ('468', 'Yauri', '2668');
INSERT INTO `app_lga` VALUES ('469', 'Zuru', '2668');
INSERT INTO `app_lga` VALUES ('470', 'Adavi', '2669');
INSERT INTO `app_lga` VALUES ('471', 'Ajaojuta', '2669');
INSERT INTO `app_lga` VALUES ('472', 'Ankpa', '2669');
INSERT INTO `app_lga` VALUES ('473', 'Bassa', '2669');
INSERT INTO `app_lga` VALUES ('474', 'Dekina', '2669');
INSERT INTO `app_lga` VALUES ('475', 'Ibaji', '2669');
INSERT INTO `app_lga` VALUES ('476', 'Igalamela-odolu', '2669');
INSERT INTO `app_lga` VALUES ('477', 'Ijumu', '2669');
INSERT INTO `app_lga` VALUES ('478', 'Ijumu', '2669');
INSERT INTO `app_lga` VALUES ('479', 'Kabba/bunu', '2669');
INSERT INTO `app_lga` VALUES ('480', 'Kogi', '2669');
INSERT INTO `app_lga` VALUES ('481', 'Lokoja', '2669');
INSERT INTO `app_lga` VALUES ('482', 'Mopa-muro', '2669');
INSERT INTO `app_lga` VALUES ('483', 'Ofu', '2669');
INSERT INTO `app_lga` VALUES ('484', 'Ogori/megongo', '2669');
INSERT INTO `app_lga` VALUES ('485', 'Okehi', '2669');
INSERT INTO `app_lga` VALUES ('486', 'Olamabolo', '2669');
INSERT INTO `app_lga` VALUES ('487', 'Omala', '2669');
INSERT INTO `app_lga` VALUES ('488', 'Yagba east', '2669');
INSERT INTO `app_lga` VALUES ('489', 'Yagba west', '2669');
INSERT INTO `app_lga` VALUES ('490', 'Asa', '2670');
INSERT INTO `app_lga` VALUES ('491', 'Baruten', '2670');
INSERT INTO `app_lga` VALUES ('492', 'Edu', '2670');
INSERT INTO `app_lga` VALUES ('493', 'Ekiti', '2670');
INSERT INTO `app_lga` VALUES ('494', 'Ifelodun', '2670');
INSERT INTO `app_lga` VALUES ('495', 'Ilorin south', '2670');
INSERT INTO `app_lga` VALUES ('496', 'Ilorin west', '2670');
INSERT INTO `app_lga` VALUES ('497', 'Irepodun', '2670');
INSERT INTO `app_lga` VALUES ('498', 'Isin', '2670');
INSERT INTO `app_lga` VALUES ('499', 'Kaiama', '2670');
INSERT INTO `app_lga` VALUES ('500', 'Moro', '2670');
INSERT INTO `app_lga` VALUES ('501', 'Offa', '2670');
INSERT INTO `app_lga` VALUES ('502', 'Oke-ero', '2670');
INSERT INTO `app_lga` VALUES ('503', 'Oyun', '2670');
INSERT INTO `app_lga` VALUES ('504', 'Pategi', '2670');
INSERT INTO `app_lga` VALUES ('505', 'Agege', '2671');
INSERT INTO `app_lga` VALUES ('506', 'Ajeromi-ifelodun', '2671');
INSERT INTO `app_lga` VALUES ('507', 'Alimosho', '2671');
INSERT INTO `app_lga` VALUES ('508', 'Amuwo-odofin', '2671');
INSERT INTO `app_lga` VALUES ('509', 'Apapa', '2671');
INSERT INTO `app_lga` VALUES ('510', 'Badagry', '2671');
INSERT INTO `app_lga` VALUES ('511', 'Epe', '2671');
INSERT INTO `app_lga` VALUES ('512', 'Eti-osa', '2671');
INSERT INTO `app_lga` VALUES ('513', 'Ibeju/lekki', '2671');
INSERT INTO `app_lga` VALUES ('514', 'Ifako-ijaye', '2671');
INSERT INTO `app_lga` VALUES ('515', 'Ikeja', '2671');
INSERT INTO `app_lga` VALUES ('516', 'Ikorodu', '2671');
INSERT INTO `app_lga` VALUES ('517', 'Kosofe', '2671');
INSERT INTO `app_lga` VALUES ('518', 'Lagos island', '2671');
INSERT INTO `app_lga` VALUES ('519', 'Lagos mainland', '2671');
INSERT INTO `app_lga` VALUES ('520', 'Mushin', '2671');
INSERT INTO `app_lga` VALUES ('521', 'Ojo', '2671');
INSERT INTO `app_lga` VALUES ('522', 'Oshodi-isolo', '2671');
INSERT INTO `app_lga` VALUES ('523', 'Shomolu', '2671');
INSERT INTO `app_lga` VALUES ('524', 'Surulere', '2671');
INSERT INTO `app_lga` VALUES ('525', 'Akwanga', '2672');
INSERT INTO `app_lga` VALUES ('526', 'Awe', '2672');
INSERT INTO `app_lga` VALUES ('527', 'Doma', '2672');
INSERT INTO `app_lga` VALUES ('528', 'Karu', '2672');
INSERT INTO `app_lga` VALUES ('529', 'Keana', '2672');
INSERT INTO `app_lga` VALUES ('530', 'Keffi', '2672');
INSERT INTO `app_lga` VALUES ('531', 'Kokona', '2672');
INSERT INTO `app_lga` VALUES ('532', 'Lafia', '2672');
INSERT INTO `app_lga` VALUES ('533', 'Nasarawa', '2672');
INSERT INTO `app_lga` VALUES ('534', 'Nasarawa-eggon', '2672');
INSERT INTO `app_lga` VALUES ('535', 'Obi', '2672');
INSERT INTO `app_lga` VALUES ('536', 'Toto', '2672');
INSERT INTO `app_lga` VALUES ('537', 'Wamba', '2672');
INSERT INTO `app_lga` VALUES ('538', 'Agaie', '2673');
INSERT INTO `app_lga` VALUES ('539', 'Agwara', '2673');
INSERT INTO `app_lga` VALUES ('540', 'Bida', '2673');
INSERT INTO `app_lga` VALUES ('541', 'Borgu', '2673');
INSERT INTO `app_lga` VALUES ('542', 'Bosso', '2673');
INSERT INTO `app_lga` VALUES ('543', 'Chanchaga', '2673');
INSERT INTO `app_lga` VALUES ('544', 'Edati', '2673');
INSERT INTO `app_lga` VALUES ('545', 'Gbako', '2673');
INSERT INTO `app_lga` VALUES ('546', 'Gurara', '2673');
INSERT INTO `app_lga` VALUES ('547', 'Katcha', '2673');
INSERT INTO `app_lga` VALUES ('548', 'Kontagora', '2673');
INSERT INTO `app_lga` VALUES ('549', 'Lapai', '2673');
INSERT INTO `app_lga` VALUES ('550', 'Lavun', '2673');
INSERT INTO `app_lga` VALUES ('551', 'Magama', '2673');
INSERT INTO `app_lga` VALUES ('552', 'Mariga', '2673');
INSERT INTO `app_lga` VALUES ('553', 'Mashegu', '2673');
INSERT INTO `app_lga` VALUES ('554', 'Mokwa', '2673');
INSERT INTO `app_lga` VALUES ('555', 'Muya', '2673');
INSERT INTO `app_lga` VALUES ('556', 'Paikoro', '2673');
INSERT INTO `app_lga` VALUES ('557', 'Rafi', '2673');
INSERT INTO `app_lga` VALUES ('558', 'Rajau', '2673');
INSERT INTO `app_lga` VALUES ('559', 'Shiroro', '2673');
INSERT INTO `app_lga` VALUES ('560', 'Suleja', '2673');
INSERT INTO `app_lga` VALUES ('561', 'Tafa', '2673');
INSERT INTO `app_lga` VALUES ('562', 'Wushishi', '2673');
INSERT INTO `app_lga` VALUES ('563', 'Abeokuta north', '2674');
INSERT INTO `app_lga` VALUES ('564', 'Abeokuta south', '2674');
INSERT INTO `app_lga` VALUES ('565', 'Ado-odo/ota', '2674');
INSERT INTO `app_lga` VALUES ('566', 'Egbado north', '2674');
INSERT INTO `app_lga` VALUES ('567', 'Egbado south', '2674');
INSERT INTO `app_lga` VALUES ('568', 'Ekwekoro', '2674');
INSERT INTO `app_lga` VALUES ('569', 'Ifo', '2674');
INSERT INTO `app_lga` VALUES ('570', 'Ijebu east', '2674');
INSERT INTO `app_lga` VALUES ('571', 'Ijebu north', '2674');
INSERT INTO `app_lga` VALUES ('572', 'Ijebu north east', '2674');
INSERT INTO `app_lga` VALUES ('573', 'Ijebu-ode', '2674');
INSERT INTO `app_lga` VALUES ('574', 'Ikenne', '2674');
INSERT INTO `app_lga` VALUES ('575', 'Imeko-afon', '2674');
INSERT INTO `app_lga` VALUES ('576', 'Ipokia', '2674');
INSERT INTO `app_lga` VALUES ('577', 'Obafemi-owode', '2674');
INSERT INTO `app_lga` VALUES ('578', 'Ogun waterside', '2674');
INSERT INTO `app_lga` VALUES ('579', 'Odeda', '2674');
INSERT INTO `app_lga` VALUES ('580', 'Odogbolu', '2674');
INSERT INTO `app_lga` VALUES ('581', 'Remo north', '2674');
INSERT INTO `app_lga` VALUES ('582', 'Shagamu', '2674');
INSERT INTO `app_lga` VALUES ('583', 'Akoko north east', '2675');
INSERT INTO `app_lga` VALUES ('584', 'Akoko north west', '2675');
INSERT INTO `app_lga` VALUES ('585', 'Akoko south east', '2675');
INSERT INTO `app_lga` VALUES ('586', 'Akoko south west', '2675');
INSERT INTO `app_lga` VALUES ('587', 'Akure north', '2675');
INSERT INTO `app_lga` VALUES ('588', 'Akuresouth', '2675');
INSERT INTO `app_lga` VALUES ('589', 'Ese-odo', '2675');
INSERT INTO `app_lga` VALUES ('590', 'Idanre', '2675');
INSERT INTO `app_lga` VALUES ('591', 'Ifedore', '2675');
INSERT INTO `app_lga` VALUES ('592', 'Ilaje', '2675');
INSERT INTO `app_lga` VALUES ('593', 'Ile-oluji-okeigbo', '2675');
INSERT INTO `app_lga` VALUES ('594', 'Irele', '2675');
INSERT INTO `app_lga` VALUES ('595', 'Odigbo', '2675');
INSERT INTO `app_lga` VALUES ('596', 'Okitipupa', '2675');
INSERT INTO `app_lga` VALUES ('597', 'Ondo east', '2675');
INSERT INTO `app_lga` VALUES ('598', 'Ondo west', '2675');
INSERT INTO `app_lga` VALUES ('599', 'Ose-owo', '2675');
INSERT INTO `app_lga` VALUES ('600', 'Aiyedade', '2676');
INSERT INTO `app_lga` VALUES ('601', 'Aiyedire', '2676');
INSERT INTO `app_lga` VALUES ('602', 'Atakumosa east', '2676');
INSERT INTO `app_lga` VALUES ('603', 'Atakumose-west', '2676');
INSERT INTO `app_lga` VALUES ('604', 'Boluwaduro', '2676');
INSERT INTO `app_lga` VALUES ('605', 'Boripe', '2676');
INSERT INTO `app_lga` VALUES ('606', 'Ede north', '2676');
INSERT INTO `app_lga` VALUES ('607', 'Ede south', '2676');
INSERT INTO `app_lga` VALUES ('608', 'Egbedore', '2676');
INSERT INTO `app_lga` VALUES ('609', 'Ejigbo', '2676');
INSERT INTO `app_lga` VALUES ('610', 'Ife central', '2676');
INSERT INTO `app_lga` VALUES ('611', 'Ife east', '2676');
INSERT INTO `app_lga` VALUES ('612', 'Ife north', '2676');
INSERT INTO `app_lga` VALUES ('613', 'Ife south', '2676');
INSERT INTO `app_lga` VALUES ('614', 'Ifedayo', '2676');
INSERT INTO `app_lga` VALUES ('615', 'Ifelodun', '2676');
INSERT INTO `app_lga` VALUES ('616', 'Ila', '2676');
INSERT INTO `app_lga` VALUES ('617', 'Ilasha east', '2676');
INSERT INTO `app_lga` VALUES ('618', 'Ilesha west', '2676');
INSERT INTO `app_lga` VALUES ('619', 'Irepodun', '2676');
INSERT INTO `app_lga` VALUES ('620', 'Irewole', '2676');
INSERT INTO `app_lga` VALUES ('621', 'Isokan', '2676');
INSERT INTO `app_lga` VALUES ('622', 'Iwo', '2676');
INSERT INTO `app_lga` VALUES ('623', 'Obokun', '2676');
INSERT INTO `app_lga` VALUES ('624', 'Odo-otin', '2676');
INSERT INTO `app_lga` VALUES ('625', 'Ola-oluwa', '2676');
INSERT INTO `app_lga` VALUES ('626', 'Olorunda', '2676');
INSERT INTO `app_lga` VALUES ('627', 'Oriade', '2676');
INSERT INTO `app_lga` VALUES ('628', 'Orolu', '2676');
INSERT INTO `app_lga` VALUES ('629', 'Osogbo', '2676');
INSERT INTO `app_lga` VALUES ('630', 'Afijio', '2677');
INSERT INTO `app_lga` VALUES ('631', 'Akinyele', '2677');
INSERT INTO `app_lga` VALUES ('632', 'Atiba', '2677');
INSERT INTO `app_lga` VALUES ('633', 'Atigbo', '2677');
INSERT INTO `app_lga` VALUES ('634', 'Egbeda', '2677');
INSERT INTO `app_lga` VALUES ('635', 'Ibadan central', '2677');
INSERT INTO `app_lga` VALUES ('636', 'Ibadan north', '2677');
INSERT INTO `app_lga` VALUES ('637', 'Ibadan north west', '2677');
INSERT INTO `app_lga` VALUES ('638', 'Ibadan south west', '2677');
INSERT INTO `app_lga` VALUES ('639', 'Ibadan south east', '2677');
INSERT INTO `app_lga` VALUES ('640', 'Ibarapa central', '2677');
INSERT INTO `app_lga` VALUES ('641', 'Ibarapa east', '2677');
INSERT INTO `app_lga` VALUES ('642', 'Ibarapa north', '2677');
INSERT INTO `app_lga` VALUES ('643', 'Ido', '2677');
INSERT INTO `app_lga` VALUES ('644', 'Irepo', '2677');
INSERT INTO `app_lga` VALUES ('645', 'Iseyin', '2677');
INSERT INTO `app_lga` VALUES ('646', 'Itesiwaju', '2677');
INSERT INTO `app_lga` VALUES ('647', 'Iwajowa', '2677');
INSERT INTO `app_lga` VALUES ('648', 'Kajola', '2677');
INSERT INTO `app_lga` VALUES ('649', 'Lagelu', '2677');
INSERT INTO `app_lga` VALUES ('650', 'Ogbomoso north', '2677');
INSERT INTO `app_lga` VALUES ('651', 'Ogbomoso south', '2677');
INSERT INTO `app_lga` VALUES ('652', 'Ogo oluwa', '2677');
INSERT INTO `app_lga` VALUES ('653', 'Olorunsogo', '2677');
INSERT INTO `app_lga` VALUES ('654', 'Oluyole', '2677');
INSERT INTO `app_lga` VALUES ('655', 'Ona-ara', '2677');
INSERT INTO `app_lga` VALUES ('656', 'Orelope', '2677');
INSERT INTO `app_lga` VALUES ('657', 'Ori ire', '2677');
INSERT INTO `app_lga` VALUES ('658', 'Oyo east', '2677');
INSERT INTO `app_lga` VALUES ('659', 'Oyo west', '2677');
INSERT INTO `app_lga` VALUES ('660', 'Saki east', '2677');
INSERT INTO `app_lga` VALUES ('661', 'Saki west', '2677');
INSERT INTO `app_lga` VALUES ('662', 'Surelere', '2677');
INSERT INTO `app_lga` VALUES ('663', 'Barikin ladi', '2678');
INSERT INTO `app_lga` VALUES ('664', 'Bassa', '2678');
INSERT INTO `app_lga` VALUES ('665', 'Bokkos', '2678');
INSERT INTO `app_lga` VALUES ('666', 'Jos east', '2678');
INSERT INTO `app_lga` VALUES ('667', 'Jos north', '2678');
INSERT INTO `app_lga` VALUES ('668', 'Jos south', '2678');
INSERT INTO `app_lga` VALUES ('669', 'Kanam', '2678');
INSERT INTO `app_lga` VALUES ('670', 'Kanke', '2678');
INSERT INTO `app_lga` VALUES ('671', 'Langtang north', '2678');
INSERT INTO `app_lga` VALUES ('672', 'Langtang south', '2678');
INSERT INTO `app_lga` VALUES ('673', 'Mangu', '2678');
INSERT INTO `app_lga` VALUES ('674', 'Mikang', '2678');
INSERT INTO `app_lga` VALUES ('675', 'Pankshin', '2678');
INSERT INTO `app_lga` VALUES ('676', 'Qua\'an pan', '2678');
INSERT INTO `app_lga` VALUES ('677', 'Riyom', '2678');
INSERT INTO `app_lga` VALUES ('678', 'Shendam', '2678');
INSERT INTO `app_lga` VALUES ('679', 'Wase', '2678');
INSERT INTO `app_lga` VALUES ('680', 'Abua/odual', '2679');
INSERT INTO `app_lga` VALUES ('681', 'Ahoada east', '2679');
INSERT INTO `app_lga` VALUES ('682', 'Ahoada west', '2679');
INSERT INTO `app_lga` VALUES ('683', 'Akuku toru', '2679');
INSERT INTO `app_lga` VALUES ('684', 'Andoni', '2679');
INSERT INTO `app_lga` VALUES ('685', 'Asari-toru', '2679');
INSERT INTO `app_lga` VALUES ('686', 'Bonny', '2679');
INSERT INTO `app_lga` VALUES ('687', 'Degema', '2679');
INSERT INTO `app_lga` VALUES ('688', 'Emohua', '2679');
INSERT INTO `app_lga` VALUES ('689', 'Eleme', '2679');
INSERT INTO `app_lga` VALUES ('690', 'Etche', '2679');
INSERT INTO `app_lga` VALUES ('691', 'Gokana', '2679');
INSERT INTO `app_lga` VALUES ('692', 'Ikwerre', '2679');
INSERT INTO `app_lga` VALUES ('693', 'Khana', '2679');
INSERT INTO `app_lga` VALUES ('694', 'Obia/akpor', '2679');
INSERT INTO `app_lga` VALUES ('695', 'Ogba/egbema/ndoni', '2679');
INSERT INTO `app_lga` VALUES ('696', 'Ogu/bolo', '2679');
INSERT INTO `app_lga` VALUES ('697', 'Okrika', '2679');
INSERT INTO `app_lga` VALUES ('698', 'Omumma', '2679');
INSERT INTO `app_lga` VALUES ('699', 'Opobo/nkoro', '2679');
INSERT INTO `app_lga` VALUES ('700', 'Oyigbo', '2679');
INSERT INTO `app_lga` VALUES ('701', 'Port harcourt', '2679');
INSERT INTO `app_lga` VALUES ('702', 'Tai', '2679');
INSERT INTO `app_lga` VALUES ('703', 'Binji', '2680');
INSERT INTO `app_lga` VALUES ('704', 'Bodinga', '2680');
INSERT INTO `app_lga` VALUES ('705', 'Dange-shuni', '2680');
INSERT INTO `app_lga` VALUES ('706', 'Gada', '2680');
INSERT INTO `app_lga` VALUES ('707', 'Goronyo', '2680');
INSERT INTO `app_lga` VALUES ('708', 'Gudu', '2680');
INSERT INTO `app_lga` VALUES ('709', 'Gwadabawa', '2680');
INSERT INTO `app_lga` VALUES ('710', 'Illela', '2680');
INSERT INTO `app_lga` VALUES ('711', 'Isa', '2680');
INSERT INTO `app_lga` VALUES ('712', 'Kware', '2680');
INSERT INTO `app_lga` VALUES ('713', 'Kebbe', '2680');
INSERT INTO `app_lga` VALUES ('714', 'Rabah', '2680');
INSERT INTO `app_lga` VALUES ('715', 'Sabon birni', '2680');
INSERT INTO `app_lga` VALUES ('716', 'Shagari', '2680');
INSERT INTO `app_lga` VALUES ('717', 'Silame', '2680');
INSERT INTO `app_lga` VALUES ('718', 'Sokoto north', '2680');
INSERT INTO `app_lga` VALUES ('719', 'Sokoto south', '2680');
INSERT INTO `app_lga` VALUES ('720', 'Tambuwal', '2680');
INSERT INTO `app_lga` VALUES ('721', 'Tangaza', '2680');
INSERT INTO `app_lga` VALUES ('722', 'Tureta', '2680');
INSERT INTO `app_lga` VALUES ('723', 'Wamakko', '2680');
INSERT INTO `app_lga` VALUES ('724', 'Wurno', '2680');
INSERT INTO `app_lga` VALUES ('725', 'Yabo', '2680');
INSERT INTO `app_lga` VALUES ('726', 'Ardo-kola', '2681');
INSERT INTO `app_lga` VALUES ('727', 'Bali', '2681');
INSERT INTO `app_lga` VALUES ('728', 'Donga', '2681');
INSERT INTO `app_lga` VALUES ('729', 'Gashaka', '2681');
INSERT INTO `app_lga` VALUES ('730', 'Gassol', '2681');
INSERT INTO `app_lga` VALUES ('731', 'Ibi', '2681');
INSERT INTO `app_lga` VALUES ('732', 'Jalingo', '2681');
INSERT INTO `app_lga` VALUES ('733', 'Karim-lamido', '2681');
INSERT INTO `app_lga` VALUES ('734', 'Kurmi', '2681');
INSERT INTO `app_lga` VALUES ('735', 'Lau', '2681');
INSERT INTO `app_lga` VALUES ('736', 'Sarduana', '2681');
INSERT INTO `app_lga` VALUES ('737', 'Takum', '2681');
INSERT INTO `app_lga` VALUES ('738', 'Ussa', '2681');
INSERT INTO `app_lga` VALUES ('739', 'Wukari', '2681');
INSERT INTO `app_lga` VALUES ('740', 'Yorro', '2681');
INSERT INTO `app_lga` VALUES ('741', 'Zing', '2681');
INSERT INTO `app_lga` VALUES ('742', 'Bade', '2682');
INSERT INTO `app_lga` VALUES ('743', 'Bursari', '2682');
INSERT INTO `app_lga` VALUES ('744', 'Damaturu', '2682');
INSERT INTO `app_lga` VALUES ('745', 'Fika', '2682');
INSERT INTO `app_lga` VALUES ('746', 'Fune', '2682');
INSERT INTO `app_lga` VALUES ('747', 'Geidam', '2682');
INSERT INTO `app_lga` VALUES ('748', 'Gujba', '2682');
INSERT INTO `app_lga` VALUES ('749', 'Gulani', '2682');
INSERT INTO `app_lga` VALUES ('750', 'Jakusko', '2682');
INSERT INTO `app_lga` VALUES ('751', 'Karasuwa', '2682');
INSERT INTO `app_lga` VALUES ('752', 'Machina', '2682');
INSERT INTO `app_lga` VALUES ('753', 'Nangere', '2682');
INSERT INTO `app_lga` VALUES ('754', 'Nguru', '2682');
INSERT INTO `app_lga` VALUES ('755', 'Potiskum', '2682');
INSERT INTO `app_lga` VALUES ('756', 'Tarmua', '2682');
INSERT INTO `app_lga` VALUES ('757', 'Yunusari', '2682');
INSERT INTO `app_lga` VALUES ('758', 'Yusufari', '2682');
INSERT INTO `app_lga` VALUES ('759', 'Anka', '2683');
INSERT INTO `app_lga` VALUES ('760', 'Bakurna', '2683');
INSERT INTO `app_lga` VALUES ('761', 'Birnin magaji', '2683');
INSERT INTO `app_lga` VALUES ('762', 'Bukkuyum', '2683');
INSERT INTO `app_lga` VALUES ('763', 'Bungudu', '2683');
INSERT INTO `app_lga` VALUES ('764', 'Gummi', '2683');
INSERT INTO `app_lga` VALUES ('765', 'Kaura namoda', '2683');
INSERT INTO `app_lga` VALUES ('766', 'Maradun', '2683');
INSERT INTO `app_lga` VALUES ('767', 'Maru', '2683');
INSERT INTO `app_lga` VALUES ('768', 'Shinkafi', '2683');
INSERT INTO `app_lga` VALUES ('769', 'Talata', '2683');
INSERT INTO `app_lga` VALUES ('770', 'Mafara', '2683');
INSERT INTO `app_lga` VALUES ('771', 'Tsafe', '2683');
INSERT INTO `app_lga` VALUES ('772', 'Zumi', '2683');
INSERT INTO `app_lga` VALUES ('773', 'Eggon', '2672');
INSERT INTO `app_lga` VALUES ('774', 'Ile oluji', '2675');
INSERT INTO `app_lga` VALUES ('775', 'Sagamu', '2674');
INSERT INTO `app_lga` VALUES ('776', 'Opeji', '2674');
INSERT INTO `app_lga` VALUES ('777', 'Ijebu ode', '2674');
INSERT INTO `app_lga` VALUES ('778', 'Ishan', '2659');
INSERT INTO `app_lga` VALUES ('779', 'Ondo central', '2675');
INSERT INTO `app_lga` VALUES ('780', 'Otukpo', '2654');
INSERT INTO `app_lga` VALUES ('781', 'Abaji', '2648');
INSERT INTO `app_lga` VALUES ('782', 'Abuja Municipal', '2648');
INSERT INTO `app_lga` VALUES ('783', 'Bwari', '2648');
INSERT INTO `app_lga` VALUES ('784', 'Gwagwalada', '2648');
INSERT INTO `app_lga` VALUES ('785', 'Kuje', '2648');
INSERT INTO `app_lga` VALUES ('786', 'Kwali', '2648');
INSERT INTO `app_lga` VALUES ('787', 'Ehime mbano', '2663');
INSERT INTO `app_lga` VALUES ('788', 'Oji river', '2661');
INSERT INTO `app_lga` VALUES ('789', 'Ogbomosho', '2677');
INSERT INTO `app_lga` VALUES ('790', 'Akure south', '2675');
INSERT INTO `app_lga` VALUES ('791', 'Odupani', '2656');
INSERT INTO `app_lga` VALUES ('792', 'Ngor okpala', '2663');
INSERT INTO `app_lga` VALUES ('793', 'Ador', '2654');
INSERT INTO `app_lga` VALUES ('794', 'Okobo', '2650');
INSERT INTO `app_lga` VALUES ('795', 'Idah', '2669');
INSERT INTO `app_lga` VALUES ('796', 'Ugwunagbor', '2647');
INSERT INTO `app_lga` VALUES ('797', 'Ogba/Egbem/Noom', '2679');
INSERT INTO `app_lga` VALUES ('798', 'Okene', '2669');
INSERT INTO `app_lga` VALUES ('799', 'Akoko', '2675');
INSERT INTO `app_lga` VALUES ('800', 'Owo', '2675');
INSERT INTO `app_lga` VALUES ('801', 'Kamba', '2668');
INSERT INTO `app_lga` VALUES ('802', 'Water side', '2674');
INSERT INTO `app_lga` VALUES ('803', 'Egado South', '2674');
INSERT INTO `app_lga` VALUES ('804', 'Imeko Afon', '2674');
INSERT INTO `app_lga` VALUES ('805', 'Panilshin', '2678');
INSERT INTO `app_lga` VALUES ('806', 'Ikalo', '2675');
INSERT INTO `app_lga` VALUES ('807', 'Eredo', '2671');
INSERT INTO `app_lga` VALUES ('808', 'Manufanoti', '2667');
INSERT INTO `app_lga` VALUES ('809', 'Kofa atiku', '2679');
INSERT INTO `app_lga` VALUES ('811', 'Onna', '2650');
INSERT INTO `app_lga` VALUES ('812', 'Udium', '2650');
INSERT INTO `app_lga` VALUES ('813', 'Ake', '2674');
INSERT INTO `app_lga` VALUES ('814', 'Uromi', '2659');
INSERT INTO `app_lga` VALUES ('815', 'Oron', '2650');
INSERT INTO `app_lga` VALUES ('816', 'Oruk', '2650');
INSERT INTO `app_lga` VALUES ('817', 'Aniocha', '2657');
INSERT INTO `app_lga` VALUES ('818', 'Ose', '2675');
INSERT INTO `app_lga` VALUES ('819', 'Oro', '2670');
INSERT INTO `app_lga` VALUES ('820', 'Yewa', '2674');
INSERT INTO `app_lga` VALUES ('821', 'Yewa South', '2674');
INSERT INTO `app_lga` VALUES ('822', 'Yewa North', '2674');
INSERT INTO `app_lga` VALUES ('823', 'Opobo/Nkoro', '2679');
INSERT INTO `app_lga` VALUES ('824', 'Onelga', '2679');
INSERT INTO `app_lga` VALUES ('826', 'Maiduguri .M.C', '2655');

-- ----------------------------
-- Table structure for `app_onepay_transactions`
-- ----------------------------
DROP TABLE IF EXISTS `app_onepay_transactions`;
CREATE TABLE `app_onepay_transactions` (
  `flex_id` varchar(50) NOT NULL DEFAULT '',
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `merch_trans_id` varchar(50) DEFAULT NULL,
  `product_desc` text,
  `merchant_reg_id` varchar(50) DEFAULT NULL,
  `client_email` varchar(75) DEFAULT NULL,
  `client_name` varchar(150) DEFAULT NULL,
  `client_phone` varchar(15) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `response_code` varchar(5) DEFAULT NULL,
  `response_message` varchar(50) DEFAULT NULL,
  `processed_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `order_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`flex_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of app_onepay_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for `app_password_recovery`
-- ----------------------------
DROP TABLE IF EXISTS `app_password_recovery`;
CREATE TABLE `app_password_recovery` (
  `recoveryid` varchar(100) NOT NULL,
  `email` varchar(45) NOT NULL,
  `recovery_code` varchar(145) NOT NULL,
  `date_created` datetime NOT NULL,
  `posteduser` varchar(45) NOT NULL,
  `status` int(4) unsigned DEFAULT '0',
  `used_status` int(4) unsigned DEFAULT '0',
  PRIMARY KEY (`recoveryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of app_password_recovery
-- ----------------------------
INSERT INTO `app_password_recovery` VALUES ('000000', 'inno@gmail.com', 'dSEcohBITE950ZH8fSZHqbSx3M2ylHO2fJfPSR2RN1tugideI5QXMDHJdx9v7ytB', '2020-02-18 14:32:05', 'inno@gmail.com', '1', '0');

-- ----------------------------
-- Table structure for `app_programme_setup_tb`
-- ----------------------------
DROP TABLE IF EXISTS `app_programme_setup_tb`;
CREATE TABLE `app_programme_setup_tb` (
  `prog_id` varchar(8) NOT NULL,
  `program_name` varchar(100) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `posted_user` varchar(64) DEFAULT NULL,
  `status` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  `amount` double(8,2) DEFAULT NULL,
  PRIMARY KEY (`prog_id`) USING BTREE,
  UNIQUE KEY `program_name` (`program_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_programme_setup_tb
-- ----------------------------
INSERT INTO `app_programme_setup_tb` VALUES ('100', 'Nursing', '2018-09-12 14:52:02', 'cephatech', '0', '7000.00');
INSERT INTO `app_programme_setup_tb` VALUES ('101', 'Midwifery', '2018-09-12 14:52:28', 'cephatech', '1', '8000.00');

-- ----------------------------
-- Table structure for `app_results_tb`
-- ----------------------------
DROP TABLE IF EXISTS `app_results_tb`;
CREATE TABLE `app_results_tb` (
  `sid` varchar(230) NOT NULL,
  `reg_id` varchar(16) NOT NULL,
  `subject_name` varchar(30) NOT NULL,
  `result` varchar(12) NOT NULL,
  `grade` varchar(12) NOT NULL,
  `year` varchar(5) NOT NULL,
  `remarks` varchar(36) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `siting` int(10) unsigned DEFAULT '0',
  `position` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_results_tb
-- ----------------------------

-- ----------------------------
-- Table structure for `app_slider`
-- ----------------------------
DROP TABLE IF EXISTS `app_slider`;
CREATE TABLE `app_slider` (
  `slider_id` varchar(100) NOT NULL DEFAULT '',
  `slider_title` varchar(200) DEFAULT NULL,
  `slider_imageurl` varchar(200) DEFAULT NULL,
  `status` varchar(2) DEFAULT '0',
  `posteddate` datetime NOT NULL,
  `postedby` varchar(20) DEFAULT NULL,
  `slider_msg` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`slider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of app_slider
-- ----------------------------
INSERT INTO `app_slider` VALUES ('app_01', 'welcome to Olivet School of Health Technology', 'images/slider1.jpg', '1', '2020-02-28 11:25:09', null, 'Lafia Nasarrawa State');

-- ----------------------------
-- Table structure for `app_staff`
-- ----------------------------
DROP TABLE IF EXISTS `app_staff`;
CREATE TABLE `app_staff` (
  `staff_id` varchar(100) NOT NULL DEFAULT '',
  `staff_name` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL,
  `staff_imageurl` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of app_staff
-- ----------------------------
INSERT INTO `app_staff` VALUES ('12', 'Akor Innocent', 'Software Engineer', '1', 'images/slider2.jpg');

-- ----------------------------
-- Table structure for `app_states`
-- ----------------------------
DROP TABLE IF EXISTS `app_states`;
CREATE TABLE `app_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2684 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_states
-- ----------------------------
INSERT INTO `app_states` VALUES ('2647', 'Abia', '160');
INSERT INTO `app_states` VALUES ('2648', 'Abuja Federal Capital Territory', '160');
INSERT INTO `app_states` VALUES ('2649', 'Adamawa', '160');
INSERT INTO `app_states` VALUES ('2650', 'Akwa Ibom', '160');
INSERT INTO `app_states` VALUES ('2651', 'Anambra', '160');
INSERT INTO `app_states` VALUES ('2652', 'Bauchi', '160');
INSERT INTO `app_states` VALUES ('2653', 'Bayelsa', '160');
INSERT INTO `app_states` VALUES ('2654', 'Benue', '160');
INSERT INTO `app_states` VALUES ('2655', 'Borno', '160');
INSERT INTO `app_states` VALUES ('2656', 'Cross River', '160');
INSERT INTO `app_states` VALUES ('2657', 'Delta', '160');
INSERT INTO `app_states` VALUES ('2658', 'Ebonyi', '160');
INSERT INTO `app_states` VALUES ('2659', 'Edo', '160');
INSERT INTO `app_states` VALUES ('2660', 'Ekiti', '160');
INSERT INTO `app_states` VALUES ('2661', 'Enugu', '160');
INSERT INTO `app_states` VALUES ('2662', 'Gombe', '160');
INSERT INTO `app_states` VALUES ('2663', 'Imo', '160');
INSERT INTO `app_states` VALUES ('2664', 'Jigawa', '160');
INSERT INTO `app_states` VALUES ('2665', 'Kaduna', '160');
INSERT INTO `app_states` VALUES ('2666', 'Kano', '160');
INSERT INTO `app_states` VALUES ('2667', 'Katsina', '160');
INSERT INTO `app_states` VALUES ('2668', 'Kebbi', '160');
INSERT INTO `app_states` VALUES ('2669', 'Kogi', '160');
INSERT INTO `app_states` VALUES ('2670', 'Kwara', '160');
INSERT INTO `app_states` VALUES ('2671', 'Lagos', '160');
INSERT INTO `app_states` VALUES ('2672', 'Nassarawa', '160');
INSERT INTO `app_states` VALUES ('2673', 'Niger', '160');
INSERT INTO `app_states` VALUES ('2674', 'Ogun', '160');
INSERT INTO `app_states` VALUES ('2675', 'Ondo', '160');
INSERT INTO `app_states` VALUES ('2676', 'Osun', '160');
INSERT INTO `app_states` VALUES ('2677', 'Oyo', '160');
INSERT INTO `app_states` VALUES ('2678', 'Plateau', '160');
INSERT INTO `app_states` VALUES ('2679', 'Rivers', '160');
INSERT INTO `app_states` VALUES ('2680', 'Sokoto', '160');
INSERT INTO `app_states` VALUES ('2681', 'Taraba', '160');
INSERT INTO `app_states` VALUES ('2682', 'Yobe', '160');
INSERT INTO `app_states` VALUES ('2683', 'Zamfara', '160');

-- ----------------------------
-- Table structure for `app_subject_grade_tb`
-- ----------------------------
DROP TABLE IF EXISTS `app_subject_grade_tb`;
CREATE TABLE `app_subject_grade_tb` (
  `gid` varchar(255) NOT NULL,
  `grade_name` varchar(255) NOT NULL,
  PRIMARY KEY (`gid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_subject_grade_tb
-- ----------------------------
INSERT INTO `app_subject_grade_tb` VALUES ('1', 'A1');
INSERT INTO `app_subject_grade_tb` VALUES ('2', 'B2');
INSERT INTO `app_subject_grade_tb` VALUES ('3', 'B3');
INSERT INTO `app_subject_grade_tb` VALUES ('4', 'C4');
INSERT INTO `app_subject_grade_tb` VALUES ('5', 'C5');
INSERT INTO `app_subject_grade_tb` VALUES ('6', 'C6');
INSERT INTO `app_subject_grade_tb` VALUES ('7', 'D7');
INSERT INTO `app_subject_grade_tb` VALUES ('8', 'E8');
INSERT INTO `app_subject_grade_tb` VALUES ('9', 'F9');

-- ----------------------------
-- Table structure for `app_subject_name_tb`
-- ----------------------------
DROP TABLE IF EXISTS `app_subject_name_tb`;
CREATE TABLE `app_subject_name_tb` (
  `sid` varchar(4) NOT NULL,
  `subject_name` varchar(56) NOT NULL,
  `category` varchar(16) NOT NULL,
  PRIMARY KEY (`sid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_subject_name_tb
-- ----------------------------
INSERT INTO `app_subject_name_tb` VALUES ('10', 'C.R.S', '0');
INSERT INTO `app_subject_name_tb` VALUES ('11', 'Marketing', '0');
INSERT INTO `app_subject_name_tb` VALUES ('12', 'Agricultural Science', '0');
INSERT INTO `app_subject_name_tb` VALUES ('13', 'Accounting', '0');
INSERT INTO `app_subject_name_tb` VALUES ('14', 'Geography', '0');
INSERT INTO `app_subject_name_tb` VALUES ('15', 'Further Mathematics', '0');
INSERT INTO `app_subject_name_tb` VALUES ('6', 'Literature', '0');
INSERT INTO `app_subject_name_tb` VALUES ('7', 'Economics', '0');
INSERT INTO `app_subject_name_tb` VALUES ('8', 'Government', '0');
INSERT INTO `app_subject_name_tb` VALUES ('9', 'Commerce', '0');

-- ----------------------------
-- Table structure for `app_tbl_rrr_log`
-- ----------------------------
DROP TABLE IF EXISTS `app_tbl_rrr_log`;
CREATE TABLE `app_tbl_rrr_log` (
  `rrr` varchar(16) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT NULL,
  `date_generated` datetime DEFAULT '0000-00-00 00:00:00',
  `date_paid` datetime DEFAULT '0000-00-00 00:00:00',
  `order_id` varchar(16) DEFAULT NULL,
  `status_code` varchar(4) DEFAULT NULL,
  `status_message` varchar(56) DEFAULT NULL,
  `logid` varchar(145) NOT NULL,
  `program_code` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`logid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of app_tbl_rrr_log
-- ----------------------------

-- ----------------------------
-- Table structure for `faculty_setup`
-- ----------------------------
DROP TABLE IF EXISTS `faculty_setup`;
CREATE TABLE `faculty_setup` (
  `faculty_id` varchar(80) NOT NULL,
  `faculty_head` varchar(80) NOT NULL,
  `faculty_established` year(4) NOT NULL,
  `created_by` datetime NOT NULL,
  `posted_by` varchar(80) NOT NULL,
  PRIMARY KEY (`faculty_id`),
  KEY `faculty_head` (`faculty_head`),
  CONSTRAINT `faculty_head` FOREIGN KEY (`faculty_head`) REFERENCES `staff_information` (`staff_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of faculty_setup
-- ----------------------------

-- ----------------------------
-- Table structure for `gendata`
-- ----------------------------
DROP TABLE IF EXISTS `gendata`;
CREATE TABLE `gendata` (
  `table_name` varchar(30) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of gendata
-- ----------------------------
INSERT INTO `gendata` VALUES ('CART', '32', '1');
INSERT INTO `gendata` VALUES ('menu', '589', '2');
INSERT INTO `gendata` VALUES ('PAYMENT_NOTIFICATION', '87', '3');
INSERT INTO `gendata` VALUES ('acno', '2', '4');
INSERT INTO `gendata` VALUES ('itemId', '146', '5');
INSERT INTO `gendata` VALUES ('PRODUCT-ID', '340', '6');
INSERT INTO `gendata` VALUES ('login_id', '20', '7');
INSERT INTO `gendata` VALUES ('item_id', '24', '8');
INSERT INTO `gendata` VALUES ('ApplyNow', '338', '9');
INSERT INTO `gendata` VALUES ('programm', '168', '10');
INSERT INTO `gendata` VALUES ('ACC-ONEPAY20190104', '32', '11');
INSERT INTO `gendata` VALUES ('REMITA_NOTIFICATION_STATUS', '4', '12');
INSERT INTO `gendata` VALUES ('results', '10', '13');
INSERT INTO `gendata` VALUES ('result_tb', '643', '14');
INSERT INTO `gendata` VALUES ('ACC-ONEPAY20190128', '1', '15');
INSERT INTO `gendata` VALUES ('ACC-ONEPAY20190206', '1', '16');
INSERT INTO `gendata` VALUES ('ACC-ONEPAY20190319', '2', '17');
INSERT INTO `gendata` VALUES ('ApplyNow2019', '63', '18');
INSERT INTO `gendata` VALUES ('ACC-ONEPAY20190327', '2', '19');
INSERT INTO `gendata` VALUES ('recoverpassword2019', '17', '20');
INSERT INTO `gendata` VALUES ('accetance2019', '22', '21');

-- ----------------------------
-- Table structure for `parameter`
-- ----------------------------
DROP TABLE IF EXISTS `parameter`;
CREATE TABLE `parameter` (
  `parameter_name` varchar(60) DEFAULT NULL,
  `parameter_value` varchar(100) DEFAULT NULL,
  `parameter_flag` enum('0','1') DEFAULT '1',
  `parameter_desc` text,
  `parameterid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`parameterid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of parameter
-- ----------------------------
INSERT INTO `parameter` VALUES ('ADMIN_EMAIL', 'innocentedwin25@yahoo.com', '1', 'Admin email', '1');
INSERT INTO `parameter` VALUES ('working_hours', '00:00-23:59', '0', 'Allotted working hours of the day', '2');
INSERT INTO `parameter` VALUES ('country_code', '566', '0', 'Default Country', '3');
INSERT INTO `parameter` VALUES ('currency', 'NGN', '0', 'Default Country Currency', '4');
INSERT INTO `parameter` VALUES ('no_of_pin_misses', '5', '0', 'Available number of pin misses allowed', '5');
INSERT INTO `parameter` VALUES ('password_expiry_days', '30', '0', 'Number of days for password expiry', '6');
INSERT INTO `parameter` VALUES ('inactivity_time', '5', '1', 'Time to timeout in minutes', '7');
INSERT INTO `parameter` VALUES ('root_link', 'http://nassnm.edu.ng/', '1', 'School of Nursing And midwifery', '8');

-- ----------------------------
-- Table structure for `paymentnotificationtb`
-- ----------------------------
DROP TABLE IF EXISTS `paymentnotificationtb`;
CREATE TABLE `paymentnotificationtb` (
  `pnId` varchar(16) DEFAULT NULL,
  `rrr` varchar(12) NOT NULL,
  `channel` varchar(16) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `transactiondate` varchar(16) DEFAULT NULL,
  `debitdate` varchar(16) DEFAULT NULL,
  `bank` varchar(4) DEFAULT NULL,
  `branch` varchar(11) DEFAULT NULL,
  `serviceTypeId` varchar(11) DEFAULT NULL,
  `dateRequested` varchar(16) DEFAULT NULL,
  `orderRef` varchar(16) DEFAULT NULL,
  `payerName` varchar(32) DEFAULT NULL,
  `payerPhoneNumber` varchar(15) DEFAULT NULL,
  `payerEmail` varchar(32) DEFAULT NULL,
  `uniqueIdentifier` varchar(24) DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `statusCode` varchar(6) DEFAULT '0',
  `consultance_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`rrr`) USING BTREE,
  UNIQUE KEY `rrr` (`rrr`) USING BTREE,
  UNIQUE KEY `pnId` (`pnId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of paymentnotificationtb
-- ----------------------------
INSERT INTO `paymentnotificationtb` VALUES ('PN000000000099', '100223714923', 'Bank', '100250', '2018-05-17 12:00', '2018-05-23 03:30', '2222', '313131', '1233232323', '0.00297176820208', '00000240', 'erex', '08032882888', 'info@erex.com.ng', '', '2019-01-02 11:00:00', '01', 'Cons0016');
INSERT INTO `paymentnotificationtb` VALUES ('RRR000000000001', '300007724951', 'bank', '56000', '11/11/2019', '11/11/2019', '4236', '3653653563', '4252525', '0.00058534828182', '7646476474', 'adeniyi james', '08032882888', 'Adeniy@accessng.com', '', '2019-01-09 12:59:39', '00', null);
INSERT INTO `paymentnotificationtb` VALUES ('RRR000000000004', '340007724953', 'bank', '56000', '11/11/2019', '11/11/2019', '4236', '3653653563', '4252525', '0.00058534828182', '7646476474', 'adeniyi james', '08032882888', 'Adeniy@accessng.com', '', '2019-01-09 01:10:22', '00', null);
INSERT INTO `paymentnotificationtb` VALUES ('PN000000000117', '350229488070', 'Bank', '307750', '2018-06-28 12:00', '2018-06-29 11:52', '2222', '313131', '1233232323', '0.00297176820208', '00000289', 'erex', '08032882888', 'info@erex.com.ng', '', '2019-01-02 11:00:00', '01', 'Cons0016');

-- ----------------------------
-- Table structure for `split_report_tb`
-- ----------------------------
DROP TABLE IF EXISTS `split_report_tb`;
CREATE TABLE `split_report_tb` (
  `sr_id` varchar(16) NOT NULL,
  `transaction_d` varchar(16) DEFAULT NULL,
  `account_number` varchar(10) DEFAULT NULL,
  `account_name` varchar(128) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `bank_name` varchar(64) DEFAULT NULL,
  `percent_amount` int(11) DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`sr_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of split_report_tb
-- ----------------------------

-- ----------------------------
-- Table structure for `staff_information`
-- ----------------------------
DROP TABLE IF EXISTS `staff_information`;
CREATE TABLE `staff_information` (
  `staff_id` varchar(80) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `middle_name` varchar(80) DEFAULT NULL,
  `Address` varchar(80) NOT NULL,
  `home_phone` varchar(11) NOT NULL,
  `alternate_phone` varchar(11) NOT NULL,
  `staff_email` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `marital_status` varchar(50) NOT NULL,
  `staff_type` int(1) NOT NULL COMMENT '1 represents academic staff while 0 represents non-academic staff',
  `Employment_date` date NOT NULL,
  `employment_status` varchar(50) NOT NULL,
  `staff_qualification` varchar(80) NOT NULL,
  `staff_faculty` varchar(80) NOT NULL,
  PRIMARY KEY (`staff_id`),
  KEY `employment` (`employment_status`),
  KEY `staff qualification` (`staff_qualification`),
  KEY `faculty` (`staff_faculty`),
  CONSTRAINT `employment` FOREIGN KEY (`employment_status`) REFERENCES `staff_information` (`staff_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `faculty` FOREIGN KEY (`staff_faculty`) REFERENCES `faculty_setup` (`faculty_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `staff qualification` FOREIGN KEY (`staff_qualification`) REFERENCES `staff_information` (`staff_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of staff_information
-- ----------------------------

-- ----------------------------
-- Table structure for `userdata`
-- ----------------------------
DROP TABLE IF EXISTS `userdata`;
CREATE TABLE `userdata` (
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role_id` varchar(20) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile_phone` varchar(20) NOT NULL,
  `passchg_logon` char(1) NOT NULL,
  `pass_expire` varchar(1) DEFAULT NULL,
  `pass_dateexpire` date DEFAULT NULL,
  `pass_change` char(1) DEFAULT NULL,
  `user_disabled` char(1) NOT NULL,
  `user_locked` char(1) NOT NULL,
  `day_1` char(1) NOT NULL,
  `day_2` char(1) NOT NULL,
  `day_3` char(1) NOT NULL,
  `day_4` char(1) NOT NULL,
  `day_5` char(1) NOT NULL,
  `day_6` char(1) NOT NULL,
  `day_7` char(1) NOT NULL,
  `pin_missed` int(2) NOT NULL DEFAULT '0',
  `last_used` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `email_activation` char(1) DEFAULT '1',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hint_question` varchar(100) DEFAULT NULL,
  `hint_answer` varchar(100) DEFAULT NULL,
  `override_wh` char(1) DEFAULT NULL,
  `extend_wh` varchar(17) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `posted_user` varchar(100) DEFAULT NULL,
  `last_used_passwords` varchar(250) DEFAULT NULL,
  `merchant_id` varchar(50) DEFAULT NULL,
  `token_id` varchar(10) DEFAULT NULL,
  `station_code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='InnoDB free: 11264 kB; InnoDB free: 11264 kB; InnoDB free: 1';

-- ----------------------------
-- Records of userdata
-- ----------------------------
INSERT INTO `userdata` VALUES ('admin', '0xa89f51883606faf7', '5121', 'Nibox', 'innocent', 'innocentedwin26@yahoo.com', '8089708251', '0', '0', '2019-08-09', null, '0', '0', '1', '1', '1', '1', '1', '1', '1', '0', '0000-00-00 00:00:00', '1', '2019-07-10 03:47:04', null, null, '0', '', '2019-07-10 03:47:04', 'cephatech', null, null, null, '');
INSERT INTO `userdata` VALUES ('cephatech', '0x79a5b2cf38bed780', '5111', 'kwavis', 'kwavis', 'kwavis@yahoo.com', '#', '0', null, '2018-12-31', null, '0', '0', '1', '1', '1', '1', '1', '1', '1', '0', '2018-09-19 10:35:31', '1', '2018-07-19 12:56:17', null, null, '0', '', '2018-07-04 16:53:30', 'kwavis', null, null, '0001', '001');
INSERT INTO `userdata` VALUES ('innocentedwin25@yahoo.com', '0x421bb7e9125a0fa7', '5111', 'Nibox', 'innocent', 'innocentedwin25@yahoo.com', '08089708251', '0', '0', '2019-08-09', null, '0', '0', '1', '1', '1', '1', '1', '1', '1', '0', '0000-00-00 00:00:00', '1', '2019-07-10 08:59:18', null, null, '0', '', '2019-07-10 08:59:18', 'cephatech', null, null, null, '');
INSERT INTO `userdata` VALUES ('martend', '0x710b4b523a12918a', '005', 'Martins', 'Orji', 'lamartiniz@yahoo.com', '08099717277333', '0', null, null, null, '0', '0', '1', '1', '1', '1', '1', '1', '1', '0', '2018-07-29 17:14:00', '1', '2018-07-31 11:17:29', null, null, '0', '', '2017-09-15 12:59:47', 'kwavis', null, '', null, '001');
INSERT INTO `userdata` VALUES ('superadmin', '0xa3dfc24b321a6517', '5111', 'Admin', 'Admin ', 'admin@mail.com', '', '0', '', '1970-01-01', null, '0', '0', '1', '1', '1', '1', '1', '1', '1', '0', '2018-07-20 09:29:29', '1', '2018-07-10 17:50:16', null, null, '0', '', '2018-07-10 17:50:16', '', null, null, null, null);
INSERT INTO `userdata` VALUES ('support', '0xf91be86ad8b9c701', '004', 'Ese', 'Uvbiekpahor', 'ese.kelvin@accessng.com', '001', '0', null, null, null, '0', '0', '1', '1', '1', '1', '1', '1', '1', '0', '2018-08-17 13:02:21', '1', '2018-07-19 12:56:00', null, null, '0', '', '2017-01-16 14:13:53', 'kwavis', null, '', null, '001');
