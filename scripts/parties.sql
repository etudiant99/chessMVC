-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 13 Mai 2018 à 15:20
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `click`
--

-- --------------------------------------------------------

--
-- Structure de la table `parties`
--

CREATE TABLE IF NOT EXISTS `parties` (
  `gid` int(5) NOT NULL AUTO_INCREMENT,
  `uidb` int(4) DEFAULT NULL,
  `uidn` int(4) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date NOT NULL,
  `date_dernier_coup` datetime DEFAULT NULL,
  `cadencep` int(2) DEFAULT NULL,
  `reservep` int(2) DEFAULT NULL,
  `reserve_uidb` float NOT NULL,
  `reserve_uidn` float NOT NULL,
  `finalisation` int(2) NOT NULL,
  `commentaire` text NOT NULL,
  `nulle` int(4) NOT NULL,
  `efface` int(11) NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;