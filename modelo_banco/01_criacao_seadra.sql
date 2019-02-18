-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema seadra
-- -----------------------------------------------------	
DROP SCHEMA IF EXISTS `seadra` ;

CREATE SCHEMA IF NOT EXISTS `seadra` DEFAULT CHARACTER SET utf8 ; 

USE `seadra` ;

-- -----------------------------------------------------
-- Table `seadra`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`Usuario` (
  `idUsuario` INT NOT NULL AUTO_INCREMENT,
  `nmUsuario` VARCHAR(255) NOT NULL,
  `dsLogin` VARCHAR(20) NOT NULL,
  `dsSenha` VARCHAR(255) NULL,
  `tpGrupo` CHAR(1) NOT NULL DEFAULT 'U' COMMENT '\'U\' = Usuarios, \'A\' = Administradores',
  `stAtivo` CHAR(1) NOT NULL DEFAULT 'S',
  `dtCriacao` DATETIME NOT NULL DEFAULT NOW(),
  `dtModificacao` DATETIME NULL ON UPDATE NOW(),
  PRIMARY KEY (`idUsuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `seadra`.`UnidadeFederativa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`UnidadeFederativa` (
  `idUnidadeFederativa` INT NOT NULL,
  `dsSigla` CHAR(2) NOT NULL,
  `dsNome` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`idUnidadeFederativa`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `form_exemplo`.`municipio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`Municipio` (
  `idMunicipio` INT NOT NULL AUTO_INCREMENT,
  `cdMunicipio` INT NULL,
  `nmMunicipio` VARCHAR(200) NOT NULL,
  `idUnidadeFederativa` INT NOT NULL,
  `stAtivo` CHAR(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`idMunicipio`),
  INDEX `fk_municipio_uf_idx` (`idUnidadeFederativa` ASC),
  CONSTRAINT `fk_municipio_uf`
    FOREIGN KEY (`idUnidadeFederativa`)
    REFERENCES `seadra`.`UnidadeFederativa` (`idUnidadeFederativa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `seadra`.`Endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`Endereco` (
  `idEndereco` INT NOT NULL AUTO_INCREMENT,
  `idCliente` INT NOT NULL,
  `dsCep` VARCHAR(8) NULL,
  `dsLogradouro` VARCHAR(255) NULL,
  `dsComplemento` VARCHAR(255) NULL NULL,
  `dsBairro` VARCHAR(100) NULL,
  `dsLocalidade` VARCHAR(100) NULL,
  `idMunicipio` INT NOT NULL,
  PRIMARY KEY (`idEndereco`),
  CONSTRAINT `fk_Endereco_Municipio`
    FOREIGN KEY (`idMunicipio`)
    REFERENCES `seadra`.`municipio` (`idMunicipio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Endereco_Cliente`
    FOREIGN KEY (`idCliente`)
    REFERENCES `seadra`.`cliente` (`idCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `idCliente_dsCep_UNIQUE` ON `seadra`.`endereco` (`idCliente` ASC, `dsCep` ASC);

CREATE INDEX `fk_Endereco_Municipio_idx` ON `seadra`.`endereco` (`idMunicipio` ASC);

CREATE INDEX `fk_Endereco_Cliente_idx` ON `seadra`.`endereco` (`idCliente` ASC);


-- -----------------------------------------------------
-- Table `seadra`.`Cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`Cliente` (
  `idCliente` INT NOT NULL AUTO_INCREMENT,
  `nmCliente` VARCHAR(255) NOT NULL,
  `nrCpfCnpj` VARCHAR(30) NOT NULL,
  `dsEmail` VARCHAR(100) NULL,
  `nrTelefone` VARCHAR(20) NULL,
  `nrCelular` VARCHAR(20) NULL,
  `stAtivo` CHAR(1) NOT NULL DEFAULT 'S',
  `idUsuarioCriacao` INT NOT NULL,
  `dtCriacao` DATETIME NOT NULL DEFAULT NOW(),
  `idUsuarioModificacao` INT NULL,
  `dtModificacao` DATETIME NULL ON UPDATE NOW(),
  PRIMARY KEY (`idCliente`),
  CONSTRAINT `fk_Cliente_UsuarioCriacao`
    FOREIGN KEY (`idUsuarioCriacao`)
    REFERENCES `seadra`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cliente_UsuarioModificacao`
    FOREIGN KEY (`idUsuarioModificacao`)
    REFERENCES `seadra`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_Cliente_UsuarioCriacao_idx` ON `seadra`.`Cliente` (`idUsuarioCriacao` ASC);

CREATE INDEX `fk_Cliente_UsuarioModificacao_idx` ON `seadra`.`Cliente` (`idUsuarioModificacao` ASC);


-- -----------------------------------------------------
-- Table `seadra`.`Produto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`Produto` (
  `idProduto` INT NOT NULL AUTO_INCREMENT,
  `nmProduto` VARCHAR(255) NOT NULL,
  `vlPrecoCusto` DECIMAL(10,2) NOT NULL,
  `vlPrecoVenda` DECIMAL(10,2) NOT NULL,
  `stAtivo` CHAR(1) NOT NULL DEFAULT 'S',
  `idUsuarioCriacao` INT NOT NULL,
  `dtCriacao` DATETIME NOT NULL DEFAULT NOW(),
  `idUsuarioModificacao` INT NULL,
  `dtModificacao` DATETIME NULL ON UPDATE NOW(),
  PRIMARY KEY (`idProduto`),
  CONSTRAINT `fk_Produto_UsuarioCriacao`
    FOREIGN KEY (`idUsuarioCriacao`)
    REFERENCES `seadra`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Produto_UsuarioModificacao`
    FOREIGN KEY (`idUsuarioModificacao`)
    REFERENCES `seadra`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_Produto_UsuarioCriacao_idx` ON `seadra`.`Produto` (`idUsuarioCriacao` ASC);

CREATE INDEX `fk_Produto_UsuarioModificacao_idx` ON `seadra`.`Produto` (`idUsuarioModificacao` ASC);


-- -----------------------------------------------------
-- Table `seadra`.`Pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`Pedido` (
  `idPedido` INT NOT NULL AUTO_INCREMENT,
  `idCliente` INT NOT NULL,
  `dtPedido` DATE NOT NULL,
  `vlTotal` DECIMAL(10,2) NOT NULL,
  `vlDesconto` DECIMAL(10,2) NULL,
  `vlPago` DECIMAL(10,2) NOT NULL,
  `idUsuarioCriacao` INT NOT NULL,
  `dtCriacao` DATETIME NOT NULL DEFAULT NOW(),
  `idUsuarioModificacao` INT NULL,
  `dtModificacao` DATETIME NULL ON UPDATE NOW(),
  PRIMARY KEY (`idPedido`),
  CONSTRAINT `fk_Pedido_Cliente`
    FOREIGN KEY (`idCliente`)
    REFERENCES `seadra`.`Cliente` (`idCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Pedido_UsuarioCriacao`
    FOREIGN KEY (`idUsuarioCriacao`)
    REFERENCES `seadra`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Pedido_UsuarioModificacao`
    FOREIGN KEY (`idUsuarioModificacao`)
    REFERENCES `seadra`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_Pedido_Cliente_idx` ON `seadra`.`Pedido` (`idCliente` ASC);

CREATE INDEX `fk_Pedido_UsuarioCriacao_idx` ON `seadra`.`Pedido` (`idUsuarioCriacao` ASC);

CREATE INDEX `fk_Pedido_UsuarioModificacao_idx` ON `seadra`.`Pedido` (`idUsuarioModificacao` ASC);


-- -----------------------------------------------------
-- Table `seadra`.`ItemPedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`ItemPedido` (
  `idItemPedido` INT NOT NULL AUTO_INCREMENT,
  `idPedido` INT NOT NULL,
  `idProduto` INT NOT NULL,
  `qtItemPedido` INT NOT NULL,
  PRIMARY KEY (`idItemPedido`),
  CONSTRAINT `fk_ItemPedido_Produto`
    FOREIGN KEY (`idProduto`)
    REFERENCES `seadra`.`Produto` (`idProduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ItemPedido_Pedido`
    FOREIGN KEY (`idPedido`)
    REFERENCES `seadra`.`Pedido` (`idPedido`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_ItemPedido_Produto_idx` ON `seadra`.`ItemPedido` (`idProduto` ASC);

CREATE INDEX `fk_ItemPedido_Pedido_idx` ON `seadra`.`ItemPedido` (`idPedido` ASC);

CREATE UNIQUE INDEX `pedidoProduto_UNIQUE` ON `seadra`.`ItemPedido` (`idPedido` ASC, `idProduto` ASC);


-- -----------------------------------------------------
-- View `seadra`.`vw_municipios`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW vw_municipios AS 
select uf.idunidadefederativa as idunidadefederativa
       ,uf.dssigla as dssigla
       ,m.cdmunicipio as cdmunicipio
	   ,m.nmmunicipio as nmmunicipio 
from municipio m, unidadefederativa uf 
where m.idunidadefederativa = uf.idunidadefederativa;


-- -----------------------------------------------------
-- View `seadra`.`vw_clientes`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `seadra`.`vw_clientes` AS 
select `cli`.`idCliente` AS `idCliente`
	   ,`cli`.`nmCliente` AS `nmCliente`
       ,`cli`.`nrCpfCnpj` AS `nrCpfCnpj`
       ,`cli`.`dsEmail` AS `dsEmail`
       ,`cli`.`nrTelefone` AS `nrTelefone`
       ,`cli`.`nrCelular` AS `nrCelular`
	   ,`cli`.`stAtivo` AS `stAtivo`
       ,`end`.`idEndereco` AS `idEndereco`
       ,`end`.`dsCep` AS `dsCep`
       ,`end`.`dsLogradouro` AS `dsLogradouro`
       ,`end`.`dsComplemento` AS `dsComplemento`
       ,`end`.`dsBairro` AS `dsBairro`
       ,`end`.`dsLocalidade` AS `dsLocalidade`
       ,`mun`.`idMunicipio` AS `idMunicipio`
	   ,`mun`.`cdMunicipio` AS `cdMunicipio`
       ,`mun`.`nmMunicipio` AS `nmMunicipio`
       ,`ufe`.`idUnidadeFederativa` AS `idUnidadeFederativa`
       ,`ufe`.`dsSigla` AS `dsSigla`
       ,`ufe`.`dsNome` AS `dsUnidadeFederativa`
from `seadra`.`cliente` `cli` 
	left join `seadra`.`endereco` `end` on `cli`.`idCliente` = `end`.`idCliente`
    left join `seadra`.`municipio` `mun` on `mun`.`idMunicipio` = `end`.`idMunicipio`
    left join `seadra`.`unidadefederativa` `ufe` on `ufe`.`idUnidadeFederativa` = `mun`.`idUnidadeFederativa`;


-- -----------------------------------------------------
-- Trigger `seadra`.`tg_upd_cliente`
-- -----------------------------------------------------
DROP TRIGGER IF EXISTS `seadra`.`tg_upd_cliente`;

DELIMITER $$
CREATE TRIGGER `seadra`.`tg_upd_cliente` BEFORE UPDATE 
ON `seadra`.`Cliente` FOR EACH ROW
BEGIN
	IF (OLD.`nrCpfCnpj` <> NEW.`nrCpfCnpj`) THEN
		IF EXISTS (SELECT `idCliente` FROM `seadra`.`Cliente` 
				   WHERE `nrCpfCnpj` = NEW.`nrCpfCnpj` AND `stAtivo` = 'S') THEN
			SET NEW.`nrCpfCnpj` = NULL;
		END IF;
	END IF;
END $$
DELIMITER ;


-- -----------------------------------------------------
-- User 
-- -----------------------------------------------------
DROP USER IF EXISTS 'seadra_bd'@'localhost';
CREATE USER 'seadra_bd'@'localhost' IDENTIFIED BY '@seadraadm';
GRANT DELETE,EXECUTE,INSERT,SELECT,UPDATE ON seadra.* TO 'seadra_bd'@'localhost';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
