/*
Navicat MySQL Data Transfer

Source Server         : graduation
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : supermarket

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-01-16 14:36:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for administrators
-- ----------------------------
DROP TABLE IF EXISTS `administrators`;
CREATE TABLE `administrators` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `password` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '管理员名称',
  `email` varchar(255) DEFAULT NULL COMMENT '管理员邮箱',
  `phone` varchar(255) DEFAULT NULL COMMENT '管理员手机号',
  `level` int(255) DEFAULT NULL COMMENT '管理员等级  0:超级管理员    1：普通管理员     2：仓库管理员    3：超市合作方管理员',
  `mkid` varchar(255) DEFAULT NULL COMMENT '所属超市',
  `login_time` varchar(255) DEFAULT NULL COMMENT '上次登陆时间  10位时间戳',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of administrators
-- ----------------------------
INSERT INTO `administrators` VALUES ('1', 'ff9830c42660c1dd1942844f8069b74a', 'root', '1474920136@qq.com', '13507431951', '0', '', '1579075633');
INSERT INTO `administrators` VALUES ('5', '8385bf525aaf1a144c510a1a3da4c53e', 'cangku', '13201646@qq.com', '15462358142', '2', '', '1578905743');
INSERT INTO `administrators` VALUES ('4', '964fc168c9ff0b572ec622ab6198cde1', 'putong', '156113412@qq.com', '13074521390', '1', '', '1579075399');
INSERT INTO `administrators` VALUES ('6', 'a28628422cb42d281ac53192b9862d9f', 'hezuo', '7461130535@qq.com', '15423017890', '3', '1001', '1579074311');

-- ----------------------------
-- Table structure for all_goods
-- ----------------------------
DROP TABLE IF EXISTS `all_goods`;
CREATE TABLE `all_goods` (
  `gid` varchar(8) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` int(11) NOT NULL COMMENT '售价 单位：分',
  `sprice` int(11) DEFAULT '0' COMMENT '进价  单位：分',
  `type` int(11) DEFAULT NULL COMMENT '商品类型',
  `gen_time` varchar(255) DEFAULT NULL COMMENT '生产日期',
  `expiration_date` int(255) DEFAULT NULL COMMENT '保质期',
  `expire_date` varchar(255) DEFAULT NULL COMMENT '到期时间',
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of all_goods
-- ----------------------------
INSERT INTO `all_goods` VALUES ('01010001', '溜溜梅60g', '510', '0', '1', '2019-11-01 00:00:00', '182', '2020-05-01 00:00:00');
INSERT INTO `all_goods` VALUES ('01000002', '爽歪歪200g', '200', '0', '0', '2019-11-08 00:00:00', '30', '2019-12-08 00:00:00');
INSERT INTO `all_goods` VALUES ('01020003', '舒肤佳香皂', '450', '0', '2', '2019-12-04 00:00:00', '360', '2020-11-28 00:00:00');
INSERT INTO `all_goods` VALUES ('01010004', '苏打水', '300', '0', '0', '2020-01-01 00:00:00', '180', '2020-06-29 00:00:00');
INSERT INTO `all_goods` VALUES ('01020005', '作业本', '100', '0', '2', '2019-12-19 00:00:00', '360', '2020-12-13 00:00:00');
INSERT INTO `all_goods` VALUES ('01010006', '小熊饼干', '100', '0', '1', '2019-12-26 00:00:00', '90', '2020-03-25 00:00:00');
INSERT INTO `all_goods` VALUES ('01010007', '鸭腿', '450', '0', '1', '2019-12-10 00:00:00', '90', '2020-03-09 00:00:00');
INSERT INTO `all_goods` VALUES ('01010008', '盼盼虾条', '500', '0', '1', '2020-01-02 00:00:00', '180', '2020-06-30 00:00:00');
INSERT INTO `all_goods` VALUES ('01010009', '棒棒糖', '50', '0', '1', '2020-01-01 00:00:00', '180', '2020-06-29 00:00:00');
INSERT INTO `all_goods` VALUES ('01020010', '飘柔洗发水', '180', '0', '2', '2019-12-11 00:00:00', '360', '2020-12-05 00:00:00');
INSERT INTO `all_goods` VALUES ('01020011', '南德调味料', '450', '0', '2', '2019-12-28 00:00:00', '180', '2020-06-25 00:00:00');
INSERT INTO `all_goods` VALUES ('01000012', '菠萝啤', '200', '0', '0', '2019-12-23 00:00:00', '180', '2020-06-20 00:00:00');
INSERT INTO `all_goods` VALUES ('01000013', '加多宝', '300', '0', '0', '2019-12-31 00:00:00', '180', '2020-06-28 00:00:00');
INSERT INTO `all_goods` VALUES ('01020014', '隆力奇护手霜', '500', '0', '0', '2019-12-11 00:00:00', '360', '2020-12-05 00:00:00');
INSERT INTO `all_goods` VALUES ('01020015', '粘鞋胶', '200', '0', '2', '2019-12-08 00:00:00', '360', '2020-12-02 00:00:00');
INSERT INTO `all_goods` VALUES ('01010016', '猫耳朵', '100', '0', '1', '2019-12-24 00:00:00', '180', '2020-06-21 00:00:00');

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` varchar(8) NOT NULL,
  `mkid` varchar(4) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` int(11) NOT NULL COMMENT '售价 单位：分',
  `sprice` int(11) DEFAULT '0' COMMENT '进价  单位：分',
  `count` int(11) DEFAULT '0' COMMENT '商品库存',
  `type` int(11) DEFAULT NULL COMMENT '商品类型',
  `gen_time` varchar(255) DEFAULT NULL COMMENT '生产日期',
  `expiration_date` int(255) DEFAULT NULL COMMENT '保质期',
  `expire_date` varchar(255) DEFAULT NULL COMMENT '到期时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of goods
-- ----------------------------
INSERT INTO `goods` VALUES ('1', '01010001', '1001', '溜溜梅60g', '510', '0', '5', '1', '2019-11-01 00:00:00', '182', '2020-05-01 00:00:00');
INSERT INTO `goods` VALUES ('2', '01000002', '1001', '爽歪歪200g', '200', '0', '8', '0', '2019-11-08 00:00:00', '30', '2019-12-08 00:00:00');
INSERT INTO `goods` VALUES ('3', '01020003', '1001', '舒肤佳香皂', '450', '0', '14', '2', '2019-12-04 00:00:00', '360', '2020-11-28 00:00:00');
INSERT INTO `goods` VALUES ('4', '01010001', '1002', '溜溜梅60g', '510', '0', '2', '1', '2019-11-01 00:00:00', '182', '2020-05-01 00:00:00');
INSERT INTO `goods` VALUES ('5', '01000002', '1002', '爽歪歪200g', '200', '0', '2', '0', '2019-11-08 00:00:00', '30', '2019-12-08 00:00:00');
INSERT INTO `goods` VALUES ('6', '01020003', '1003', '舒肤佳香皂', '450', '0', '2', '2', '2019-12-04 00:00:00', '360', '2020-11-28 00:00:00');
INSERT INTO `goods` VALUES ('7', '01010001', '1003', '溜溜梅60g', '510', '0', '1', '1', '2019-11-01 00:00:00', '182', '2020-05-01 00:00:00');
INSERT INTO `goods` VALUES ('8', '01010004', '1001', '苏打水', '300', '0', '10', '0', '2020-01-01 00:00:00', '180', '2020-06-29 00:00:00');
INSERT INTO `goods` VALUES ('9', '01020005', '1001', '作业本', '100', '0', '15', '2', '2019-12-19 00:00:00', '360', '2020-12-13 00:00:00');
INSERT INTO `goods` VALUES ('10', '01010006', '1001', '小熊饼干', '100', '0', '6', '1', '2019-12-26 00:00:00', '90', '2020-03-25 00:00:00');
INSERT INTO `goods` VALUES ('11', '01010007', '1001', '鸭腿', '450', '0', '7', '1', '2019-12-10 00:00:00', '90', '2020-03-09 00:00:00');
INSERT INTO `goods` VALUES ('12', '01010008', '1001', '盼盼虾条', '500', '0', '13', '1', '2020-01-02 00:00:00', '180', '2020-06-30 00:00:00');
INSERT INTO `goods` VALUES ('13', '01010009', '1001', '棒棒糖', '50', '0', '15', '1', '2020-01-01 00:00:00', '180', '2020-06-29 00:00:00');
INSERT INTO `goods` VALUES ('14', '01020010', '1001', '飘柔洗发水', '1800', '0', '5', '2', '2019-12-11 00:00:00', '360', '2020-12-05 00:00:00');
INSERT INTO `goods` VALUES ('15', '01020011', '1001', '南德调味料', '450', '0', '8', '2', '2019-12-28 00:00:00', '180', '2020-06-25 00:00:00');
INSERT INTO `goods` VALUES ('16', '01000012', '1001', '菠萝啤', '200', '0', '20', '0', '2019-12-23 00:00:00', '180', '2020-06-20 00:00:00');
INSERT INTO `goods` VALUES ('17', '01000013', '1001', '加多宝', '300', '0', '18', '0', '2019-12-31 00:00:00', '180', '2020-06-28 00:00:00');
INSERT INTO `goods` VALUES ('18', '01020014', '1001', '隆力奇护手霜', '500', '0', '16', '0', '2019-12-11 00:00:00', '360', '2020-12-05 00:00:00');
INSERT INTO `goods` VALUES ('19', '01020015', '1001', '粘鞋胶', '200', '0', '9', '2', '2019-12-08 00:00:00', '360', '2020-12-02 00:00:00');
INSERT INTO `goods` VALUES ('20', '01010016', '1001', '猫耳朵', '100', '0', '11', '1', '2019-12-24 00:00:00', '180', '2020-06-21 00:00:00');

-- ----------------------------
-- Table structure for markets
-- ----------------------------
DROP TABLE IF EXISTS `markets`;
CREATE TABLE `markets` (
  `mkid` varchar(4) NOT NULL COMMENT '超市id',
  `descp` varchar(255) DEFAULT NULL COMMENT '超市名',
  `kind` int(255) DEFAULT NULL COMMENT '超市类型  1:自营   2：半合作    3：全合作',
  `state` int(255) DEFAULT '0' COMMENT '超市状态 0:软锁门  1：正常',
  `action` int(255) DEFAULT '0' COMMENT '超市灯光状态  0：关灯   1：开灯',
  `pos` varchar(255) DEFAULT NULL COMMENT '超市经纬度',
  PRIMARY KEY (`mkid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of markets
-- ----------------------------
INSERT INTO `markets` VALUES ('1001', '1号超市', '1', '1', '1', '河南省周口市');
INSERT INTO `markets` VALUES ('1002', '2号超市', '2', '1', '0', '河南省郑州市');
INSERT INTO `markets` VALUES ('1003', '3号超市', '3', '0', '0', '河南省郑州市');
INSERT INTO `markets` VALUES ('1004', '4号超市', '1', '0', '0', '河南省周口市');
INSERT INTO `markets` VALUES ('1005', '阳光超市', '1', '1', '0', '河南省新乡市');
INSERT INTO `markets` VALUES ('1006', '鸿运百货超市', '1', '0', '0', '河南省商丘市');
INSERT INTO `markets` VALUES ('1007', '乡邻一家生活超市', '2', '0', '0', '河南省许昌市');
INSERT INTO `markets` VALUES ('1008', '8号合作超市', '3', '1', '0', '河南省郑州市');
INSERT INTO `markets` VALUES ('1009', '9半合作超市', '2', '0', '0', '河南省信阳市');
INSERT INTO `markets` VALUES ('1010', '10号自营超市', '1', '1', '0', '河南省郑州市');
INSERT INTO `markets` VALUES ('1011', '发发百货超市', '1', '1', '0', '河南省周口市');

-- ----------------------------
-- Table structure for sales_record
-- ----------------------------
DROP TABLE IF EXISTS `sales_record`;
CREATE TABLE `sales_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` varchar(8) DEFAULT NULL,
  `mkid` varchar(4) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `gname` varchar(255) DEFAULT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL COMMENT '商品售价',
  `sprice` int(11) DEFAULT NULL COMMENT '商品进价',
  `count` int(11) DEFAULT NULL COMMENT '销售数量',
  `subtotal` int(11) DEFAULT NULL COMMENT '购买总价',
  `username` varchar(255) DEFAULT NULL COMMENT '购物者名称',
  `phone` varchar(255) DEFAULT NULL COMMENT '购物者手机号',
  `time` varchar(10) DEFAULT NULL COMMENT '支付时间   10位时间戳',
  `payway` int(255) DEFAULT NULL COMMENT '支付方式   0：微信   1：支付宝',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sales_record
-- ----------------------------
INSERT INTO `sales_record` VALUES ('1', '01010001', '1001', 'opWzIv-2erKBFneuy0aWGosEHLs0', '溜溜梅60g', '1号超市', '500', '0', '1', '500', '小月', '15041203619', '1577863248', '0');
INSERT INTO `sales_record` VALUES ('2', '01000002', '1001', 'opWzIv-57mw7QHcf4-wAcnlUeVZk', '爽歪歪200g', '1号超市', '200', '0', '1', '200', '小乔', '18610237451', '1577874250', '1');
INSERT INTO `sales_record` VALUES ('3', '01020003', '1002', 'opWzIv-8LLADVyr8dSiD4LHmlF38', '舒肤佳香皂', '2号超市', '450', '0', '2', '900', '豆豆', '15142361020', '1577883165', '0');
INSERT INTO `sales_record` VALUES ('4', '01000002', '1003', 'opWzIv7_q5PhQ_PfRueGzSdmjueA', '爽歪歪200g', '3号超市', '200', '0', '4', '800', '沐沐', '17648349134', '1577931155', '1');
INSERT INTO `sales_record` VALUES ('5', '01010004', '1001', 'opWzIv3elSV95hebpBnZ2NujjB4o', '苏打水', '1号超市', '300', '0', '1', '300', '听雪', '15378149163', '1577940330', '0');
INSERT INTO `sales_record` VALUES ('6', '01010008', '1001', 'opWzIv7_nlyXcnoozqkJAqIBOaeA', '盼盼虾条', '1号超市', '500', '0', '2', '500', '贾若成', '13678135137', '1577948616', '0');
INSERT INTO `sales_record` VALUES ('7', '01020010', '1001', 'opWzIv-2erKBFneuy0aWGosEHLs0', '飘柔洗发水', '1号超市', '1800', '0', '1', '1800', '小月', '15041203619', '1577954207', '1');
INSERT INTO `sales_record` VALUES ('8', '01020011', '1001', 'opWzIv-2erKBFneuy0aWGosEHLs0', '南德调味料', '1号超市', '450', '0', '1', '450', '小月', '15041203619', '1577954207', '1');
INSERT INTO `sales_record` VALUES ('9', '01000013', '1001', 'opWzIv-srEWno_7ltpco9EtRQafc', '加多宝', '1号超市', '300', '0', '1', '300', '在风雨中行走', '13256410237', '1577959756', '0');
INSERT INTO `sales_record` VALUES ('10', '01010016', '1001', 'opWzIv-srEWno_7ltpco9EtRQafc', '猫耳朵', '1号超市', '100', '0', '2', '200', '在风雨中行走', '13256410237', '1577959756', '0');
INSERT INTO `sales_record` VALUES ('11', '01010001', '1001', 'opWzIv-srEWno_7ltpco9EtRQafc', '溜溜梅60g', '1号超市', '510', '0', '1', '510', '在风雨中行走', '13256410237', '1577959756', '0');
INSERT INTO `sales_record` VALUES ('12', '01010004', '1001', 'opWzIv-57mw7QHcf4-wAcnlUeVZk', '苏打水', '1号超市', '300', '0', '1', '300', '小乔', '18610237451', '1577963051', '1');
INSERT INTO `sales_record` VALUES ('14', '01020014', '1001', 'opWzIv3elSV95hebpBnZ2NujjB4o', '隆力奇护手霜', '1号超市', '500', '0', '1', '500', '听雪', '15378149163', '1577969729', '0');
INSERT INTO `sales_record` VALUES ('13', '01010009', '1001', 'opWzIv-PjqIMv3LJDzuTUJoPKeSE', '棒棒糖', '1号超市', '50', '0', '4', '200', '沉默是金', '14589762304', '1577965430', '0');
INSERT INTO `sales_record` VALUES ('15', '01010004', '1001', 'opWzIv-d_CX6AU6LbLRfuLbK4-7U', '苏打水', '1号超市', '300', '0', '2', '600', '王瑞', '13248509120', '1577972046', '0');
INSERT INTO `sales_record` VALUES ('16', '01000012', '1001', 'opWzIv-mTQtDCZHTZAG8_Yg4wa8s', '菠萝啤', '1号超市', '200', '0', '2', '400', '陌生人', '15478956012', '1577976543', '1');
INSERT INTO `sales_record` VALUES ('17', '01010016', '1001', 'opWzIv30vJ3GAIW1RrRnwDBhdlE0', '猫耳朵', '1号超市', '100', '0', '3', '300', '俊逸', '16879635143', '1577979751', '1');
INSERT INTO `sales_record` VALUES ('18', '01010007', '1001', 'opWzIv3mGC0yu1g_sJxRLYcHmiXw', '鸭腿', '1号超市', '450', '0', '2', '900', '凤婷', '14749637546', '1577981637', '0');
INSERT INTO `sales_record` VALUES ('19', '01010008', '1001', 'opWzIv882aCPUML4aaqaWu7w1mRA', '盼盼虾条', '1号超市', '500', '0', '1', '500', '恩相随', '14181379134', '1577983483', '0');
INSERT INTO `sales_record` VALUES ('20', '01020011', '1001', 'opWzIv-6mqiXYw7X4Kr9RvJTjJUk', '南德调味料', '1号超市', '450', '0', '1', '450', '风趣', '15896203145', '1577985936', '0');
INSERT INTO `sales_record` VALUES ('21', '01010004', '1001', 'opWzIv3elSV95hebpBnZ2NujjB4o', '苏打水', '1号超市', '300', '0', '1', '300', '听雪', '15378149163', '1577988453', '0');
INSERT INTO `sales_record` VALUES ('22', '01010008', '1001', 'opWzIv7_nlyXcnoozqkJAqIBOaeA', '盼盼虾条', '1号超市', '500', '0', '1', '500', '贾若成', '13678135137', '1577990710', '0');
INSERT INTO `sales_record` VALUES ('23', '01000013', '1001', 'opWzIv-srEWno_7ltpco9EtRQafc', '加多宝', '1号超市', '300', '0', '1', '300', '在风雨中行走', '13256410237', '1577993829', '0');
INSERT INTO `sales_record` VALUES ('24', '01000002', '1001', 'opWzIv7_q5PhQ_PfRueGzSdmjueA', '爽歪歪200g', '1号超市', '200', '0', '4', '800', '沐沐', '17648349134', '1578006370', '1');
INSERT INTO `sales_record` VALUES ('25', '01000012', '1001', 'opWzIv-mTQtDCZHTZAG8_Yg4wa8s', '菠萝啤', '1号超市', '200', '0', '2', '400', '陌生人', '15478956012', '1578008630', '1');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `openid` varchar(255) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mkid` varchar(255) DEFAULT NULL COMMENT '注册超市',
  `rtime` varchar(10) DEFAULT NULL COMMENT '注册时间   10位时间戳',
  PRIMARY KEY (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('opWzIv-d_CX6AU6LbLRfuLbK4-7U', '王瑞', '13248509120', '1001', '1575605493');
INSERT INTO `users` VALUES ('opWzIv-6mqiXYw7X4Kr9RvJTjJUk', '风趣', '15896203145', '1001', '1576851070');
INSERT INTO `users` VALUES ('opWzIv-2erKBFneuy0aWGosEHLs0', '小月', '15041203619', '1001', '1576116970');
INSERT INTO `users` VALUES ('opWzIv-57mw7QHcf4-wAcnlUeVZk', '小乔', '18610237451', '1001', '1575290170');
INSERT INTO `users` VALUES ('opWzIv-8LLADVyr8dSiD4LHmlF38', '豆豆', '15142361020', '1002', '1577086014');
INSERT INTO `users` VALUES ('opWzIv-s9NiplEoBSJ1qAcYrGARQ', '落叶', '15841231023', '1003', '1574205735');
INSERT INTO `users` VALUES ('opWzIv-XxYh7iJsrowVysLWIyeYE', '匆匆', '13889427981', '1003', '1570683133');
INSERT INTO `users` VALUES ('opWzIv30vJ3GAIW1RrRnwDBhdlE0', '俊逸', '16879635143', '1007', '1571206938');
INSERT INTO `users` VALUES ('opWzIv3CunKsVS0lKfYMOYcWc6RM', '归人', '15963454396', '1010', '1571389944');
INSERT INTO `users` VALUES ('opWzIv3elSV95hebpBnZ2NujjB4o', '听雪', '15378149163', '1001', '1558276605');
INSERT INTO `users` VALUES ('opWzIv3Jq8DefO81C_1t3ObsaS1s', '翩翩', '18369967996', '1004', '1564794994');
INSERT INTO `users` VALUES ('opWzIv3mGC0yu1g_sJxRLYcHmiXw', '凤婷', '14749637546', '1006', '1571635365');
INSERT INTO `users` VALUES ('opWzIv7_q5PhQ_PfRueGzSdmjueA', '沐沐', '17648349134', '1001', '1570588181');
INSERT INTO `users` VALUES ('opWzIv7_nlyXcnoozqkJAqIBOaeA', '贾若成', '13678135137', '1002', '1571296848');
INSERT INTO `users` VALUES ('opWzIv882aCPUML4aaqaWu7w1mRA', '恩相随', '14181379134', '1009', '1574120301');
INSERT INTO `users` VALUES ('opWzIv8Axd8fV6TNBBftkRiOov08', '秋风扫落叶', '15873184619', '1010', '1576203147');
INSERT INTO `users` VALUES ('opWzIv8LLAlx3mY7LPHQ5vnDlk0A', '断线的风筝', '13275193194', '1005', '1574522301');
INSERT INTO `users` VALUES ('opWzIv-g7rmOURz2GaksQUSRGJVM', '余生很长', '18678134613', '1004', '1574512036');
INSERT INTO `users` VALUES ('opWzIv-kdD2hr-74zob9bgOFfw98', '海阔天空', '13965421021', '1006', '1578945123');
INSERT INTO `users` VALUES ('opWzIv-mTQtDCZHTZAG8_Yg4wa8s', '陌生人', '15478956012', '1005', '1574568910');
INSERT INTO `users` VALUES ('opWzIv-PjqIMv3LJDzuTUJoPKeSE', '沉默是金', '14589762304', '1001', '1578456236');
INSERT INTO `users` VALUES ('opWzIv-rygHz6WKNlCXy4Uq2_LQw', '没有糖了', '15689457802', '1009', '1574789541');
INSERT INTO `users` VALUES ('opWzIv-srEWno_7ltpco9EtRQafc', '在风雨中行走', '13256410237', '1001', '1578451259');
