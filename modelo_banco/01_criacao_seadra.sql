-- -----------------------------------------------------
-- Schema seadra
-- -----------------------------------------------------	
-- DROP SCHEMA IF EXISTS `seadra` ;
-- CREATE SCHEMA IF NOT EXISTS `seadra` DEFAULT CHARACTER SET utf8 ; 

USE `seadra`;

-- -----------------------------------------------------
-- Table `usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` INT NOT NULL AUTO_INCREMENT,
  `nmusuario` VARCHAR(255) NOT NULL,
  `dslogin` VARCHAR(20) NOT NULL,
  `dssenha` VARCHAR(255) NULL,
  `tpgrupo` CHAR(1) NOT NULL DEFAULT 'U' COMMENT 'U=usuarios, A=Administradores',
  `stativo` CHAR(1) NOT NULL DEFAULT 'S',
  `dtcriacao` DATETIME NOT NULL DEFAULT NOW(),
  `dtmodificacao` DATETIME NULL ON UPDATE NOW(),
  PRIMARY KEY (`idusuario`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `uk_dslogin` ON `usuario` (`dslogin` ASC);


-- -----------------------------------------------------
-- Table `unidadefederativa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `unidadefederativa` (
  `idunidadefederativa` INT NOT NULL,
  `dssigla` CHAR(2) NOT NULL,
  `dsnome` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`idunidadefederativa`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `municipio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `municipio` (
  `idmunicipio` INT NOT NULL AUTO_INCREMENT,
  `cdmunicipio` INT NULL,
  `nmmunicipio` VARCHAR(200) NOT NULL,
  `idunidadefederativa` INT NOT NULL,
  `stativo` CHAR(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`idmunicipio`),
  INDEX `fk_municipio_uf_idx` (`idunidadefederativa` ASC),
  CONSTRAINT `fk_municipio_uf`
    FOREIGN KEY (`idunidadefederativa`)
    REFERENCES `unidadefederativa` (`idunidadefederativa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cliente` (
  `idcliente` INT NOT NULL AUTO_INCREMENT,
  `nmcliente` VARCHAR(255) NOT NULL,
  `nrcpfcnpj` VARCHAR(30) NOT NULL,
  `dsemail` VARCHAR(100) NULL,
  `nrtelefone` VARCHAR(20) NULL,
  `nrcelular` VARCHAR(20) NULL,
  `dsobservacao` VARCHAR(2000) NULL,
  `stativo` CHAR(1) NOT NULL DEFAULT 'S',
  `idusuariocriacao` INT NOT NULL,
  `dtcriacao` DATETIME NOT NULL DEFAULT NOW(),
  `idusuariomodificacao` INT NULL,
  `dtmodificacao` DATETIME NULL ON UPDATE NOW(),
  PRIMARY KEY (`idcliente`),
  CONSTRAINT `fk_cliente_usuariocriacao`
    FOREIGN KEY (`idusuariocriacao`)
    REFERENCES `usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cliente_usuariomodificacao`
    FOREIGN KEY (`idusuariomodificacao`)
    REFERENCES `usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_cliente_usuariocriacao_idx` ON `cliente` (`idusuariocriacao` ASC);

CREATE INDEX `fk_cliente_usuariomodificacao_idx` ON `cliente` (`idusuariomodificacao` ASC);

CREATE UNIQUE INDEX `uk_nrcpfcnpj` ON `cliente` (`nrcpfcnpj` ASC);

CREATE UNIQUE INDEX `uk_dsemail` ON `cliente` (`dsemail` ASC);


-- -----------------------------------------------------
-- Table `endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `endereco` (
  `idendereco` INT NOT NULL AUTO_INCREMENT,
  `idcliente` INT NOT NULL,
  `dscep` VARCHAR(8) NULL,
  `dslogradouro` VARCHAR(255) NULL,
  `dscomplemento` VARCHAR(255) NULL NULL,
  `dsbairro` VARCHAR(100) NULL,
  `dslocalidade` VARCHAR(100) NULL,
  `idmunicipio` INT NULL,
  PRIMARY KEY (`idendereco`),
  CONSTRAINT `fk_endereco_municipio`
    FOREIGN KEY (`idmunicipio`)
    REFERENCES `municipio` (`idmunicipio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_endereco_cliente`
    FOREIGN KEY (`idcliente`)
    REFERENCES `cliente` (`idcliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_endereco_municipio_idx` ON `endereco` (`idmunicipio` ASC);

CREATE INDEX `fk_endereco_cliente_idx` ON `endereco` (`idcliente` ASC);

CREATE UNIQUE INDEX `uk_idcliente_dscep` ON `endereco` (`idcliente` ASC, `dscep` ASC);


-- -----------------------------------------------------
-- Table `produto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `produto` (
  `idproduto` INT NOT NULL AUTO_INCREMENT,
  `nmproduto` VARCHAR(255) NOT NULL,
  `dsunidademedida` VARCHAR(4) NOT NULL COMMENT 'Unidade de medida do produto: UN=Unidade, MT=Metro, M2=Metro quadrado, M3=Metro cubico',
  `vlprecocusto` DECIMAL(10,2) NOT NULL,
  `vlprecovenda` DECIMAL(10,2) NOT NULL,
  `stativo` CHAR(1) NOT NULL DEFAULT 'S',
  `idusuariocriacao` INT NOT NULL,
  `dtcriacao` DATETIME NOT NULL DEFAULT NOW(),
  `idusuariomodificacao` INT NULL,
  `dtmodificacao` DATETIME NULL ON UPDATE NOW(),
  PRIMARY KEY (`idproduto`),
  CONSTRAINT `fk_produto_usuariocriacao`
    FOREIGN KEY (`idusuariocriacao`)
    REFERENCES `usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produto_usuariomodificacao`
    FOREIGN KEY (`idusuariomodificacao`)
    REFERENCES `usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_produto_usuariocriacao_idx` ON `produto` (`idusuariocriacao` ASC);

CREATE INDEX `fk_produto_usuariomodificacao_idx` ON `produto` (`idusuariomodificacao` ASC);

CREATE UNIQUE INDEX `uk_nmproduto` ON `produto` (`nmproduto` ASC);


-- -----------------------------------------------------
-- Table `pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pedido` (
  `idpedido` INT NOT NULL AUTO_INCREMENT,
  `idcliente` INT NOT NULL,
  `dtpedido` DATE NOT NULL,
  `idusuariocriacao` INT NOT NULL,
  `dtcriacao` DATETIME NOT NULL DEFAULT NOW(),
  `idusuariomodificacao` INT NULL,
  `dtmodificacao` DATETIME NULL ON UPDATE NOW(),
  PRIMARY KEY (`idpedido`),
  CONSTRAINT `fk_pedido_cliente`
    FOREIGN KEY (`idcliente`)
    REFERENCES `cliente` (`idcliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_usuariocriacao`
    FOREIGN KEY (`idusuariocriacao`)
    REFERENCES `usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_usuariomodificacao`
    FOREIGN KEY (`idusuariomodificacao`)
    REFERENCES `usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_pedido_cliente_idx` ON `pedido` (`idcliente` ASC);

CREATE INDEX `fk_pedido_usuariocriacao_idx` ON `pedido` (`idusuariocriacao` ASC);

CREATE INDEX `fk_pedido_usuariomodificacao_idx` ON `pedido` (`idusuariomodificacao` ASC);

CREATE UNIQUE INDEX `uk_idcliente_dtpedido` ON `pedido` (`idcliente` ASC, `dtpedido` ASC);


-- -----------------------------------------------------
-- Table `itempedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `itempedido` (
  `iditempedido` INT NOT NULL AUTO_INCREMENT,
  `idpedido` INT NOT NULL,
  `idproduto` INT NOT NULL,
  `qtitempedido` DECIMAL(10,2) NOT NULL,
  `vldesconto` DECIMAL(10,2) NULL,
  PRIMARY KEY (`iditempedido`),
  CONSTRAINT `fk_itempedido_produto`
    FOREIGN KEY (`idproduto`)
    REFERENCES `produto` (`idproduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_itempedido_pedido`
    FOREIGN KEY (`idpedido`)
    REFERENCES `pedido` (`idpedido`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_itempedido_produto_idx` ON `itempedido` (`idproduto` ASC);

CREATE INDEX `fk_itempedido_pedido_idx` ON `itempedido` (`idpedido` ASC);

CREATE UNIQUE INDEX `uk_idpedido_idproduto` ON `itempedido` (`idpedido` ASC, `idproduto` ASC);


-- -----------------------------------------------------
-- Table `configuracao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `configuracao` (
  `idconfiguracao` INT NOT NULL AUTO_INCREMENT,
  `dsemitente` VARCHAR(200) NULL,
  `dsenderecoemitente` VARCHAR(400) NULL,
  `dstelefoneemitente` VARCHAR(20) NULL,
  PRIMARY KEY (`idconfiguracao`)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- View `vw_municipio`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `vw_municipio` AS 
SELECT `ufe`.`idunidadefederativa` AS `idunidadefederativa`
       ,`ufe`.`dssigla` AS `dssigla`
       ,`ufe`.`dsnome` AS `dsunidadefederativa`
       ,`mun`.`idmunicipio` AS `idmunicipio`
       ,`mun`.`cdmunicipio` AS `cdmunicipio`
	     ,`mun`.`nmmunicipio` AS `nmmunicipio`
FROM `municipio` `mun`
	INNER JOIN `unidadefederativa` `ufe` ON `mun`.`idunidadefederativa` = `ufe`.`idunidadefederativa`;


-- -----------------------------------------------------
-- View `vw_cliente`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `vw_cliente` AS 
SELECT `cli`.`idcliente` AS `idcliente`
	     ,`cli`.`nmcliente` AS `nmcliente`
       ,`cli`.`nrcpfcnpj` AS `nrcpfcnpj`
       ,`cli`.`dsemail` AS `dsemail`
       ,`cli`.`nrtelefone` AS `nrtelefone`
       ,`cli`.`nrcelular` AS `nrcelular`
       ,`cli`.`dsobservacao` AS `dsobservacao`
	     ,`cli`.`stativo` AS `stativo`
       ,`end`.`idendereco` AS `idendereco`
       ,`end`.`dscep` AS `dscep`
       ,`end`.`dslogradouro` AS `dslogradouro`
       ,`end`.`dscomplemento` AS `dscomplemento`
       ,`end`.`dsbairro` AS `dsbairro`
       ,`end`.`dslocalidade` AS `dslocalidade`
       ,`mun`.`idmunicipio` AS `idmunicipio`
	     ,`mun`.`cdmunicipio` AS `cdmunicipio`
       ,`mun`.`nmmunicipio` AS `nmmunicipio`
       ,`mun`.`idunidadefederativa` AS `idunidadefederativa`
       ,`mun`.`dssigla` AS `dssigla`
       ,`mun`.`dsunidadefederativa` AS `dsunidadefederativa`
FROM `cliente` `cli` 
	LEFT JOIN `endereco` `end` ON `cli`.`idcliente` = `end`.`idcliente`
    LEFT JOIN `vw_municipio` `mun` ON `mun`.`idmunicipio` = `end`.`idmunicipio`;


-- -----------------------------------------------------
-- View `vw_cliente_ativo`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `vw_cliente_ativo` AS 
SELECT `cli`.`idcliente` AS `idcliente`
	   ,`cli`.`nmcliente` AS `nmcliente`
       ,`cli`.`nrcpfcnpj` AS `nrcpfcnpj`
FROM `cliente` `cli` 
WHERE `cli`.`stativo` = 'S';


-- -----------------------------------------------------
-- View `vw_produto_ativo`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `vw_produto_ativo` AS 
SELECT `pro`.`idproduto` AS `idproduto`
	   ,`pro`.`nmproduto` AS `nmproduto`
     ,`pro`.`vlprecovenda` AS `vlprecovenda`
FROM `produto` `pro` 
WHERE `pro`.`stativo` = 'S';


-- -----------------------------------------------------
-- View `vw_pedido_cliente`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `vw_pedido_cliente` AS 
SELECT `ped`.`idpedido` AS `idpedido`
	     ,`cli`.`idcliente` AS `idcliente`
	     ,`cli`.`nmcliente` AS `nmcliente`
       ,`cli`.`nrcpfcnpj` AS `nrcpfcnpj`
       ,`cli`.`stativo` AS `stativo`
       ,`ped`.`dtpedido` AS `dtpedido`
FROM `pedido` `ped`
	INNER JOIN `cliente` `cli` ON `ped`.`idcliente` = `cli`.`idcliente`;		


-- -----------------------------------------------------
-- View `vw_pedido_itens`
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `vw_pedido_itens` AS 
SELECT `cli`.`idcliente` AS `idcliente`
	     ,`cli`.`nmcliente` AS `nmcliente`
       ,`cli`.`nrcpfcnpj` AS `nrcpfcnpj`
       ,`cli`.`nrtelefone` AS `nrtelefone`
       ,`cli`.`nrcelular` AS `nrcelular`
	     ,`cli`.`stativo` AS `stclienteativo`
       ,`cli`.`dscep` AS `dscep`
       ,`cli`.`dslogradouro` AS `dslogradouro`
       ,`cli`.`dscomplemento` AS `dscomplemento`
       ,`cli`.`dsbairro` AS `dsbairro`
       ,`cli`.`nmmunicipio` AS `nmmunicipio`
       ,`cli`.`dssigla` AS `dssigla`
       ,`ped`.`idpedido` AS `idpedido` 
       ,`ped`.`dtpedido` AS `dtpedido`
       ,DATE_FORMAT(`dtpedido`,'%d/%m/%Y') AS `dtpedidoformatada`
       ,`ite`.`iditempedido` AS `iditempedido`
	     ,`ite`.`idproduto` AS `idproduto`
       ,LPAD(`ite`.`idproduto`,5,'0') AS `idprodutoformatado`
	     ,`pro`.`nmproduto` AS `nmproduto`
       ,`pro`.`dsunidademedida` AS `dsunidademedida`
       ,FORMAT(`pro`.`vlprecovenda`,2,'de_DE') AS `vlprecovenda`
       ,`pro`.`stativo` AS `stprodutoativo`
       ,`ite`.`qtitempedido` AS `qtitempedido`
       ,FORMAT(`ite`.`qtitempedido`,2,'de_DE') AS `qtitempedidoformatado`
       ,FORMAT((`pro`.`vlprecovenda` * `ite`.`qtitempedido`),2,'de_DE') AS `vltotalitem` 
       ,IFNULL(`ite`.`vldesconto`, 0) AS `vldesconto`
       ,FORMAT(IFNULL(`ite`.`vldesconto`, 0),2,'de_DE') AS `vldescontoformatado`
       ,FORMAT((`pro`.`vlprecovenda` * `ite`.`qtitempedido`) - IFNULL(`ite`.`vldesconto`, 0),2,'de_DE') AS `vltotalitemcomdesconto` 
       ,FORMAT(`vlr`.`vlpedido`,2,'de_DE') AS `vlpedido`
       ,FORMAT(`vlr`.`vltotaldesconto`,2,'de_DE') AS `vltotaldesconto`
       ,FORMAT(`vlr`.`vltotal`,2,'de_DE') AS `vltotal` 
FROM `pedido` `ped`
	INNER JOIN `vw_cliente` `cli` ON `ped`.`idcliente` = `cli`.`idcliente` 
	LEFT JOIN `itempedido` `ite` ON  `ite`.`idpedido` = `ped`.`idpedido`
	LEFT JOIN `produto` `pro` ON `ite`.`idproduto` = `pro`.`idproduto`
  LEFT JOIN (SELECT `pe`.`idpedido`
						        ,SUM(`pr`.`vlprecovenda` * `it`.`qtitempedido`) AS `vlpedido`
                    ,SUM(`it`.`vldesconto`) AS `vltotaldesconto`
						        ,SUM(`pr`.`vlprecovenda` * `it`.`qtitempedido`) - IFNULL(SUM(`it`.`vldesconto`), 0) AS `vltotal`  
				    FROM `itempedido` `it`
					      INNER JOIN `pedido` `pe` ON `it`.`idpedido` = `pe`.`idpedido`
					      INNER JOIN `produto` `pr` ON `it`.`idproduto` = `pr`.`idproduto`
                WHERE `pr`.`stativo` = 'S'
				    GROUP BY `pe`.`idpedido`) AS `vlr` ON `vlr`.`idpedido` = `ped`.`idpedido`
WHERE `cli`.`stativo` = 'S' AND `pro`.`stativo` = 'S';
