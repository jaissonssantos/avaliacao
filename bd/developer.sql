# ************************************************************
# Sequel Pro SQL dump
# Versão 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.35)
# Base de Dados: avaliacao
# Tempo de Geração: 2017-03-23 05:47:35 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump da tabela cidade
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cidade`;

CREATE TABLE `cidade` (
  `id` int(11) NOT NULL,
  `idestado` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cidade_estado_idx` (`idestado`),
  CONSTRAINT `fk_cidade_estado` FOREIGN KEY (`idestado`) REFERENCES `estado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump da tabela cliente
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cliente`;

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `email` varchar(160) NOT NULL,
  `telefone` varchar(14) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump da tabela estabelecimento
# ------------------------------------------------------------

DROP TABLE IF EXISTS `estabelecimento`;

CREATE TABLE `estabelecimento` (
  `id` int(11) NOT NULL,
  `razaosocial` varchar(160) NOT NULL,
  `nomefantasia` varchar(160) DEFAULT NULL,
  `cnpj` varchar(20) NOT NULL,
  `telefone` varchar(45) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `endereco` varchar(60) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `complemento` varchar(40) DEFAULT NULL,
  `bairro` varchar(40) DEFAULT NULL,
  `idcidade` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj_UNIQUE` (`cnpj`),
  KEY `fk_estabelecimento_cidade1_idx` (`idcidade`),
  CONSTRAINT `fk_estabelecimento_cidade1` FOREIGN KEY (`idcidade`) REFERENCES `cidade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump da tabela estabelecimento_cliente
# ------------------------------------------------------------

DROP TABLE IF EXISTS `estabelecimento_cliente`;

CREATE TABLE `estabelecimento_cliente` (
  `id` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idestabelecimento` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_estabelecimento_cliente_cliente1_idx` (`idcliente`),
  KEY `fk_estabelecimento_cliente_estabelecimento1_idx` (`idestabelecimento`),
  CONSTRAINT `fk_estabelecimento_cliente_cliente1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estabelecimento_cliente_estabelecimento1` FOREIGN KEY (`idestabelecimento`) REFERENCES `estabelecimento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump da tabela estado
# ------------------------------------------------------------

DROP TABLE IF EXISTS `estado`;

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `sigla` char(2) NOT NULL,
  `nome` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump da tabela pergunta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pergunta`;

CREATE TABLE `pergunta` (
  `id` int(11) NOT NULL,
  `idquestionario` int(11) NOT NULL,
  `titulo` varchar(60) NOT NULL,
  `tipo` varchar(10) NOT NULL DEFAULT '1' COMMENT '1 - Escolha única\n2 - Multipla escolha',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 - não exibir\n1 - exibir',
  PRIMARY KEY (`id`),
  KEY `fk_resposta_pergunta1_idx` (`idquestionario`),
  CONSTRAINT `fk_resposta_pergunta1` FOREIGN KEY (`idquestionario`) REFERENCES `questionario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump da tabela questionario
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questionario`;

CREATE TABLE `questionario` (
  `id` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `titulo` varchar(120) NOT NULL,
  `introducao` varchar(120) DEFAULT NULL,
  `rodape` varchar(120) DEFAULT NULL,
  `prazo` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 - Ativo\n2 - Inativo\n3- Arquivado\n4 - Finalizado',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `idusuario` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_questionario_usuario1_idx` (`idusuario`),
  CONSTRAINT `fk_questionario_usuario1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump da tabela resposta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `resposta`;

CREATE TABLE `resposta` (
  `id` int(11) NOT NULL,
  `titulo` varchar(60) NOT NULL,
  `idpergunta` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_resposta_pergunta2_idx` (`idpergunta`),
  CONSTRAINT `fk_resposta_pergunta2` FOREIGN KEY (`idpergunta`) REFERENCES `pergunta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump da tabela resposta_cliente
# ------------------------------------------------------------

DROP TABLE IF EXISTS `resposta_cliente`;

CREATE TABLE `resposta_cliente` (
  `id` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idresposta` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_resposta_cliente_cliente1_idx` (`idcliente`),
  KEY `fk_resposta_cliente_resposta1_idx` (`idresposta`),
  CONSTRAINT `fk_resposta_cliente_cliente1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_resposta_cliente_resposta1` FOREIGN KEY (`idresposta`) REFERENCES `resposta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump da tabela usuario
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `idestabelecimento` int(11) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `sobrenome` varchar(40) DEFAULT NULL,
  `email` varchar(160) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` int(11) NOT NULL COMMENT '1 - Usuário comum\n2 - Gestor',
  `gestor` int(11) DEFAULT '0' COMMENT '0 - Não\n1 - Sim',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 - Ativo\n2 - Inativo\n3- Arquivado',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_usuario_estabelecimento1_idx` (`idestabelecimento`),
  CONSTRAINT `fk_usuario_estabelecimento1` FOREIGN KEY (`idestabelecimento`) REFERENCES `estabelecimento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump da tabela usuario_permissao
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usuario_permissao`;

CREATE TABLE `usuario_permissao` (
  `id` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `regra` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usuario_permissao_usuario1_idx` (`idusuario`),
  CONSTRAINT `fk_usuario_permissao_usuario1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
