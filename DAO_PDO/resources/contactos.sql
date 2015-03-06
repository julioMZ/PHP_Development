/*
MySQL Data Transfer
Source Host: localhost
Source Database: contactos
Target Host: localhost
Target Database: contactos
Date: 26/01/2015 12:46:16 p.m.
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for contactos
-- ----------------------------
DROP TABLE IF EXISTS `contactos`;
CREATE TABLE `contactos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `apellidos` varchar(80) NOT NULL,
  `puesto_departamento` varchar(80) NOT NULL,
  `correo_electronico` varchar(45) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Catálogo de datos base de contactos.';

-- ----------------------------
-- Table structure for contactos_telefonos
-- ----------------------------
DROP TABLE IF EXISTS `contactos_telefonos`;
CREATE TABLE `contactos_telefonos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contacto` int(11) NOT NULL,
  `numeros_telefonicos` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contacto-fk_idx` (`contacto`),
  CONSTRAINT `contactos_telefonos-contacto-fk` FOREIGN KEY (`contacto`) REFERENCES `contactos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Direcctorio de números telefónicos de contactos.\n\nUn contacto puede tener varios teléfonos.';

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `contactos` VALUES ('1', 'Julio Juan Antonio', 'Mora Zamora', 'Gerente de TI&DS', 'julio.mora@t2omedia.com', '1');
INSERT INTO `contactos` VALUES ('2', 'Abner', 'Rojas', 'TI', 'abnereleazar.rojas@t2omedia.com', '0');
INSERT INTO `contactos` VALUES ('3', 'Mariana', 'Garcia Hernandez', 'Odontologia', 'mariana.garcia@gmail.com', '1');
INSERT INTO `contactos` VALUES ('4', 'Julio Adrián', 'Mora García', 'Diversión', 'julioa.mora@gmail.com', '1');
INSERT INTO `contactos_telefonos` VALUES ('1', '1', '{\"oficina\":[{\"numero\":\"40000659\",\"extension\":\"8828\"},{\"numero\":\"50220900\",\"extension\":\"3014\"}],\"celular\":[{\"numero\":\"0445544437471\"}]}');
INSERT INTO `contactos_telefonos` VALUES ('2', '2', '{\"celular\":[{\"numero\":\"0445570809864\"}]}');
INSERT INTO `contactos_telefonos` VALUES ('3', '3', '{\"celular\":[{\"numero\":\"0445543534311\"}],\"oficina\":[{\"numero\":\"50220900\",\"extension\":\"2030\"}]}');
INSERT INTO `contactos_telefonos` VALUES ('4', '4', '{\"celular\":[{\"numero\":\"0445543534311\"}],\"oficina\":[{\"numero\":\"50220900\",\"extension\":\"2030\"}]}');
