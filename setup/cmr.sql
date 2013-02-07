-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Feb 07, 2013 alle 12:18
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabella Agenti' AUTO_INCREMENT=2 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

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
  `ClienteSesso` varchar(1) DEFAULT NULL COMMENT 'Sesso',
  `ClienteDataNascita` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Data di Nascita',
  `ClienteLuogoNascita` varchar(100) NOT NULL COMMENT 'Luogo di Nascita',
  `ClienteProvinciaNascita` varchar(2) NOT NULL COMMENT 'Provincia di Nascita',
  `ClienteTipoDocumento` varchar(50) NOT NULL COMMENT 'TIpo di Documento',
  `ClienteNumeroDocumento` varchar(10) NOT NULL COMMENT 'Numero Documento',
  `ClienteEnteDocumento` varchar(50) NOT NULL COMMENT 'Ente che ha rilasciato il Documento',
  `ClienteRilascioDocumento` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Data Rilascio Documento',
  `ClienteTelefono` varchar(20) DEFAULT NULL COMMENT 'Telefono Fisso',
  `ClienteFax` varchar(20) DEFAULT NULL COMMENT 'Fax',
  `ClienteCellulare` varchar(20) DEFAULT NULL COMMENT 'Cellulare',
  `ClienteMail` varchar(45) DEFAULT NULL,
  `ClienteIndirizzo` varchar(45) DEFAULT NULL,
  `ClienteNumero` varchar(45) DEFAULT NULL,
  `ClienteCap` varchar(45) DEFAULT NULL,
  `ClienteCitta` varchar(45) DEFAULT NULL COMMENT '	',
  `ClienteTipologia` varchar(25) NOT NULL,
  `Agenti_idAgenti` int(11) NOT NULL,
  PRIMARY KEY (`idCliente`),
  KEY `fk_Clienti_Agenti_idx` (`Agenti_idAgenti`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `Contratti`
--

CREATE TABLE IF NOT EXISTS `Contratti` (
  `ContrattoId` int(11) NOT NULL AUTO_INCREMENT,
  `ContrattoNome` varchar(50) NOT NULL,
  `ContrattoTipo` varchar(50) NOT NULL COMMENT 'Valori "Privato" - "Azienda"',
  `ContrattoStato` varchar(25) NOT NULL COMMENT 'Stato Contratto "Inserito - Lavorazione - Attivato - Rifiutato"',
  `ContrattoFatturato` int(1) NOT NULL COMMENT 'Valorizzato a 0 se ancora non emessa fattura altrimenti valorizzato a 1',
  `ContrattoPagato` int(1) NOT NULL COMMENT 'Fattura Pagata (valorizzato a 0 non pagata - 1 pagata)',
  `ContrattoIndirizzo1` varchar(75) DEFAULT NULL COMMENT 'Indirizzo installazione',
  `ContrattoNumero1` int(4) DEFAULT NULL COMMENT 'Civico installazione',
  `ContrattoCap1` int(5) DEFAULT NULL COMMENT 'CAP installazione',
  `ContrattoCitta1` varchar(50) DEFAULT NULL COMMENT 'Citta installazione',
  `ContrattoIndirizzo2` varchar(75) DEFAULT NULL COMMENT 'Indirizzo invio corrispondenza',
  `ContrattoNumero2` int(4) DEFAULT NULL COMMENT 'Civico invio corrispondenza',
  `ContrattoCap2` int(5) DEFAULT NULL COMMENT 'CAP invio corrispondenza',
  `ContrattoCitta2` varchar(50) DEFAULT NULL COMMENT 'Citta invio corrispondenza',
  `Clienti_idCliente` int(11) NOT NULL,
  PRIMARY KEY (`ContrattoId`),
  KEY `fk_Contratti_Clienti1_idx` (`Clienti_idCliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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
