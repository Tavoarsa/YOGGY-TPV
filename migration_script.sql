-- ----------------------------------------------------------------------------
-- MySQL Workbench Migration
-- Migrated Schemata: dbVentas
-- Source Schemata: dbVentas1
-- Created: Fri Jun 15 21:15:56 2018
-- Workbench Version: 6.3.9
-- ----------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- Schema dbVentas
-- ----------------------------------------------------------------------------
DROP SCHEMA IF EXISTS `dbVentas` ;
CREATE SCHEMA IF NOT EXISTS `dbVentas` ;

-- ----------------------------------------------------------------------------
-- Table dbVentas.articulo
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`articulo` (
  `idarticulo` INT(11) NOT NULL AUTO_INCREMENT,
  `idcategoria` INT(11) NOT NULL,
  `codigo` VARCHAR(50) NULL DEFAULT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `stock` INT(11) NOT NULL,
  `descripcion` VARCHAR(512) NULL DEFAULT NULL,
  `imagen` VARCHAR(50) NULL DEFAULT NULL,
  `estado` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`idarticulo`),
  INDEX `fk_articulo_categoria_idx` (`idcategoria` ASC),
  CONSTRAINT `fk_articulo_categoria`
    FOREIGN KEY (`idcategoria`)
    REFERENCES `dbVentas`.`categoria` (`idcategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table dbVentas.categoria
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`categoria` (
  `idcategoria` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `descripcion` VARCHAR(256) NULL DEFAULT NULL,
  `condicion` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`idcategoria`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table dbVentas.detalle_ingreso
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`detalle_ingreso` (
  `iddetalle_ingreso` INT(11) NOT NULL AUTO_INCREMENT,
  `idingreso` INT(11) NOT NULL,
  `idarticulo` INT(11) NOT NULL,
  `cantidad` INT(11) NOT NULL,
  `precio_compra` DECIMAL(11,2) NOT NULL,
  `precio_venta` DECIMAL(11,2) NOT NULL,
  PRIMARY KEY (`iddetalle_ingreso`),
  INDEX `fk_detalle_ingreso_idx` (`idingreso` ASC),
  INDEX `fk_detalle_ingreso_articulo_idx` (`idarticulo` ASC),
  CONSTRAINT `fk_detalle_ingreso`
    FOREIGN KEY (`idingreso`)
    REFERENCES `dbVentas`.`ingreso` (`idingreso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalle_ingreso_articulo`
    FOREIGN KEY (`idarticulo`)
    REFERENCES `dbVentas`.`articulo` (`idarticulo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 46
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table dbVentas.detalle_venta
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`detalle_venta` (
  `iddetalle_venta` INT(11) NOT NULL AUTO_INCREMENT,
  `idventa` INT(11) NOT NULL,
  `idarticulo` INT(11) NOT NULL,
  `cantidad` INT(11) NOT NULL,
  `precio_venta` DECIMAL(11,2) NOT NULL,
  `descuento` DECIMAL(11,2) NOT NULL,
  PRIMARY KEY (`iddetalle_venta`),
  INDEX `fk_detalle_venta_articulo_idx` (`idarticulo` ASC),
  INDEX `fk_detalle_venta_venta_idx` (`idventa` ASC),
  CONSTRAINT `fk_detalle_venta_articulo`
    FOREIGN KEY (`idarticulo`)
    REFERENCES `dbVentas`.`articulo` (`idarticulo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalle_venta_venta`
    FOREIGN KEY (`idventa`)
    REFERENCES `dbVentas`.`venta` (`idventa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 216
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table dbVentas.ingreso
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`ingreso` (
  `idingreso` INT(11) NOT NULL AUTO_INCREMENT,
  `idproveedor` INT(11) NOT NULL,
  `tipo_comprobante` VARCHAR(20) NOT NULL,
  `serie_comprobante` VARCHAR(7) NULL DEFAULT NULL,
  `num_comprobante` VARCHAR(10) NOT NULL,
  `fecha_hora` DATETIME NOT NULL,
  `impuesto` DECIMAL(4,2) NOT NULL,
  `estado` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`idingreso`),
  INDEX `fk_ingreso_persona_idx` (`idproveedor` ASC),
  CONSTRAINT `fk_ingreso_persona`
    FOREIGN KEY (`idproveedor`)
    REFERENCES `dbVentas`.`persona` (`idpersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 42
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table dbVentas.invoice
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`invoice` (
  `idinvoice` INT(11) NOT NULL AUTO_INCREMENT,
  `iddetalle_venta` INT(11) NOT NULL,
  `total_venta` DECIMAL(10,0) NULL DEFAULT NULL,
  `pago_efectivo` DECIMAL(10,0) NULL DEFAULT NULL,
  `pago_tarjeta` DECIMAL(10,0) NULL DEFAULT NULL,
  `vuelto` DECIMAL(10,0) NULL DEFAULT NULL,
  `vaucher` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idinvoice`, `iddetalle_venta`),
  INDEX `iddetalle_venta_idx` (`iddetalle_venta` ASC),
  CONSTRAINT `iddetalle_venta`
    FOREIGN KEY (`iddetalle_venta`)
    REFERENCES `dbVentas`.`detalle_venta` (`iddetalle_venta`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 109
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table dbVentas.migrations
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`migrations` (
  `migration` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `batch` INT(11) NOT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table dbVentas.password_resets
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`password_resets` (
  `email` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `token` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `password_resets_email_index` (`email` ASC),
  INDEX `password_resets_token_index` (`token` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table dbVentas.persona
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`persona` (
  `idpersona` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo_persona` VARCHAR(20) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `tipo_documento` VARCHAR(20) NULL DEFAULT NULL,
  `num_documento` VARCHAR(15) NULL DEFAULT NULL,
  `direccion` VARCHAR(70) NULL DEFAULT NULL,
  `telefono` VARCHAR(15) NULL DEFAULT NULL,
  `email` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idpersona`))
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table dbVentas.users
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `email` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `password` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `remember_token` VARCHAR(100) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `users_email_unique` (`email` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table dbVentas.venta
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbVentas`.`venta` (
  `idventa` INT(11) NOT NULL AUTO_INCREMENT,
  `idcliente` INT(11) NOT NULL,
  `tipo_comprobante` VARCHAR(20) NULL DEFAULT NULL,
  `serie_comprobante` VARCHAR(7) NOT NULL,
  `num_comprobante` INT(11) NOT NULL,
  `fecha_hora` DATETIME NOT NULL,
  `impuesto` DECIMAL(4,2) NOT NULL,
  `total_venta` DECIMAL(11,2) NOT NULL,
  `estado` VARCHAR(20) NOT NULL,
  `fecha` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`idventa`),
  INDEX `fk_venta_cliente_idx` (`idcliente` ASC),
  CONSTRAINT `fk_venta_cliente`
    FOREIGN KEY (`idcliente`)
    REFERENCES `dbVentas`.`persona` (`idpersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 188
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Trigger dbVentas.tr_updStockIngreso
-- ----------------------------------------------------------------------------
DELIMITER $$
USE `dbVentas`$$
CREATE DEFINER=`root`@`localhost` TRIGGER tr_updStockIngreso 
AFTER INSERT 
	ON detalle_ingreso FOR EACH ROW 
 BEGIN
	UPDATE articulo SET stock = stock + NEW.cantidad 
    WHERE articulo.idarticulo = NEW.idarticulo;
END;

-- ----------------------------------------------------------------------------
-- Trigger dbVentas.detalle_venta_AFTER_INSERT
-- ----------------------------------------------------------------------------
DELIMITER $$
USE `dbVentas`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `dbVentas1`.`detalle_venta_AFTER_INSERT` 
AFTER INSERT 
ON `detalle_venta` FOR EACH ROW
BEGIN
	UPDATE articulo SET stock = stock - NEW.cantidad 
    WHERE articulo.idarticulo = NEW.idarticulo;
END;

-- ----------------------------------------------------------------------------
-- Trigger dbVentas.ingreso_AFTER_UPDATE
-- ----------------------------------------------------------------------------
DELIMITER $$
USE `dbVentas`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `dbVentas1`.`ingreso_AFTER_UPDATE` 
AFTER UPDATE 
ON `ingreso` FOR EACH ROW
BEGIN
	update articulo 
    join detalle_ingreso 
      on detalle_ingreso.idarticulo = articulo.idarticulo
     and detalle_ingreso.idingreso = new.idingreso
     set articulo.stock = articulo.stock - detalle_ingreso.cantidad;
END;

-- ----------------------------------------------------------------------------
-- Trigger dbVentas.venta_AFTER_UPDATE
-- ----------------------------------------------------------------------------
DELIMITER $$
USE `dbVentas`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `dbVentas1`.`venta_AFTER_UPDATE`
 AFTER UPDATE
 ON `venta` FOR EACH ROW
BEGIN
 update articulo 
    join detalle_venta 
      on detalle_venta.idarticulo = articulo.idarticulo
     and detalle_venta.idventa = new.idventa
     set articulo.stock = articulo.stock + detalle_venta.cantidad;
END;
SET FOREIGN_KEY_CHECKS = 1;
