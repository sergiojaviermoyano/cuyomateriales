/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : cuyomateriales

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2017-05-09 15:19:06
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
  `artSeFracciona` bit(1) NOT NULL DEFAULT b'0',
  `artMinimo` int(11) DEFAULT '0',
  `artMedio` int(11) DEFAULT '0',
  `artMaximo` int(11) DEFAULT '0',
  `ivaId` int(11) NOT NULL,
  `subrId` int(11) NOT NULL,
  PRIMARY KEY (`artId`),
  UNIQUE KEY `artBarCode` (`artBarCode`) USING BTREE,
  UNIQUE KEY `artDescription` (`artDescription`) USING BTREE,
  KEY `ivaId` (`ivaId`),
  KEY `subrId` (`subrId`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`ivaId`) REFERENCES `ivaalicuotas` (`ivaId`) ON UPDATE CASCADE,
  CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`subrId`) REFERENCES `subrubros` (`subrId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of articles
-- ----------------------------

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
-- Table structure for ivaalicuotas
-- ----------------------------
DROP TABLE IF EXISTS `ivaalicuotas`;
CREATE TABLE `ivaalicuotas` (
  `ivaId` int(11) NOT NULL AUTO_INCREMENT,
  `ivaDescripcion` varchar(20) NOT NULL,
  `ivaPorcentaje` decimal(10,2) NOT NULL,
  `ivaEstado` varchar(2) NOT NULL DEFAULT 'AC',
  `ivaPorDefecto` bigint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ivaId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ivaalicuotas
-- ----------------------------
INSERT INTO `ivaalicuotas` VALUES ('1', 'Exen', '0.00', 'AC', '0');
INSERT INTO `ivaalicuotas` VALUES ('2', 'No Grav', '0.00', 'AC', '0');
INSERT INTO `ivaalicuotas` VALUES ('3', '10,5%', '10.50', 'AC', '0');
INSERT INTO `ivaalicuotas` VALUES ('4', '21%', '21.00', 'AC', '1');
INSERT INTO `ivaalicuotas` VALUES ('5', '27%', '27.00', 'AC', '0');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of receptions
-- ----------------------------

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of receptionsdetail
-- ----------------------------

-- ----------------------------
-- Table structure for rubros
-- ----------------------------
DROP TABLE IF EXISTS `rubros`;
CREATE TABLE `rubros` (
  `rubId` int(11) NOT NULL AUTO_INCREMENT,
  `rubDescripcion` varchar(30) NOT NULL,
  `rubEstado` varchar(2) NOT NULL DEFAULT 'AC',
  PRIMARY KEY (`rubId`),
  UNIQUE KEY `rubDescripcion` (`rubDescripcion`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rubros
-- ----------------------------
INSERT INTO `rubros` VALUES ('1', 'Hierro', 'AC');
INSERT INTO `rubros` VALUES ('2', 'Cementos', 'AC');
INSERT INTO `rubros` VALUES ('3', 'Herramientas', 'AC');
INSERT INTO `rubros` VALUES ('4', 'Baño', 'AC');

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
) ENGINE=InnoDB AUTO_INCREMENT=313 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of sisgroupsactions
-- ----------------------------
INSERT INTO `sisgroupsactions` VALUES ('270', '3', '29');
INSERT INTO `sisgroupsactions` VALUES ('271', '3', '30');
INSERT INTO `sisgroupsactions` VALUES ('272', '3', '31');
INSERT INTO `sisgroupsactions` VALUES ('273', '3', '32');
INSERT INTO `sisgroupsactions` VALUES ('274', '4', '33');
INSERT INTO `sisgroupsactions` VALUES ('275', '2', '1');
INSERT INTO `sisgroupsactions` VALUES ('276', '2', '2');
INSERT INTO `sisgroupsactions` VALUES ('277', '2', '3');
INSERT INTO `sisgroupsactions` VALUES ('278', '2', '4');
INSERT INTO `sisgroupsactions` VALUES ('279', '2', '5');
INSERT INTO `sisgroupsactions` VALUES ('280', '2', '6');
INSERT INTO `sisgroupsactions` VALUES ('281', '2', '7');
INSERT INTO `sisgroupsactions` VALUES ('282', '2', '8');
INSERT INTO `sisgroupsactions` VALUES ('283', '2', '9');
INSERT INTO `sisgroupsactions` VALUES ('284', '2', '10');
INSERT INTO `sisgroupsactions` VALUES ('285', '2', '11');
INSERT INTO `sisgroupsactions` VALUES ('286', '2', '12');
INSERT INTO `sisgroupsactions` VALUES ('287', '2', '21');
INSERT INTO `sisgroupsactions` VALUES ('288', '2', '22');
INSERT INTO `sisgroupsactions` VALUES ('289', '2', '23');
INSERT INTO `sisgroupsactions` VALUES ('290', '2', '24');
INSERT INTO `sisgroupsactions` VALUES ('291', '2', '25');
INSERT INTO `sisgroupsactions` VALUES ('292', '2', '26');
INSERT INTO `sisgroupsactions` VALUES ('293', '2', '28');
INSERT INTO `sisgroupsactions` VALUES ('294', '2', '29');
INSERT INTO `sisgroupsactions` VALUES ('295', '2', '30');
INSERT INTO `sisgroupsactions` VALUES ('296', '2', '31');
INSERT INTO `sisgroupsactions` VALUES ('297', '2', '32');
INSERT INTO `sisgroupsactions` VALUES ('298', '2', '33');
INSERT INTO `sisgroupsactions` VALUES ('299', '2', '34');
INSERT INTO `sisgroupsactions` VALUES ('300', '2', '35');
INSERT INTO `sisgroupsactions` VALUES ('301', '2', '36');
INSERT INTO `sisgroupsactions` VALUES ('302', '2', '37');
INSERT INTO `sisgroupsactions` VALUES ('303', '2', '38');
INSERT INTO `sisgroupsactions` VALUES ('304', '2', '39');
INSERT INTO `sisgroupsactions` VALUES ('305', '2', '40');
INSERT INTO `sisgroupsactions` VALUES ('306', '2', '41');
INSERT INTO `sisgroupsactions` VALUES ('307', '2', '42');
INSERT INTO `sisgroupsactions` VALUES ('308', '2', '43');
INSERT INTO `sisgroupsactions` VALUES ('309', '2', '44');
INSERT INTO `sisgroupsactions` VALUES ('310', '2', '45');
INSERT INTO `sisgroupsactions` VALUES ('311', '2', '46');
INSERT INTO `sisgroupsactions` VALUES ('312', '2', '47');

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
INSERT INTO `sismenu` VALUES ('21', 'Configuración', 'fa fa-fw fa-cogs', '', '', null);
INSERT INTO `sismenu` VALUES ('22', 'Rubros', '', 'rubro', 'index', '21');
INSERT INTO `sismenu` VALUES ('23', 'Subrubros', '', 'rubro', 'indexSR', '21');

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
  KEY `actId` (`actId`),
  CONSTRAINT `sismenuactions_ibfk_1` FOREIGN KEY (`menuId`) REFERENCES `sismenu` (`menuId`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `sismenuactions_ibfk_2` FOREIGN KEY (`actId`) REFERENCES `sisactions` (`actId`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
INSERT INTO `sismenuactions` VALUES ('40', '22', '1');
INSERT INTO `sismenuactions` VALUES ('41', '22', '2');
INSERT INTO `sismenuactions` VALUES ('42', '22', '3');
INSERT INTO `sismenuactions` VALUES ('43', '22', '4');
INSERT INTO `sismenuactions` VALUES ('44', '23', '1');
INSERT INTO `sismenuactions` VALUES ('45', '23', '2');
INSERT INTO `sismenuactions` VALUES ('46', '23', '3');
INSERT INTO `sismenuactions` VALUES ('47', '23', '4');

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
INSERT INTO `sisusers` VALUES ('2', 'admin', 'admin', 'admin', '1', 'e10adc3949ba59abbe56e057f20f883e', '2', '2017-05-09 14:44:56', 'gXXcTWBUD80xznGkbij4ETlXEg0LB6pLiotGaeZrExk4de7GhDqWeqyZWT6FKIeILZyqNKto708Rtdg8qthgTuCH1FxdYVwWDxDbz4d8bgJeJKJ3PxFpXONNQ7jMgVgZGyq1PBPLC4IJ0cR4LxtG8uI5vp7ARhupxAT4UbILcv7sJaZuZRY0uSfJuDSE4ylWGUGNCnjE8iG58Qe0yd2P7PaYjjKJcUmJ2l6T9JpYr2qdRJAsxjrnes9Qgpn9AMJ');
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of stock
-- ----------------------------

-- ----------------------------
-- Table structure for subrubros
-- ----------------------------
DROP TABLE IF EXISTS `subrubros`;
CREATE TABLE `subrubros` (
  `subrId` int(11) NOT NULL AUTO_INCREMENT,
  `subrDescripcion` varchar(30) NOT NULL,
  `rubId` int(11) NOT NULL,
  `subrEstado` varchar(2) NOT NULL DEFAULT 'AC',
  PRIMARY KEY (`subrId`),
  UNIQUE KEY `subrDescripcion` (`subrDescripcion`) USING BTREE,
  KEY `rubId` (`rubId`),
  CONSTRAINT `subrubros_ibfk_1` FOREIGN KEY (`rubId`) REFERENCES `rubros` (`rubId`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of subrubros
-- ----------------------------
INSERT INTO `subrubros` VALUES ('1', 'Pileta', '4', 'AC');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ventas
-- ----------------------------

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ventasdetalle
-- ----------------------------
