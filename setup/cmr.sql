-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Feb 20, 2013 alle 09:38
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
-- Struttura della tabella `Admin`
--

CREATE TABLE IF NOT EXISTS `Admin` (
  `AdminId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AdminUser` varchar(50) NOT NULL,
  `AdminPass` varchar(50) NOT NULL,
  `AdminMail` varchar(50) NOT NULL,
  PRIMARY KEY (`AdminId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabella gestione Utenti Administrator' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `Agenti`
--

CREATE TABLE IF NOT EXISTS `Agenti` (
  `idAgenti` int(11) NOT NULL AUTO_INCREMENT,
  `AgenteNome` varchar(45) DEFAULT NULL,
  `AgenteCognome` varchar(45) DEFAULT NULL,
  `AgenteTelefono` varchar(50) NOT NULL,
  `AgenteFax` varchar(50) NOT NULL,
  `AgenteCellulare` varchar(50) NOT NULL,
  `AgenteMail` varchar(50) DEFAULT NULL,
  `AgenteIndirizzo` varchar(100) NOT NULL,
  `AgenteNumero` varchar(10) NOT NULL,
  `AgenteCap` varchar(5) NOT NULL,
  `AgenteCitta` varchar(100) NOT NULL,
  `AgenteUser` varchar(45) DEFAULT NULL,
  `AgentePass` varchar(45) DEFAULT NULL,
  `AgenteAbilitato` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`idAgenti`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabella Agenti' AUTO_INCREMENT=4 ;

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
  `ClienteDataNascita` date NOT NULL COMMENT 'Data di Nascita',
  `ClienteLuogoNascita` varchar(100) NOT NULL COMMENT 'Luogo di Nascita',
  `ClienteProvinciaNascita` varchar(2) NOT NULL COMMENT 'Provincia di Nascita',
  `ClienteTipoDocumento` varchar(50) NOT NULL COMMENT 'TIpo di Documento',
  `ClienteNumeroDocumento` varchar(10) NOT NULL COMMENT 'Numero Documento',
  `ClienteEnteDocumento` varchar(50) NOT NULL COMMENT 'Ente che ha rilasciato il Documento',
  `ClienteRilascioDocumento` date NOT NULL COMMENT 'Data Rilascio Documento',
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

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
  `ContrattoPagamento` varchar(50) NOT NULL COMMENT 'Metodo di Pagamento Bimestrale "Bollettino Postale" - "RID" - "MAV"',
  `ContrattoAttivazione` varchar(50) NOT NULL COMMENT 'U.T. Attivazione : "Contanti" - "Addebito"',
  `ContrattoFattura` varchar(50) NOT NULL COMMENT 'Ricezione Fattura: "Cartacea" - "Digitale"',
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

-- --------------------------------------------------------

--
-- Struttura della tabella `Offerte`
--

CREATE TABLE IF NOT EXISTS `Offerte` (
  `OffertaId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `OffertaNome` varchar(50) NOT NULL COMMENT 'Nome Offerta',
  `OffertaCanone` decimal(5,2) NOT NULL COMMENT 'Costo Mensile',
  `OffertaPagamento` varchar(50) NOT NULL COMMENT 'Valori "Mensile" - "Bimestrale"',
  `OffertaDescrizione` text NOT NULL COMMENT 'Descrizione dettagliata offerta',
  `OffertaDestinazione` varchar(50) NOT NULL COMMENT 'Valori "Privato" - "Azienda"',
  `TipologiaId` int(11) NOT NULL COMMENT 'Indice Tipologia',
  PRIMARY KEY (`OffertaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabella Offerte' AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `Tipologie`
--

CREATE TABLE IF NOT EXISTS `Tipologie` (
  `TipologiaId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TipologiaNome` varchar(50) NOT NULL COMMENT 'Nome Tipologie Offerte Disponibili',
  PRIMARY KEY (`TipologiaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabella gestione Tipologie Offerte' AUTO_INCREMENT=8 ;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Clienti`
--
ALTER TABLE `Clienti`
  ADD CONSTRAINT `Clienti_ibfk_1` FOREIGN KEY (`Agenti_idAgenti`) REFERENCES `Agenti` (`idAgenti`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Contratti`
--
ALTER TABLE `Contratti`
  ADD CONSTRAINT `fk_Contratti_Clienti1` FOREIGN KEY (`Clienti_idCliente`) REFERENCES `Clienti` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
