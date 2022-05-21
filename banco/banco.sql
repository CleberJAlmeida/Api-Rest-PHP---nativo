
CREATE TABLE `corretoras` (
    `id` INT (10) NOT NULL AUTO_INCREMENT,
    `corretora` VARCHAR (50),
    `razao_social` VARCHAR (80),
    `valor_caixa` DECIMAL (10, 2),
    `valor_enviado` DECIMAL (10, 2),
    `valor_retirado` DECIMAL (10, 2),
    `valor_total_acoes` DECIMAL (10, 2),
    `valor_lucro_preju_total` DECIMAL (10, 2),
	`id_usuario` INT (10),
    PRIMARY KEY (`id`),
    UNIQUE INDEX corretoras_id_unique (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8;


CREATE TABLE `usuarios` (
    `id` INT (10) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR (70),
    `login` VARCHAR (100),
    `senha` VARCHAR (64),
    PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8;


CREATE TABLE `carteira` (
    `id` INT (10) NOT NULL AUTO_INCREMENT,
    `id_corretora` INT (10),
    `codigo_acao` VARCHAR (8),
    `nome_acao` VARCHAR (20),
    `quantidade` INT (10),
    `preco_medio` DECIMAL (10, 2),
    `valor_total` DECIMAL (10, 2),
    `lucro_preju` DECIMAL (10, 2),
    PRIMARY KEY (`id`),
    UNIQUE INDEX acoes_id_unique (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8;


CREATE TABLE `movimento_corretora` (
    `id` INT (10) NOT NULL AUTO_INCREMENT,
    `id_corretora` INT (10),
    `tipo` VARCHAR (20),
    `valor` DECIMAL (10, 2),
    `data` VARCHAR (10),
    PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8;


CREATE TABLE `compras` (
    `id` INT (10) NOT NULL AUTO_INCREMENT,
	`id_corretora` INT (10),
    `codigo_acao` VARCHAR (8),
    `quantidade` INT (10),
    `valor_unitario` FLOAT (10, 3),
    `total_compra` FLOAT (10, 3),
	`data` VARCHAR (10),
    PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8;


CREATE TABLE `vendas` (
    `id` INT (10) AUTO_INCREMENT,
	`id_corretora` INT (10),
    `codigo_acao` VARCHAR (8) NOT NULL,
    `quantidade` INT (10),
    `valor_medio` DECIMAL (10, 2),
    `valor_unitario` VARCHAR (100),
    `data` VARCHAR (10),
    PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8;


ALTER TABLE `carteira`
ADD FOREIGN KEY corretoras_acoes_fk (`id_corretora`) REFERENCES `corretoras`(`id`);

ALTER TABLE `movimento_corretoa`
ADD FOREIGN KEY corretoras_movimento__fk (`id_corretora`) REFERENCES `corretoras`(`id`);

ALTER TABLE `compra`
ADD FOREIGN KEY acoes_compra_fk (`codigo_acao`) REFERENCES `carteira`(`codigo_acao`);

ALTER TABLE `vendas`
ADD FOREIGN KEY acoes_vendas_fk (`codigo_acao`) REFERENCES `carteira`(`codigo_acao`);




