-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema seadra
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `seadra` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;
USE `seadra` ;

-- -----------------------------------------------------
-- Table `seadra`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`Usuario` (
  `idUsuario` INT NOT NULL AUTO_INCREMENT,
  `nmUsuario` VARCHAR(255) NOT NULL,
  `dsLogin` VARCHAR(20) NOT NULL,
  `dsSenha` VARCHAR(255) NULL,
  `tpGrupo` CHAR(1) NOT NULL DEFAULT 'U' COMMENT '\'U\' = Usuários, \'A\' = Administradores.',
  `stAtivo` CHAR(1) NOT NULL DEFAULT 'S',
  `dtCriacao` DATETIME NOT NULL DEFAULT NOW(),
  `dtModificacao` DATETIME NULL ON UPDATE NOW(),
  PRIMARY KEY (`idUsuario`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `dsLogin_UNIQUE` ON `seadra`.`Usuario` (`dsLogin` ASC);


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
  `dsCep` VARCHAR(8) NOT NULL,
  `dsLogradouro` VARCHAR(255) NOT NULL,
  `dsBairro` VARCHAR(100) NOT NULL,
  `dsLocalidade` VARCHAR(100) NOT NULL,
  `idMunicipio` INT NOT NULL,
  PRIMARY KEY (`idEndereco`),
  CONSTRAINT `fk_Endereco_Municipio`
    FOREIGN KEY (`idMunicipio`)
    REFERENCES `seadra`.`Municipio` (`idMunicipio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `dsCep_UNIQUE` ON `seadra`.`Endereco` (`dsCep` ASC);

CREATE INDEX `fk_Endereco_Municipio_idx` ON `seadra`.`Endereco` (`idMunicipio` ASC);


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
  `idEndereco` INT NULL,
  `dsComplementoEndereco` VARCHAR(255) NULL,
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
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cliente_Endereco`
    FOREIGN KEY (`idEndereco`)
    REFERENCES `seadra`.`Endereco` (`idEndereco`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `cpfCnpj_UNIQUE` ON `seadra`.`Cliente` (`nrCpfCnpj` ASC);

CREATE INDEX `fk_Cliente_UsuarioCriacao_idx` ON `seadra`.`Cliente` (`idUsuarioCriacao` ASC);

CREATE INDEX `fk_Cliente_UsuarioModificacao_idx` ON `seadra`.`Cliente` (`idUsuarioModificacao` ASC);

CREATE INDEX `fk_Cliente_Endereco_idx` ON `seadra`.`Cliente` (`idEndereco` ASC);

CREATE UNIQUE INDEX `dsEmail_UNIQUE` ON `seadra`.`Cliente` (`dsEmail` ASC);


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
CREATE VIEW vw_municipios AS 
select uf.idunidadefederativa as idunidadefederativa
       ,uf.dssigla as dssigla
       ,m.cdmunicipio as cdmunicipio
	     ,m.nmmunicipio as nmmunicipio 
from municipio m, unidadefederativa uf 
where m.idunidadefederativa = uf.idunidadefederativa;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


DROP USER IF EXISTS 'seadra_bd'@'localhost';
CREATE USER 'seadra_bd'@'localhost' IDENTIFIED BY '@seadraadm';
GRANT DELETE,EXECUTE,INSERT,SELECT,UPDATE ON seadra.* TO 'seadra_bd'@'localhost';
