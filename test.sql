/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-05-17 16:26:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for compras
-- ----------------------------
DROP TABLE IF EXISTS `compras`;
CREATE TABLE `compras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `num_cantidad` int(11) DEFAULT NULL,
  `num_total` int(11) DEFAULT NULL,
  `fec_compra` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `sn_activo` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of compras
-- ----------------------------
INSERT INTO `compras` VALUES ('2', '1', '1', '4', '1500', '2016-05-17 15:45:51', '1');
INSERT INTO `compras` VALUES ('3', '2', '1', '4', '2000', '2016-05-17 15:45:13', '1');

-- ----------------------------
-- Table structure for productos
-- ----------------------------
DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nb_producto` varchar(255) DEFAULT NULL,
  `desc_producto` varchar(255) DEFAULT NULL,
  `num_precio` varchar(255) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `sn_activo` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of productos
-- ----------------------------
INSERT INTO `productos` VALUES ('1', 'Carne', 'Carne deliciosa', '100', '1', '1');
INSERT INTO `productos` VALUES ('2', 'Sirloin', 'Sirloin Delicioso', '200', '1', '1');
INSERT INTO `productos` VALUES ('19', 'Coñazo', 'Coñazo mayor', '200', '1', '1');
INSERT INTO `productos` VALUES ('20', 'Coñazo marieloso', 'Super coñazo', '200', '2', '1');

-- ----------------------------
-- Table structure for proveedores
-- ----------------------------
DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nb_proveedor` varchar(255) DEFAULT NULL,
  `desc_proveedor` varchar(255) DEFAULT NULL,
  `sn_activo` int(1) DEFAULT NULL,
  `desc_address` varchar(255) DEFAULT NULL,
  `num_telefono` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of proveedores
-- ----------------------------
INSERT INTO `proveedores` VALUES ('1', 'SuKarne', 'SU Karne', '1', 'calle falsa 123 colonia siempre viva', '6677563235');
INSERT INTO `proveedores` VALUES ('2', 'Coppel', 'Tienda negrera', '1', 'Primavera', '654987652');
INSERT INTO `proveedores` VALUES ('7', 'Kike corps', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci soluta, vitae molestias in voluptates sed beatae tempora ut nam culpa voluptas dolore qui error sin', '1', 'Vitae explicabo laudantium, incidunt expedit', '12321321');
INSERT INTO `proveedores` VALUES ('8', 'Agroindustrias del norte', 'agricolas cabrones', '1', 'SuKarneSuKarne', '12321');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nb_rol` varchar(255) DEFAULT NULL,
  `desc_rol` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', 'admin', 'admin');
INSERT INTO `roles` VALUES ('2', 'super user', 'super user');
INSERT INTO `roles` VALUES ('3', 'user', 'user');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nb_user` varchar(255) DEFAULT NULL,
  `nb_lname` varchar(255) DEFAULT NULL,
  `nb_fname` varchar(255) DEFAULT NULL,
  `pw_password` varchar(255) DEFAULT NULL,
  `de_email` varchar(255) DEFAULT NULL,
  `id_rol` varchar(255) DEFAULT NULL,
  `sn_activo` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'vera_borcuta', 'borcuta', 'vera', 'bepa123', 'verina@mail.ru', '1', '1');
INSERT INTO `users` VALUES ('2', 'ashernetz', 'Ibanez', 'Jesus Enrique', 'cacacaca', 'ashernetz@hotmail.com', '1', '1');
INSERT INTO `users` VALUES ('3', 'mimadeishon', 'ibanez', 'mimadeishon', 'mimado123', 'mimado@hotmail.com', '1', '1');
INSERT INTO `users` VALUES ('14', 'caca', 'Bimbo', 'John', 'cacacaca', 'john@bimbo.com', '3', '1');
