#Agregar referencia a sisaction 
ALTER TABLE `sismenu` ADD FOREIGN KEY (`menuFather`) REFERENCES `sismenu` (`menuId`) ON DELETE RESTRICT ON UPDATE CASCADE;

#Agregar crud de clientes
insert into sismenu VALUES
(null, 'Clientes', 'fa fa-fw fa-users', 'customer', 'index', null);

insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Clientes'),(select actId from sisactions where actDescription = 'Add'));
insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Clientes'),(select actId from sisactions where actDescription = 'Edit'));
insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Clientes'),(select actId from sisactions where actDescription = 'Del'));
insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Clientes'),(select actId from sisactions where actDescription = 'View'));

#Agregar tabla clientes
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `cliId` int(11) NOT NULL AUTO_INCREMENT,
  `cliNombre` varchar(100) NOT NULL,
  `cliApellido` varchar(100) NOT NULL,
  `docId` int(11) DEFAULT NULL,
  `cliDocumento` varchar(14) DEFAULT NULL,
  `cliDomicilio` varchar(255) DEFAULT NULL,
  `cliTelefono` varchar(255) DEFAULT NULL,
  `cliMail` varchar(100) DEFAULT NULL,
  `cliEstado` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`cliId`),
  UNIQUE KEY `docId` (`docId`,`cliDocumento`) USING BTREE,
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`docId`) REFERENCES `tipos_documentos` (`docId`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

#Agregar columna de cliente por defecto
ALTER TABLE `clientes`
ADD COLUMN `cliDefault`  bit(1) NULL DEFAULT 0 AFTER `cliEstado`;

#Agregar cliente por defecto
insert into clientes values (null, 'Consumidor Final', '', 1, '', '', '', '', 'AC', 1);

#Agregar tabla lista de precios 
DROP TABLE IF EXISTS `listadeprecios`;
CREATE TABLE `listadeprecios` (
  `lpId` int(11) NOT NULL AUTO_INCREMENT,
  `lpDescripcion` varchar(50) NOT NULL,
  `lpDefault` bit(1) NOT NULL DEFAULT b'0',
  `lpMargen` decimal(10,2) NOT NULL DEFAULT '0.00',
  `lpEstado` varchar(2) NOT NULL DEFAULT 'AC',
  PRIMARY KEY (`lpId`),
  UNIQUE KEY `lpDescripcion` (`lpDescripcion`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


 #agregar permisos para lista de precios
 insert into sismenu VALUES(null, 'Lista_de_Precios', '', 'lista', 'index', null);
update sismenu set menuFather = 12 Where menuName = 'Lista_de_Precios'; #((Select menuId from sismenu where menuName = 'Administración'))

insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Lista_de_Precios'),(select actId from sisactions where actDescription = 'Add'));
insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Lista_de_Precios'),(select actId from sisactions where actDescription = 'Edit'));
insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Lista_de_Precios'),(select actId from sisactions where actDescription = 'Del'));
insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Lista_de_Precios'),(select actId from sisactions where actDescription = 'View'))

#agregar tabla orden de compra 
DROP TABLE IF EXISTS `ordendecompra`;
CREATE TABLE `ordendecompra` (
  `ocId` int(11) NOT NULL AUTO_INCREMENT,
  `ocObservacion` varchar(50) DEFAULT NULL,
  `ocFecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ocEstado` varchar(2) NOT NULL DEFAULT 'AC',
  `ocEsPresupuesto` bit(1) NOT NULL DEFAULT b'0',
  `usrId` int(11) NOT NULL,
  `lpId` int(11) NOT NULL,
  `cliId` int(11) NOT NULL,
  PRIMARY KEY (`ocId`),
  KEY `usrId` (`usrId`),
  KEY `lpId` (`lpId`),
  KEY `cliId` (`cliId`),
  CONSTRAINT `ordendecompra_ibfk_1` FOREIGN KEY (`usrId`) REFERENCES `sisusers` (`usrId`) ON UPDATE CASCADE,
  CONSTRAINT `ordendecompra_ibfk_2` FOREIGN KEY (`lpId`) REFERENCES `listadeprecios` (`lpId`) ON UPDATE CASCADE,
  CONSTRAINT `ordendecompra_ibfk_3` FOREIGN KEY (`cliId`) REFERENCES `clientes` (`cliId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


#agregar tabla orden de compra detalle
DROP TABLE IF EXISTS `ordendecompradetalle`;
CREATE TABLE `ordendecompradetalle` (
  `ocdId` int(11) NOT NULL AUTO_INCREMENT,
  `ocId` int(11) NOT NULL,
  `artId` int(11) NOT NULL,
  `artDescripcion` varchar(50) NOT NULL,
  `artPCosto` decimal(10,2) NOT NULL,
  `artPVenta` decimal(10,2) NOT NULL,
  `ocdCantidad` decimal(10,2) NOT NULL,
  PRIMARY KEY (`ocdId`),
  KEY `ocId` (`ocId`),
  KEY `artId` (`artId`),
  CONSTRAINT `ordendecompradetalle_ibfk_1` FOREIGN KEY (`ocId`) REFERENCES `ordendecompra` (`ocId`) ON UPDATE CASCADE,
  CONSTRAINT `ordendecompradetalle_ibfk_2` FOREIGN KEY (`artId`) REFERENCES `articles` (`artId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#Agregar crud de clientes
insert into sismenu VALUES
(null, 'Cobranza', 'fa fa-fw fa-dollar', 'sale', 'index', null);

insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Cobranza'),(select actId from sisactions where actDescription = 'Cob'));
insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Cobranza'),(select actId from sisactions where actDescription = 'Anu'));
insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Cobranza'),(select actId from sisactions where actDescription = 'AyC'));

#modificar tabla de orden de compra
ALTER TABLE `ordendecompra`
ADD COLUMN `venId`  int NULL AFTER `cliId`;

ALTER TABLE `ordendecompra` ADD FOREIGN KEY (`venId`) REFERENCES `ventas` (`venId`) ON DELETE RESTRICT ON UPDATE CASCADE;

#modificar tabla ventas
ALTER TABLE `ventas`
MODIFY COLUMN `venFecha`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `venId`;

ALTER TABLE `ventas`
ADD COLUMN `cliId`  int NOT NULL AFTER `cajaId`;

ALTER TABLE `ventas` ADD FOREIGN KEY (`cliId`) REFERENCES `clientes` (`cliId`) ON DELETE RESTRICT ON UPDATE CASCADE;