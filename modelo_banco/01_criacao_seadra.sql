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
  `tpGrupo` CHAR(1) NOT NULL DEFAULT 'U' COMMENT 'U=Usuarios, A=Administradores',
  `stAtivo` CHAR(1) NOT NULL DEFAULT 'S',
  `dtCriacao` DATETIME NOT NULL DEFAULT NOW(),
  `dtModificacao` DATETIME NULL ON UPDATE NOW(),
  PRIMARY KEY (`idUsuario`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `uk_dsLogin` ON `seadra`.`usuario` (`dsLogin` ASC);


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
  `idMunicipio` INT NULL,
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

CREATE INDEX `fk_Endereco_Municipio_idx` ON `seadra`.`endereco` (`idMunicipio` ASC);

CREATE INDEX `fk_Endereco_Cliente_idx` ON `seadra`.`endereco` (`idCliente` ASC);

CREATE UNIQUE INDEX `uk_idCliente_dsCep` ON `seadra`.`endereco` (`idCliente` ASC, `dsCep` ASC);


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

CREATE UNIQUE INDEX `uk_nrCpfCnpj` ON `seadra`.`cliente` (`nrCpfCnpj` ASC);

CREATE UNIQUE INDEX `uk_dsEmail` ON `seadra`.`cliente` (`dsEmail` ASC);


-- -----------------------------------------------------
-- Table `seadra`.`Produto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`Produto` (
  `idProduto` INT NOT NULL AUTO_INCREMENT,
  `nmProduto` VARCHAR(255) NOT NULL,
  `dsUnidadeMedida` VARCHAR(4) NOT NULL COMMENT 'Unidade de medida do produto: UN=Unidade, MT=Metro, M2=Metro quadrado, M3=Metro cubico',
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

CREATE UNIQUE INDEX `uk_nmProduto` ON `seadra`.`produto` (`nmProduto` ASC);


-- -----------------------------------------------------
-- Table `seadra`.`Pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `seadra`.`Pedido` (
  `idPedido` INT NOT NULL AUTO_INCREMENT,
  `idCliente` INT NOT NULL,
  `dtPedido` DATE NOT NULL,
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
  `vlDesconto` DECIMAL(10,2) NULL,
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

CREATE UNIQUE INDEX `uk_idPedido_idProduto` ON `seadra`.`itempedido` (`idPedido` ASC, `idProduto` ASC);


-- -----------------------------------------------------
-- View `seadra`.`vw_municipio`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `seadra`.`vw_municipio` AS 
SELECT `ufe`.`idUnidadeFederativa` AS `idUnidadeFederativa`
       ,`ufe`.`dsSigla` AS `dsSigla`
       ,`ufe`.`dsNome` AS `dsUnidadeFederativa`
       ,`mun`.`idMunicipio` AS `idMunicipio`
       ,`mun`.`cdMunicipio` AS `cdMunicipio`
	     ,`mun`.`nmMunicipio` AS `nmMunicipio`
FROM `seadra`.`municipio` `mun`
	INNER JOIN `seadra`.`unidadefederativa` `ufe` ON `mun`.`idUnidadeFederativa` = `ufe`.`idUnidadeFederativa`;


-- -----------------------------------------------------
-- View `seadra`.`vw_cliente`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `seadra`.`vw_cliente` AS 
SELECT `cli`.`idCliente` AS `idCliente`
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
       ,`mun`.`idUnidadeFederativa` AS `idUnidadeFederativa`
       ,`mun`.`dsSigla` AS `dsSigla`
       ,`mun`.`dsUnidadeFederativa` AS `dsUnidadeFederativa`
FROM `seadra`.`cliente` `cli` 
	LEFT JOIN `seadra`.`endereco` `end` ON `cli`.`idCliente` = `end`.`idCliente`
    LEFT JOIN `seadra`.`vw_municipio` `mun` ON `mun`.`idMunicipio` = `end`.`idMunicipio`;


-- -----------------------------------------------------
-- View `seadra`.`vw_cliente_ativo`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `seadra`.`vw_cliente_ativo` AS 
SELECT `cli`.`idCliente` AS `idCliente`
	   ,`cli`.`nmCliente` AS `nmCliente`
       ,`cli`.`nrCpfCnpj` AS `nrCpfCnpj`
FROM `seadra`.`cliente` `cli` 
WHERE `cli`.`stAtivo` = 'S';


-- -----------------------------------------------------
-- View `seadra`.`vw_produto_ativo`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `seadra`.`vw_produto_ativo` AS 
SELECT `pro`.`idProduto` AS `idProduto`
	   ,`pro`.`nmProduto` AS `nmProduto`
     ,`pro`.`vlPrecoVenda` AS `vlPrecoVenda`
FROM `seadra`.`produto` `pro` 
WHERE `pro`.`stAtivo` = 'S';


-- -----------------------------------------------------
-- View `seadra`.`vw_pedido_cliente`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `seadra`.`vw_pedido_cliente` AS 
SELECT `ped`.`idPedido` AS `idPedido`
	     ,`cli`.`idCliente` AS `idCliente`
	     ,`cli`.`nmCliente` AS `nmCliente`
       ,`cli`.`nrCpfCnpj` AS `nrCpfCnpj`
       ,`cli`.`stAtivo` AS `stAtivo`
       ,`ped`.`dtPedido` AS `dtPedido`
FROM `seadra`.`pedido` `ped`
	INNER JOIN `seadra`.`cliente` `cli` ON `ped`.`idCliente` = `cli`.`idCliente`;		


-- -----------------------------------------------------
-- View `seadra`.`vw_pedido_itens`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `seadra`.`vw_pedido_itens` AS 
SELECT `cli`.`idCliente` AS `idCliente`
	     ,`cli`.`nmCliente` AS `nmCliente`
       ,`cli`.`nrCpfCnpj` AS `nrCpfCnpj`
       ,`cli`.`nrTelefone` AS `nrTelefone`
       ,`cli`.`nrCelular` AS `nrCelular`
	     ,`cli`.`stAtivo` AS `stClienteAtivo`
       ,`cli`.`dsCep` AS `dsCep`
       ,`cli`.`dsLogradouro` AS `dsLogradouro`
       ,`cli`.`dsComplemento` AS `dsComplemento`
       ,`cli`.`dsBairro` AS `dsBairro`
       ,`cli`.`nmMunicipio` AS `nmMunicipio`
       ,`cli`.`dsSigla` AS `dsSigla`
       ,`ped`.`idPedido` AS `idPedido` 
       ,`ped`.`dtPedido` AS `dtPedido`
       ,DATE_FORMAT(`dtPedido`,\'%d/%m/%Y\') AS `dtPedidoFormatada`
       ,`ite`.`idItemPedido` AS `idItemPedido`
	     ,`ite`.`idProduto` AS `idProduto`
       ,LPAD(`ite`.`idProduto`,5,'0') AS `idProdutoFormatado`
	     ,`pro`.`nmProduto` AS `nmProduto`
       ,`pro`.`dsUnidadeMedida` AS `dsUnidadeMedida`
       ,FORMAT(`pro`.`vlPrecoVenda`,2,\'de_DE\') AS `vlPrecoVenda`
       ,`pro`.`stAtivo` AS `stProdutoAtivo`
       ,`ite`.`qtItemPedido` AS `qtItemPedido`
       ,FORMAT(IFNULL(`ite`.`vlDesconto`, 0),2,\'de_DE\') AS `vlDesconto`
       ,FORMAT((`pro`.`vlPrecoVenda` * `ite`.`qtItemPedido`) - IFNULL(`ite`.`vlDesconto`, 0),2,\'de_DE\') AS `vlTotalItem` 
       ,FORMAT(`vlr`.`vlPedido`,2,\'de_DE\') AS `vlPedido`
       ,FORMAT(`vlr`.`vlTotalDesconto`,2,\'de_DE\') AS `vlTotalDesconto`
       ,FORMAT(`vlr`.`vlTotal`,2,\'de_DE\') AS `vlTotal` 
FROM `seadra`.`pedido` `ped`
	INNER JOIN `seadra`.`vw_cliente` `cli` ON `ped`.`idCliente` = `cli`.`idCliente` 
	LEFT JOIN `seadra`.`itempedido` `ite` ON  `ite`.`idPedido` = `ped`.`idPedido`
	LEFT JOIN `seadra`.`produto` `pro` ON `ite`.`idProduto` = `pro`.`idProduto`
  LEFT JOIN (SELECT `pe`.`idPedido`
						        ,SUM(`pr`.`vlPrecoVenda` * `it`.`qtItemPedido`) AS `vlPedido`
                    ,SUM(`it`.`vlDesconto`) AS `vlTotalDesconto`
						        ,SUM(`pr`.`vlPrecoVenda` * `it`.`qtItemPedido`) - IFNULL(SUM(`it`.`vlDesconto`), 0) AS `vlTotal`  
				    FROM `seadra`.`itempedido` `it`
					      INNER JOIN `seadra`.`pedido` `pe` ON `it`.`idPedido` = `pe`.`idPedido`
					      INNER JOIN `seadra`.`produto` `pr` ON `it`.`idProduto` = `pr`.`idProduto`
				    GROUP BY `pe`.`idPedido`) AS `vlr` ON `vlr`.`idPedido` = `ped`.`idPedido`
WHERE `cli`.`stAtivo` = 'S' AND `pro`.`stAtivo` = 'S' ;



DROP USER IF EXISTS 'seadra_bd'@'localhost';
CREATE USER 'seadra_bd'@'localhost' IDENTIFIED BY '!s3@dr@19';
GRANT DELETE,EXECUTE,INSERT,SELECT,UPDATE ON seadra.* TO 'seadra_bd'@'localhost';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
