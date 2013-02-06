-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Feb 06, 2013 alle 15:22
-- Versione del server: 5.5.29
-- Versione PHP: 5.3.10-1ubuntu3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cmr`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Agenti`
--

CREATE TABLE IF NOT EXISTS `Agenti` (
  `idAgenti` int(11) NOT NULL AUTO_INCREMENT,
  `AgenteNome` varchar(45) DEFAULT NULL,
  `AgenteCognome` varchar(45) DEFAULT NULL,
  `AgenteMail` varchar(50) DEFAULT NULL,
  `AgenteUser` varchar(45) DEFAULT NULL,
  `AgentePass` varchar(45) DEFAULT NULL,
  `AgenteAbilitato` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idAgenti`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabella Agenti';

-- --------------------------------------------------------

--
-- Struttura della tabella `Agenti_Clienti_Contratti`
--

CREATE TABLE IF NOT EXISTS `Agenti_Clienti_Contratti` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `AgenteId` int(11) NOT NULL,
  `ContrattoId` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_Agenti_idx` (`AgenteId`),
  KEY `fk_Contratti_idx` (`ContrattoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `Clienti`
--

CREATE TABLE IF NOT EXISTS `Clienti` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `ClienteNome` varchar(45) DEFAULT NULL,
  `ClienteCognome` varchar(45) DEFAULT NULL,
  `ClienteRagione` varchar(45) DEFAULT NULL,
  `ClienteCF` varchar(45) DEFAULT NULL,
  `ClientePI` varchar(45) DEFAULT NULL,
  `ClienteMail` varchar(45) DEFAULT NULL,
  `ClienteIndirizzo1` varchar(45) DEFAULT NULL,
  `ClienteNumero1` varchar(45) DEFAULT NULL,
  `ClienteCap1` varchar(45) DEFAULT NULL,
  `ClienteCitta1` varchar(45) DEFAULT NULL COMMENT '	',
  `ClienteIndirizzo2` varchar(45) DEFAULT NULL,
  `ClienteNumero2` varchar(45) DEFAULT NULL,
  `ClienteCap2` varchar(45) DEFAULT NULL,
  `ClienteCitta2` varchar(45) DEFAULT NULL,
  `ClienteIndirizzo3` varchar(45) DEFAULT NULL,
  `ClienteNumero3` varchar(45) DEFAULT NULL,
  `ClienteCap3` varchar(45) DEFAULT NULL,
  `ClienteCitta3` varchar(45) DEFAULT NULL,
  `ClienteTipologia` varchar(25) NOT NULL,
  `Agenti_idAgenti` int(11) NOT NULL,
  PRIMARY KEY (`idCliente`),
  KEY `fk_Clienti_Agenti_idx` (`Agenti_idAgenti`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `Contratti`
--

CREATE TABLE IF NOT EXISTS `Contratti` (
  `ContrattoId` int(11) NOT NULL AUTO_INCREMENT,
  `ContrattoNome` varchar(50) NOT NULL,
  `ContrattoTipo` varchar(50) NOT NULL,
  `ContrattoStato` varchar(25) NOT NULL,
  `ContrattoFatturato` int(1) NOT NULL,
  `Clienti_idCliente` int(11) NOT NULL,
  PRIMARY KEY (`ContrattoId`),
  KEY `fk_Contratti_Clienti1_idx` (`Clienti_idCliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Clienti`
--
ALTER TABLE `Clienti`
  ADD CONSTRAINT `fk_Clienti_Agenti` FOREIGN KEY (`Agenti_idAgenti`) REFERENCES `Agenti` (`idAgenti`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `Contratti`
--
ALTER TABLE `Contratti`
  ADD CONSTRAINT `fk_Contratti_Clienti1` FOREIGN KEY (`Clienti_idCliente`) REFERENCES `Clienti` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
