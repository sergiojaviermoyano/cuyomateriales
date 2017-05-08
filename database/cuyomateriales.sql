/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : cuyomateriales

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2017-05-08 11:12:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `artId` int(11) NOT NULL AUTO_INCREMENT,
  `artBarCode` varchar(50) NOT NULL,
  `artDescription` varchar(50) NOT NULL,
  `artCoste` decimal(14,2) NOT NULL,
  `artMargin` decimal(10,2) NOT NULL,
  `artIsByBox` bit(1) NOT NULL,
  `artCantBox` int(11) DEFAULT NULL,
  `artMarginIsPorcent` bit(1) NOT NULL,
  `artEstado` varchar(2) NOT NULL DEFAULT 'AC',
  PRIMARY KEY (`artId`),
  UNIQUE KEY `artBarCode` (`artBarCode`) USING BTREE,
  UNIQUE KEY `artDescription` (`artDescription`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of articles
-- ----------------------------
INSERT INTO `articles` VALUES ('2', '123456789', 'Alfajor Bagley', '3.50', '1.50', '', '6', '\0', 'SU');
INSERT INTO `articles` VALUES ('3', '50', 'Fotocopias', '0.50', '0.50', '\0', '0', '\0', 'SU');
INSERT INTO `articles` VALUES ('4', '100', 'Semitas', '0.50', '0.25', '\0', '0', '\0', 'AC');
INSERT INTO `articles` VALUES ('5', '132456788', 'Acuarius Pera ', '12.25', '3.75', '\0', '0', '\0', 'AC');
INSERT INTO `articles` VALUES ('6', '789875454', 'Gomitas Mogul', '98.00', '1.04', '', '50', '\0', 'AC');
INSERT INTO `articles` VALUES ('7', '34234234', 'tutuca', '10.00', '50.00', '\0', '0', '', 'AC');
INSERT INTO `articles` VALUES ('8', '77981083454645', 'callia esperado torrontes ', '80.00', '40.00', '\0', '0', '', 'IN');
INSERT INTO `articles` VALUES ('9', '123456789789456', 'Bolsa Cemento 50 Kg', '115.00', '20.00', '\0', '0', '', 'AC');
INSERT INTO `articles` VALUES ('10', '40205', 'Cano 40 20 5', '500.00', '20.00', '\0', '0', '', 'AC');

-- ----------------------------
-- Table structure for cajas
-- ----------------------------
DROP TABLE IF EXISTS `cajas`;
CREATE TABLE `cajas` (
  `cajaId` int(11) NOT NULL AUTO_INCREMENT,
  `cajaApertura` datetime NOT NULL,
  `cajaCierre` datetime DEFAULT NULL,
  `usrId` int(11) DEFAULT NULL,
  `cajaImpApertura` decimal(10,2) NOT NULL,
  `cajaImpVentas` decimal(10,2) DEFAULT NULL,
  `cajaImpRendicion` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`cajaId`),
  KEY `usrId` (`usrId`),
  CONSTRAINT `cajas_ibfk_1` FOREIGN KEY (`usrId`) REFERENCES `sisusers` (`usrId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cajas
-- ----------------------------
INSERT INTO `cajas` VALUES ('5', '2016-05-10 15:36:18', '2016-05-24 15:43:06', '2', '200.32', '4000.52', '5800.00');
INSERT INTO `cajas` VALUES ('6', '2016-05-24 15:44:06', '2016-05-24 17:06:05', '2', '300.00', '200.00', '1000.00');
INSERT INTO `cajas` VALUES ('7', '2016-05-24 17:07:04', '2016-05-24 17:23:43', '2', '20.50', '300.00', '320.00');
INSERT INTO `cajas` VALUES ('8', '2016-05-24 17:29:55', '2016-05-30 12:45:48', '2', '40.00', '100.00', '300.00');
INSERT INTO `cajas` VALUES ('9', '2016-05-30 16:34:40', '2016-05-30 17:27:20', '2', '321.10', '319.75', '500.00');
INSERT INTO `cajas` VALUES ('10', '2016-05-30 17:28:03', '2016-05-30 17:33:02', '2', '30.00', '132.50', '500.00');
INSERT INTO `cajas` VALUES ('11', '2016-05-30 17:33:12', '2016-07-04 16:04:05', '2', '121.00', '96.00', '300.00');
INSERT INTO `cajas` VALUES ('12', '2016-07-11 11:23:42', '2017-01-14 19:36:54', '2', '100.00', '20917.75', '40.00');
INSERT INTO `cajas` VALUES ('13', '2017-01-14 19:37:31', '2017-03-08 17:10:32', '2', '100.00', '67.25', '70.00');
INSERT INTO `cajas` VALUES ('14', '2017-03-08 19:51:23', '2017-04-01 19:39:16', '2', '100.00', '119.50', '80.00');
INSERT INTO `cajas` VALUES ('15', '2017-04-01 19:39:50', '2017-04-24 21:17:38', '2', '500.00', '127.00', '20.00');
INSERT INTO `cajas` VALUES ('16', '2017-04-24 21:20:24', null, '2', '500.00', null, null);

-- ----------------------------
-- Table structure for proveedores
-- ----------------------------
DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE `proveedores` (
  `prvId` int(11) NOT NULL AUTO_INCREMENT,
  `prvNombre` varchar(100) DEFAULT NULL,
  `prvApellido` varchar(100) DEFAULT NULL,
  `prvRazonSocial` varchar(100) DEFAULT NULL,
  `docId` int(11) NOT NULL,
  `prvDocumento` varchar(13) NOT NULL,
  `prvDomicilio` varchar(250) DEFAULT NULL,
  `prvMail` varchar(50) DEFAULT NULL,
  `prvEstado` varchar(2) DEFAULT NULL,
  `prvTelefono` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`prvId`),
  UNIQUE KEY `docId` (`docId`,`prvDocumento`) USING BTREE,
  CONSTRAINT `proveedores_ibfk_1` FOREIGN KEY (`docId`) REFERENCES `tipos_documentos` (`docId`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of proveedores
-- ----------------------------
INSERT INTO `proveedores` VALUES ('1', '', '', 'DEC.SA', '2', '37-12345678-1', 'Diagonal Sarmiento 778', '', 'AC', '0264 4961213');
INSERT INTO `proveedores` VALUES ('2', 'callia', 'callia', 'callia srl', '1', '31324666', '', '', 'AC', '');
INSERT INTO `proveedores` VALUES ('3', 'Loma ', 'Negra', 'Loma Negra SRL', '2', '20-31324208-1', 'B° Área 2 M/E C/23', 'sergio.moyano@outlook.com.ar', 'AC', '0264-155095890');

-- ----------------------------
-- Table structure for receptions
-- ----------------------------
DROP TABLE IF EXISTS `receptions`;
CREATE TABLE `receptions` (
  `recId` int(11) NOT NULL AUTO_INCREMENT,
  `recFecha` datetime NOT NULL,
  `recEstado` varchar(2) NOT NULL,
  `prvId` int(11) NOT NULL,
  `recObservacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`recId`),
  KEY `prvId` (`prvId`),
  CONSTRAINT `receptions_ibfk_1` FOREIGN KEY (`prvId`) REFERENCES `proveedores` (`prvId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of receptions
-- ----------------------------
INSERT INTO `receptions` VALUES ('1', '2016-06-01 12:05:19', 'AC', '1', 'test');
INSERT INTO `receptions` VALUES ('2', '0000-00-00 00:00:00', 'AC', '1', 'test 2');
INSERT INTO `receptions` VALUES ('3', '0000-00-00 00:00:00', 'DS', '1', 'test 2');
INSERT INTO `receptions` VALUES ('4', '2016-06-01 00:00:00', 'AC', '1', 'test 2');
INSERT INTO `receptions` VALUES ('5', '2016-06-01 00:00:00', 'AC', '1', 'test 2');
INSERT INTO `receptions` VALUES ('6', '2016-06-01 00:00:00', 'AC', '1', 'test con productos');
INSERT INTO `receptions` VALUES ('7', '2016-06-01 00:00:00', 'CF', '1', 'test con 2 productos');
INSERT INTO `receptions` VALUES ('8', '2016-06-01 00:00:00', 'CF', '1', 'asdasd');
INSERT INTO `receptions` VALUES ('9', '2016-06-01 00:00:00', 'DS', '1', 'asdasd');
INSERT INTO `receptions` VALUES ('10', '2016-06-01 00:00:00', 'AC', '1', 'asdaasd');
INSERT INTO `receptions` VALUES ('11', '2016-06-01 00:00:00', 'CF', '1', 'asdad asdad');
INSERT INTO `receptions` VALUES ('12', '2016-06-08 00:00:00', 'CF', '1', 'con estado');
INSERT INTO `receptions` VALUES ('13', '2016-07-01 00:00:00', 'CF', '1', 'ajuste de stock por roturas');
INSERT INTO `receptions` VALUES ('14', '2016-07-01 00:00:00', 'CF', '1', 'nada');
INSERT INTO `receptions` VALUES ('15', '2017-01-01 00:00:00', 'CF', '1', 'wertyuhgbjkhhnh');
INSERT INTO `receptions` VALUES ('16', '2017-03-01 00:00:00', 'CF', '1', 'erfwsfws');
INSERT INTO `receptions` VALUES ('17', '2017-03-01 00:00:00', 'CF', '1', 'd sf s fsd f');
INSERT INTO `receptions` VALUES ('18', '2017-03-25 00:00:00', 'CF', '2', 'callia promocion ');
INSERT INTO `receptions` VALUES ('19', '2017-04-01 00:00:00', 'AC', '2', 'e12312 123123');
INSERT INTO `receptions` VALUES ('20', '2017-04-20 00:00:00', 'CF', '3', 'flete san pedro ');

-- ----------------------------
-- Table structure for receptionsdetail
-- ----------------------------
DROP TABLE IF EXISTS `receptionsdetail`;
CREATE TABLE `receptionsdetail` (
  `recdId` int(11) NOT NULL AUTO_INCREMENT,
  `recId` int(11) NOT NULL,
  `artId` int(11) NOT NULL,
  `recdCant` int(11) NOT NULL,
  PRIMARY KEY (`recdId`),
  KEY `recId` (`recId`),
  KEY `artId` (`artId`),
  CONSTRAINT `receptionsdetail_ibfk_1` FOREIGN KEY (`recId`) REFERENCES `receptions` (`recId`) ON UPDATE CASCADE,
  CONSTRAINT `receptionsdetail_ibfk_2` FOREIGN KEY (`artId`) REFERENCES `articles` (`artId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of receptionsdetail
-- ----------------------------
INSERT INTO `receptionsdetail` VALUES ('1', '7', '3', '20');
INSERT INTO `receptionsdetail` VALUES ('2', '7', '4', '1');
INSERT INTO `receptionsdetail` VALUES ('3', '8', '5', '1');
INSERT INTO `receptionsdetail` VALUES ('4', '9', '4', '1');
INSERT INTO `receptionsdetail` VALUES ('5', '10', '3', '1');
INSERT INTO `receptionsdetail` VALUES ('6', '11', '3', '1');
INSERT INTO `receptionsdetail` VALUES ('7', '12', '3', '1');
INSERT INTO `receptionsdetail` VALUES ('8', '12', '4', '1');
INSERT INTO `receptionsdetail` VALUES ('9', '13', '4', '-20');
INSERT INTO `receptionsdetail` VALUES ('10', '14', '4', '1');
INSERT INTO `receptionsdetail` VALUES ('11', '14', '5', '1');
INSERT INTO `receptionsdetail` VALUES ('12', '14', '4', '1');
INSERT INTO `receptionsdetail` VALUES ('13', '15', '5', '200');
INSERT INTO `receptionsdetail` VALUES ('14', '16', '4', '50');
INSERT INTO `receptionsdetail` VALUES ('15', '17', '4', '136');
INSERT INTO `receptionsdetail` VALUES ('16', '17', '4', '1');
INSERT INTO `receptionsdetail` VALUES ('17', '17', '4', '1');
INSERT INTO `receptionsdetail` VALUES ('18', '17', '7', '100');
INSERT INTO `receptionsdetail` VALUES ('19', '18', '8', '1');
INSERT INTO `receptionsdetail` VALUES ('20', '18', '8', '6');
INSERT INTO `receptionsdetail` VALUES ('21', '18', '4', '1');
INSERT INTO `receptionsdetail` VALUES ('22', '19', '4', '1');
INSERT INTO `receptionsdetail` VALUES ('23', '19', '4', '1');
INSERT INTO `receptionsdetail` VALUES ('24', '20', '10', '50');
INSERT INTO `receptionsdetail` VALUES ('25', '20', '4', '150');

-- ----------------------------
-- Table structure for sisactions
-- ----------------------------
DROP TABLE IF EXISTS `sisactions`;
CREATE TABLE `sisactions` (
  `actId` int(11) NOT NULL AUTO_INCREMENT,
  `actDescription` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `actDescriptionSpanish` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`actId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sisactions
-- ----------------------------
INSERT INTO `sisactions` VALUES ('1', 'Add', 'Agregar');
INSERT INTO `sisactions` VALUES ('2', 'Edit', 'Editar');
INSERT INTO `sisactions` VALUES ('3', 'Del', 'Eliminar');
INSERT INTO `sisactions` VALUES ('4', 'View', 'Consultar');
INSERT INTO `sisactions` VALUES ('5', 'Imprimir', 'Imprimir');
INSERT INTO `sisactions` VALUES ('6', 'Saldo', 'Consultar Saldo');
INSERT INTO `sisactions` VALUES ('7', 'Close', 'Cerrar');
INSERT INTO `sisactions` VALUES ('8', 'Box', 'Caja');
INSERT INTO `sisactions` VALUES ('9', 'Conf', 'Confirmar');
INSERT INTO `sisactions` VALUES ('10', 'Disc', 'Descartar');

-- ----------------------------
-- Table structure for sisgroups
-- ----------------------------
DROP TABLE IF EXISTS `sisgroups`;
CREATE TABLE `sisgroups` (
  `grpId` int(11) NOT NULL AUTO_INCREMENT,
  `grpName` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`grpId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sisgroups
-- ----------------------------
INSERT INTO `sisgroups` VALUES ('2', 'Administrador');
INSERT INTO `sisgroups` VALUES ('3', 'Cajero');
INSERT INTO `sisgroups` VALUES ('4', 'Deposito');

-- ----------------------------
-- Table structure for sisgroupsactions
-- ----------------------------
DROP TABLE IF EXISTS `sisgroupsactions`;
CREATE TABLE `sisgroupsactions` (
  `grpactId` int(11) NOT NULL AUTO_INCREMENT,
  `grpId` int(11) NOT NULL,
  `menuAccId` int(11) NOT NULL,
  PRIMARY KEY (`grpactId`),
  KEY `grpId` (`grpId`),
  KEY `menuAccId` (`menuAccId`),
  CONSTRAINT `grpId` FOREIGN KEY (`grpId`) REFERENCES `sisgroups` (`grpId`) ON UPDATE CASCADE,
  CONSTRAINT `menuAccId` FOREIGN KEY (`menuAccId`) REFERENCES `sismenuactions` (`menuAccId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sisgroupsactions
-- ----------------------------
INSERT INTO `sisgroupsactions` VALUES ('240', '2', '1');
INSERT INTO `sisgroupsactions` VALUES ('241', '2', '2');
INSERT INTO `sisgroupsactions` VALUES ('242', '2', '3');
INSERT INTO `sisgroupsactions` VALUES ('243', '2', '4');
INSERT INTO `sisgroupsactions` VALUES ('244', '2', '5');
INSERT INTO `sisgroupsactions` VALUES ('245', '2', '6');
INSERT INTO `sisgroupsactions` VALUES ('246', '2', '7');
INSERT INTO `sisgroupsactions` VALUES ('247', '2', '8');
INSERT INTO `sisgroupsactions` VALUES ('248', '2', '9');
INSERT INTO `sisgroupsactions` VALUES ('249', '2', '10');
INSERT INTO `sisgroupsactions` VALUES ('250', '2', '11');
INSERT INTO `sisgroupsactions` VALUES ('251', '2', '12');
INSERT INTO `sisgroupsactions` VALUES ('252', '2', '21');
INSERT INTO `sisgroupsactions` VALUES ('253', '2', '22');
INSERT INTO `sisgroupsactions` VALUES ('254', '2', '23');
INSERT INTO `sisgroupsactions` VALUES ('255', '2', '24');
INSERT INTO `sisgroupsactions` VALUES ('256', '2', '25');
INSERT INTO `sisgroupsactions` VALUES ('257', '2', '26');
INSERT INTO `sisgroupsactions` VALUES ('258', '2', '28');
INSERT INTO `sisgroupsactions` VALUES ('259', '2', '29');
INSERT INTO `sisgroupsactions` VALUES ('260', '2', '30');
INSERT INTO `sisgroupsactions` VALUES ('261', '2', '31');
INSERT INTO `sisgroupsactions` VALUES ('262', '2', '32');
INSERT INTO `sisgroupsactions` VALUES ('263', '2', '33');
INSERT INTO `sisgroupsactions` VALUES ('264', '2', '34');
INSERT INTO `sisgroupsactions` VALUES ('265', '2', '35');
INSERT INTO `sisgroupsactions` VALUES ('266', '2', '36');
INSERT INTO `sisgroupsactions` VALUES ('267', '2', '37');
INSERT INTO `sisgroupsactions` VALUES ('268', '2', '38');
INSERT INTO `sisgroupsactions` VALUES ('269', '2', '39');
INSERT INTO `sisgroupsactions` VALUES ('270', '3', '29');
INSERT INTO `sisgroupsactions` VALUES ('271', '3', '30');
INSERT INTO `sisgroupsactions` VALUES ('272', '3', '31');
INSERT INTO `sisgroupsactions` VALUES ('273', '3', '32');
INSERT INTO `sisgroupsactions` VALUES ('274', '4', '33');

-- ----------------------------
-- Table structure for sismenu
-- ----------------------------
DROP TABLE IF EXISTS `sismenu`;
CREATE TABLE `sismenu` (
  `menuId` int(11) NOT NULL AUTO_INCREMENT,
  `menuName` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `menuIcon` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `menuController` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `menuView` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `menuFather` int(11) DEFAULT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sismenu
-- ----------------------------
INSERT INTO `sismenu` VALUES ('9', 'Seguridad', 'fa fa-key', '', '', null);
INSERT INTO `sismenu` VALUES ('10', 'Usuarios', '', 'user', 'index', '9');
INSERT INTO `sismenu` VALUES ('11', 'Grupos', '', 'group', 'index', '9');
INSERT INTO `sismenu` VALUES ('12', 'Administración', 'fa fa-fw fa-cogs', '', '', null);
INSERT INTO `sismenu` VALUES ('13', 'Artículos', 'fa fa-cart-plus', 'article', 'index', null);
INSERT INTO `sismenu` VALUES ('16', 'Proveedores', 'fa fa-truck', 'provider', 'index', null);
INSERT INTO `sismenu` VALUES ('17', 'Cajas', 'fa fa-money', 'box', 'index', null);
INSERT INTO `sismenu` VALUES ('18', 'Ventas', 'fa fa-cart-plus', 'sale', 'index', null);
INSERT INTO `sismenu` VALUES ('19', 'Recepción', 'fa fa-fw fa-archive', 'reception', 'index', null);
INSERT INTO `sismenu` VALUES ('20', 'Stock', 'fa fa-fw fa-industry', 'stock', 'index', null);

-- ----------------------------
-- Table structure for sismenuactions
-- ----------------------------
DROP TABLE IF EXISTS `sismenuactions`;
CREATE TABLE `sismenuactions` (
  `menuAccId` int(11) NOT NULL AUTO_INCREMENT,
  `menuId` int(11) NOT NULL,
  `actId` int(11) DEFAULT NULL,
  PRIMARY KEY (`menuAccId`),
  KEY `menuId` (`menuId`),
  CONSTRAINT `sismenuactions_ibfk_1` FOREIGN KEY (`menuId`) REFERENCES `sismenu` (`menuId`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sismenuactions
-- ----------------------------
INSERT INTO `sismenuactions` VALUES ('1', '10', '1');
INSERT INTO `sismenuactions` VALUES ('2', '10', '2');
INSERT INTO `sismenuactions` VALUES ('3', '10', '3');
INSERT INTO `sismenuactions` VALUES ('4', '10', '4');
INSERT INTO `sismenuactions` VALUES ('5', '11', '1');
INSERT INTO `sismenuactions` VALUES ('6', '11', '2');
INSERT INTO `sismenuactions` VALUES ('7', '11', '3');
INSERT INTO `sismenuactions` VALUES ('8', '11', '4');
INSERT INTO `sismenuactions` VALUES ('9', '13', '1');
INSERT INTO `sismenuactions` VALUES ('10', '13', '2');
INSERT INTO `sismenuactions` VALUES ('11', '13', '3');
INSERT INTO `sismenuactions` VALUES ('12', '13', '4');
INSERT INTO `sismenuactions` VALUES ('21', '16', '1');
INSERT INTO `sismenuactions` VALUES ('22', '16', '2');
INSERT INTO `sismenuactions` VALUES ('23', '16', '3');
INSERT INTO `sismenuactions` VALUES ('24', '16', '4');
INSERT INTO `sismenuactions` VALUES ('25', '17', '1');
INSERT INTO `sismenuactions` VALUES ('26', '17', '7');
INSERT INTO `sismenuactions` VALUES ('28', '17', '4');
INSERT INTO `sismenuactions` VALUES ('29', '18', '1');
INSERT INTO `sismenuactions` VALUES ('30', '18', '3');
INSERT INTO `sismenuactions` VALUES ('31', '18', '4');
INSERT INTO `sismenuactions` VALUES ('32', '18', '8');
INSERT INTO `sismenuactions` VALUES ('33', '19', '1');
INSERT INTO `sismenuactions` VALUES ('34', '19', '9');
INSERT INTO `sismenuactions` VALUES ('35', '19', '10');
INSERT INTO `sismenuactions` VALUES ('36', '19', '4');
INSERT INTO `sismenuactions` VALUES ('37', '20', '1');
INSERT INTO `sismenuactions` VALUES ('38', '20', '4');
INSERT INTO `sismenuactions` VALUES ('39', '20', '5');

-- ----------------------------
-- Table structure for sisusers
-- ----------------------------
DROP TABLE IF EXISTS `sisusers`;
CREATE TABLE `sisusers` (
  `usrId` int(11) NOT NULL AUTO_INCREMENT,
  `usrNick` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `usrName` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `usrLastName` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `usrComision` int(11) NOT NULL,
  `usrPassword` varchar(5000) COLLATE utf8_spanish_ci NOT NULL,
  `grpId` int(11) NOT NULL,
  `usrLastAcces` datetime DEFAULT NULL,
  `usrToken` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`usrId`),
  KEY `grpId` (`grpId`),
  CONSTRAINT `sisusers_ibfk_1` FOREIGN KEY (`grpId`) REFERENCES `sisgroups` (`grpId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sisusers
-- ----------------------------
INSERT INTO `sisusers` VALUES ('2', 'admin', 'admin', 'admin', '1', 'e10adc3949ba59abbe56e057f20f883e', '2', '2017-05-06 22:23:06', 'SEk8wHTcqVgTOrJbbG7p8gc2cfoBhhToemboYFmphOWov6g0AGWzvbla2RHmRTFzuApqU7UX4xXxTjxbsO4zRGcWAGDn5vmFiAuwsuDtQRL2MHCbh9k4RmXZ6d6AX9hZpNetuFbfxzKgPtGD7tMjGegE64F7j5G0PO5ul8Xj1AEvu7qjT5lmxdftxgTKH7dCzvkbhDrhhVoYM25WpltAWyTXg8JcfkkjBoU2SkMWZ3G9uCtXz6TA2yJuRCAF0Tm');
INSERT INTO `sisusers` VALUES ('3', 'pepe', 'pepe', 'morales', '1', 'e10adc3949ba59abbe56e057f20f883e', '3', '2017-04-01 20:09:29', 'Vvda5SbXSI0nULm9zzAGOYwRmF2bqcxULKMPJ371lSWJSEYqk88l5KcyAeOpUrYtK0vMtO5Ca5k5QQvB6MqkVQnvxHtAQRbAa34hE75SOAF6hRNFaOscvcqbvgU18eJrS0UwUFfsPky5gMRa8vkZwa9elW3Q2QiRx370LbRwNJp9QgcN6WfVgNMDrzDfwF7QAhF1kmQZWDS5TkKkzmSc7SuMQsSSstaLuNE34mpKnUr3nUt2Y3vheLJHI2mhJH7');

-- ----------------------------
-- Table structure for stock
-- ----------------------------
DROP TABLE IF EXISTS `stock`;
CREATE TABLE `stock` (
  `stkId` int(11) NOT NULL AUTO_INCREMENT,
  `artId` int(11) NOT NULL,
  `stkCant` int(11) NOT NULL,
  `recId` int(11) DEFAULT NULL,
  `stkOrigen` varchar(2) NOT NULL DEFAULT 'RC',
  `stkFecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`stkId`),
  KEY `artId` (`artId`),
  KEY `recId` (`recId`),
  CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`artId`) REFERENCES `articles` (`artId`) ON UPDATE NO ACTION,
  CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`recId`) REFERENCES `receptions` (`recId`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of stock
-- ----------------------------
INSERT INTO `stock` VALUES ('1', '3', '20', '7', 'RC', '2016-07-04 18:03:45');
INSERT INTO `stock` VALUES ('2', '4', '1', '7', 'RC', '2016-07-04 18:03:45');
INSERT INTO `stock` VALUES ('3', '3', '1', '12', 'RC', '2016-07-04 18:03:45');
INSERT INTO `stock` VALUES ('4', '4', '1', '12', 'RC', '2016-07-04 18:03:45');
INSERT INTO `stock` VALUES ('5', '6', '-12', null, 'VN', '2016-07-04 18:03:45');
INSERT INTO `stock` VALUES ('6', '6', '-1', null, 'VN', '2016-07-04 18:03:45');
INSERT INTO `stock` VALUES ('7', '6', '-1', null, 'VN', '2016-07-04 18:03:45');
INSERT INTO `stock` VALUES ('8', '4', '-1', null, 'VN', '2016-07-04 18:03:45');
INSERT INTO `stock` VALUES ('9', '4', '-20', '13', 'RC', '2016-07-04 18:03:45');
INSERT INTO `stock` VALUES ('10', '3', '1', '11', 'RC', '2016-07-04 18:04:09');
INSERT INTO `stock` VALUES ('11', '5', '1', '8', 'RC', '2016-07-06 13:51:21');
INSERT INTO `stock` VALUES ('12', '4', '1', '14', 'RC', '2016-07-06 14:05:44');
INSERT INTO `stock` VALUES ('13', '5', '1', '14', 'RC', '2016-07-06 14:05:45');
INSERT INTO `stock` VALUES ('14', '4', '1', '14', 'RC', '2016-07-06 14:05:45');
INSERT INTO `stock` VALUES ('15', '3', '42', null, 'AJ', '2016-07-07 16:46:59');
INSERT INTO `stock` VALUES ('16', '3', '-50', null, 'AJ', '2016-07-07 16:47:41');
INSERT INTO `stock` VALUES ('17', '3', '1', null, 'AJ', '2016-07-07 16:48:01');
INSERT INTO `stock` VALUES ('18', '3', '1', null, 'AJ', '2016-07-11 11:23:12');
INSERT INTO `stock` VALUES ('19', '4', '-1', null, 'VN', '2016-07-11 11:24:10');
INSERT INTO `stock` VALUES ('20', '5', '-1', null, 'VN', '2016-07-11 11:27:54');
INSERT INTO `stock` VALUES ('21', '3', '-1', null, 'VN', '2016-07-15 11:24:08');
INSERT INTO `stock` VALUES ('22', '5', '-1300', null, 'VN', '2016-07-15 12:37:01');
INSERT INTO `stock` VALUES ('23', '3', '-100', null, 'VN', '2016-07-15 12:37:01');
INSERT INTO `stock` VALUES ('24', '3', '1', null, 'CV', '2016-07-15 14:00:22');
INSERT INTO `stock` VALUES ('25', '3', '1', null, 'CV', '2016-07-15 17:04:16');
INSERT INTO `stock` VALUES ('26', '3', '1', null, 'CV', '2016-07-15 17:05:16');
INSERT INTO `stock` VALUES ('27', '5', '1300', null, 'CV', '2016-07-15 17:05:52');
INSERT INTO `stock` VALUES ('28', '3', '100', null, 'CV', '2016-07-15 17:05:52');
INSERT INTO `stock` VALUES ('29', '4', '100', null, 'AJ', '2016-07-19 18:46:14');
INSERT INTO `stock` VALUES ('30', '5', '-100', null, 'AJ', '2017-01-14 19:09:15');
INSERT INTO `stock` VALUES ('31', '4', '1000', null, 'AJ', '2017-01-14 19:10:09');
INSERT INTO `stock` VALUES ('32', '4', '-82', null, 'AJ', '2017-01-14 19:10:45');
INSERT INTO `stock` VALUES ('33', '5', '200', '15', 'RC', '2017-01-14 19:35:50');
INSERT INTO `stock` VALUES ('34', '4', '-2', null, 'VN', '2017-01-14 19:39:24');
INSERT INTO `stock` VALUES ('35', '5', '-1', null, 'VN', '2017-01-14 19:39:24');
INSERT INTO `stock` VALUES ('36', '5', '-1', null, 'AJ', '2017-01-14 19:45:09');
INSERT INTO `stock` VALUES ('37', '6', '-10', null, 'VN', '2017-03-08 17:10:10');
INSERT INTO `stock` VALUES ('38', '5', '-1', null, 'VN', '2017-03-08 17:10:10');
INSERT INTO `stock` VALUES ('39', '4', '-5', null, 'VN', '2017-03-08 17:10:11');
INSERT INTO `stock` VALUES ('40', '4', '50', '16', 'RC', '2017-03-08 17:11:17');
INSERT INTO `stock` VALUES ('41', '4', '136', '17', 'RC', '2017-03-08 19:49:37');
INSERT INTO `stock` VALUES ('42', '4', '1', '17', 'RC', '2017-03-08 19:49:37');
INSERT INTO `stock` VALUES ('43', '4', '1', '17', 'RC', '2017-03-08 19:49:37');
INSERT INTO `stock` VALUES ('44', '7', '100', '17', 'RC', '2017-03-08 19:49:37');
INSERT INTO `stock` VALUES ('45', '6', '-2', null, 'VN', '2017-03-08 19:52:46');
INSERT INTO `stock` VALUES ('46', '4', '-1', null, 'VN', '2017-03-08 19:52:46');
INSERT INTO `stock` VALUES ('47', '4', '-1', null, 'VN', '2017-03-08 19:54:50');
INSERT INTO `stock` VALUES ('48', '8', '1', '18', 'RC', '2017-04-01 19:16:29');
INSERT INTO `stock` VALUES ('49', '8', '6', '18', 'RC', '2017-04-01 19:16:30');
INSERT INTO `stock` VALUES ('50', '4', '1', '18', 'RC', '2017-04-01 19:16:30');
INSERT INTO `stock` VALUES ('51', '8', '-1', null, 'VN', '2017-04-01 19:34:44');
INSERT INTO `stock` VALUES ('52', '8', '100', null, 'AJ', '2017-04-01 19:36:15');
INSERT INTO `stock` VALUES ('53', '8', '-6', null, 'AJ', '2017-04-01 19:37:35');
INSERT INTO `stock` VALUES ('54', '8', '20', null, 'AJ', '2017-04-01 19:38:34');
INSERT INTO `stock` VALUES ('55', '8', '-1', null, 'VN', '2017-04-01 19:42:27');
INSERT INTO `stock` VALUES ('56', '4', '-20', null, 'VN', '2017-04-01 19:42:27');
INSERT INTO `stock` VALUES ('57', '4', '-160', null, 'AJ', '2017-04-24 20:54:32');
INSERT INTO `stock` VALUES ('58', '10', '50', '20', 'RC', '2017-04-24 21:06:55');
INSERT INTO `stock` VALUES ('59', '4', '150', '20', 'RC', '2017-04-24 21:06:55');
INSERT INTO `stock` VALUES ('60', '10', '-1', null, 'VN', '2017-04-24 21:26:16');
INSERT INTO `stock` VALUES ('61', '9', '-4', null, 'VN', '2017-04-24 21:26:17');

-- ----------------------------
-- Table structure for tipos_documentos
-- ----------------------------
DROP TABLE IF EXISTS `tipos_documentos`;
CREATE TABLE `tipos_documentos` (
  `docId` int(11) NOT NULL AUTO_INCREMENT,
  `docDescripcion` varchar(50) NOT NULL,
  `docTipo` varchar(2) NOT NULL,
  `docEstado` varchar(2) NOT NULL,
  PRIMARY KEY (`docId`),
  UNIQUE KEY `docDescripcion` (`docDescripcion`,`docTipo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tipos_documentos
-- ----------------------------
INSERT INTO `tipos_documentos` VALUES ('1', 'DNI', 'DP', 'AC');
INSERT INTO `tipos_documentos` VALUES ('2', 'CUIT', 'DP', 'AC');
INSERT INTO `tipos_documentos` VALUES ('3', 'LC', 'DP', 'AC');
INSERT INTO `tipos_documentos` VALUES ('4', 'LE', 'DP', 'AC');

-- ----------------------------
-- Table structure for ventas
-- ----------------------------
DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas` (
  `venId` int(11) NOT NULL AUTO_INCREMENT,
  `venFecha` datetime NOT NULL,
  `venEstado` varchar(2) NOT NULL DEFAULT 'AC',
  `usrId` int(11) NOT NULL,
  `cajaId` int(11) NOT NULL,
  PRIMARY KEY (`venId`),
  KEY `usrId` (`usrId`),
  KEY `cajaId` (`cajaId`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`usrId`) REFERENCES `sisusers` (`usrId`) ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cajaId`) REFERENCES `cajas` (`cajaId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ventas
-- ----------------------------
INSERT INTO `ventas` VALUES ('1', '2016-05-24 16:52:56', 'AC', '2', '6');
INSERT INTO `ventas` VALUES ('2', '0000-00-00 00:00:00', 'AC', '2', '8');
INSERT INTO `ventas` VALUES ('3', '0000-00-00 00:00:00', 'AC', '2', '8');
INSERT INTO `ventas` VALUES ('4', '2016-05-27 17:05:08', 'AC', '2', '8');
INSERT INTO `ventas` VALUES ('5', '2016-05-27 17:30:30', 'AC', '2', '8');
INSERT INTO `ventas` VALUES ('6', '2016-05-27 17:31:55', 'AC', '2', '8');
INSERT INTO `ventas` VALUES ('7', '2016-05-30 17:21:40', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('8', '2016-05-30 17:21:40', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('9', '2016-05-30 17:21:40', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('10', '2016-05-30 17:21:41', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('11', '2016-05-30 17:21:41', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('12', '2016-05-30 17:21:41', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('13', '2016-05-30 17:22:43', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('14', '2016-05-30 17:22:43', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('15', '2016-05-30 17:22:43', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('16', '2016-05-30 17:22:43', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('17', '2016-05-30 17:22:43', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('18', '2016-05-30 17:22:44', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('19', '2016-05-30 17:22:44', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('20', '2016-05-30 17:22:44', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('21', '2016-05-30 17:22:44', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('22', '2016-05-30 17:22:44', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('23', '2016-05-30 17:22:45', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('24', '2016-05-30 17:22:45', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('25', '2016-05-30 17:22:45', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('26', '2016-05-30 17:22:45', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('27', '2016-05-30 17:25:35', 'AC', '2', '9');
INSERT INTO `ventas` VALUES ('28', '2016-05-30 17:28:53', 'AC', '2', '10');
INSERT INTO `ventas` VALUES ('29', '2016-05-30 17:32:26', 'AC', '2', '10');
INSERT INTO `ventas` VALUES ('30', '2016-05-30 17:33:34', 'AC', '2', '11');
INSERT INTO `ventas` VALUES ('31', '2016-05-30 17:38:08', 'AC', '2', '11');
INSERT INTO `ventas` VALUES ('32', '2016-05-30 18:46:19', 'AC', '2', '11');
INSERT INTO `ventas` VALUES ('33', '2016-06-08 16:22:33', 'AC', '2', '11');
INSERT INTO `ventas` VALUES ('34', '2016-07-01 18:13:11', 'AC', '2', '11');
INSERT INTO `ventas` VALUES ('35', '2016-07-11 11:24:10', 'AC', '2', '12');
INSERT INTO `ventas` VALUES ('36', '2016-07-11 11:27:54', 'AC', '2', '12');
INSERT INTO `ventas` VALUES ('37', '2016-07-15 11:24:08', 'AN', '2', '12');
INSERT INTO `ventas` VALUES ('38', '2016-07-15 12:37:01', 'AN', '2', '12');
INSERT INTO `ventas` VALUES ('39', '2017-01-14 19:39:24', 'AC', '2', '13');
INSERT INTO `ventas` VALUES ('40', '2017-03-08 17:10:10', 'AC', '2', '13');
INSERT INTO `ventas` VALUES ('41', '2017-03-08 19:52:46', 'AC', '2', '14');
INSERT INTO `ventas` VALUES ('42', '2017-03-08 19:54:50', 'AC', '2', '14');
INSERT INTO `ventas` VALUES ('43', '2017-04-01 19:34:44', 'AC', '2', '14');
INSERT INTO `ventas` VALUES ('44', '2017-04-01 19:42:27', 'AC', '2', '15');
INSERT INTO `ventas` VALUES ('45', '2017-04-24 21:26:16', 'AC', '2', '16');

-- ----------------------------
-- Table structure for ventasdetalle
-- ----------------------------
DROP TABLE IF EXISTS `ventasdetalle`;
CREATE TABLE `ventasdetalle` (
  `vendId` int(11) NOT NULL AUTO_INCREMENT,
  `venId` int(11) NOT NULL,
  `artId` int(11) NOT NULL,
  `artCode` varchar(50) NOT NULL,
  `artDescription` varchar(200) NOT NULL,
  `artCoste` decimal(10,2) NOT NULL,
  `artFinal` decimal(10,2) NOT NULL,
  `venCant` int(11) NOT NULL,
  PRIMARY KEY (`vendId`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ventasdetalle
-- ----------------------------
INSERT INTO `ventasdetalle` VALUES ('1', '6', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('2', '7', '6', '789875454', 'Gomitas Mogul', '98.00', '12.00', '4');
INSERT INTO `ventasdetalle` VALUES ('3', '7', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('4', '8', '6', '789875454', 'Gomitas Mogul', '98.00', '12.00', '4');
INSERT INTO `ventasdetalle` VALUES ('5', '8', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('6', '9', '6', '789875454', 'Gomitas Mogul', '98.00', '12.00', '4');
INSERT INTO `ventasdetalle` VALUES ('7', '9', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('8', '10', '6', '789875454', 'Gomitas Mogul', '98.00', '12.00', '4');
INSERT INTO `ventasdetalle` VALUES ('9', '10', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('10', '11', '6', '789875454', 'Gomitas Mogul', '98.00', '12.00', '4');
INSERT INTO `ventasdetalle` VALUES ('11', '11', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('12', '12', '6', '789875454', 'Gomitas Mogul', '98.00', '12.00', '4');
INSERT INTO `ventasdetalle` VALUES ('13', '12', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('14', '13', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('15', '14', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('16', '15', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('17', '16', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('18', '17', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('19', '18', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('20', '19', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('21', '20', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('22', '21', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('23', '22', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('24', '23', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('25', '24', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('26', '25', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('27', '26', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('28', '27', '5', '132456788', 'Acuarius Pera ', '12.25', '16.00', '1');
INSERT INTO `ventasdetalle` VALUES ('29', '27', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('30', '28', '5', '132456788', 'Acuarius Pera ', '12.25', '32.00', '2');
INSERT INTO `ventasdetalle` VALUES ('31', '28', '6', '789875454', 'Gomitas Mogul', '98.00', '3.00', '1');
INSERT INTO `ventasdetalle` VALUES ('32', '28', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('33', '29', '5', '132456788', 'Acuarius Pera ', '12.25', '16.00', '4');
INSERT INTO `ventasdetalle` VALUES ('34', '29', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('35', '30', '5', '132456788', 'Acuarius Pera ', '12.25', '16.00', '2');
INSERT INTO `ventasdetalle` VALUES ('36', '30', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('37', '31', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('38', '32', '5', '132456788', 'Acuarius Pera ', '12.25', '16.00', '1');
INSERT INTO `ventasdetalle` VALUES ('39', '32', '6', '789875454', 'Gomitas Mogul', '98.00', '3.00', '1');
INSERT INTO `ventasdetalle` VALUES ('40', '32', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('41', '33', '6', '789875454', 'Gomitas Mogul', '98.00', '3.00', '12');
INSERT INTO `ventasdetalle` VALUES ('42', '34', '6', '789875454', 'Gomitas Mogul', '98.00', '3.00', '1');
INSERT INTO `ventasdetalle` VALUES ('43', '34', '6', '789875454', 'Gomitas Mogul', '98.00', '3.00', '1');
INSERT INTO `ventasdetalle` VALUES ('44', '34', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('45', '35', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('46', '36', '5', '132456788', 'Acuarius Pera ', '12.25', '16.00', '1');
INSERT INTO `ventasdetalle` VALUES ('47', '37', '3', '50', 'Fotocopias', '0.50', '1.00', '1');
INSERT INTO `ventasdetalle` VALUES ('48', '38', '5', '132456788', 'Acuarius Pera ', '12.25', '16.00', '1300');
INSERT INTO `ventasdetalle` VALUES ('49', '38', '3', '50', 'Fotocopias', '0.50', '1.00', '100');
INSERT INTO `ventasdetalle` VALUES ('50', '39', '4', '100', 'Semitas', '0.50', '0.75', '2');
INSERT INTO `ventasdetalle` VALUES ('51', '39', '5', '132456788', 'Acuarius Pera ', '12.25', '16.00', '1');
INSERT INTO `ventasdetalle` VALUES ('52', '40', '6', '789875454', 'Gomitas Mogul', '98.00', '3.00', '10');
INSERT INTO `ventasdetalle` VALUES ('53', '40', '5', '132456788', 'Acuarius Pera ', '12.25', '16.00', '1');
INSERT INTO `ventasdetalle` VALUES ('54', '40', '4', '100', 'Semitas', '0.50', '0.75', '5');
INSERT INTO `ventasdetalle` VALUES ('55', '41', '6', '789875454', 'Gomitas Mogul', '98.00', '3.00', '2');
INSERT INTO `ventasdetalle` VALUES ('56', '41', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('57', '42', '4', '100', 'Semitas', '0.50', '0.75', '1');
INSERT INTO `ventasdetalle` VALUES ('58', '43', '8', '77981083454645', 'callia esperado torrontes ', '80.00', '112.00', '1');
INSERT INTO `ventasdetalle` VALUES ('59', '44', '8', '77981083454645', 'callia esperado torrontes ', '80.00', '112.00', '1');
INSERT INTO `ventasdetalle` VALUES ('60', '44', '4', '100', 'Semitas', '0.50', '0.75', '20');
INSERT INTO `ventasdetalle` VALUES ('61', '45', '10', '40205', 'Cano 40 20 5', '500.00', '600.00', '1');
INSERT INTO `ventasdetalle` VALUES ('62', '45', '9', '123456789789456', 'Bolsa Cemento 50 Kg', '115.00', '138.00', '4');
