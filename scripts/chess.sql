DROP TABLE IF EXISTS `coups`;
DROP TABLE IF EXISTS `login`;
DROP TABLE IF EXISTS `parties`;
DROP TABLE IF EXISTS `partiesproposees`;
DROP TABLE IF EXISTS `statistiques`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `verif`;

CREATE TABLE `coups` (
  `ordre` int(11) NOT NULL AUTO_INCREMENT,
  `cip` varchar(5) NOT NULL,
  `coups` varchar(10) NOT NULL,
  PRIMARY KEY (`ordre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `login` (
  `uid` int(4) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `elo` int(4) NOT NULL DEFAULT '1500',
  `coefficient` int(4) NOT NULL,
  `bidon` varchar(40) DEFAULT NULL,
  `connecte` tinyint(1) NOT NULL,
  `date_inscription` date NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `parties` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `partiesproposees` (
  `gidp` int(5) NOT NULL AUTO_INCREMENT,
  `prospect` int(4) DEFAULT NULL,
  `origine` int(4) NOT NULL,
  `macouleur` char(1) DEFAULT NULL,
  `cadence` int(2) NOT NULL,
  `reserve` int(2) NOT NULL,
  `commentaire` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`gidp`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `statistiques` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `uid` int(4) NOT NULL,
  `gains_b` int(3) DEFAULT '0',
  `pertes_b` int(3) DEFAULT '0',
  `nulles_b` int(3) DEFAULT '0',
  `gains_n` int(3) DEFAULT '0',
  `pertes_n` int(3) DEFAULT '0',
  `nulles_n` int(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `users` (
  `uid` int(4) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(15) DEFAULT NULL,
  `nom` varchar(15) DEFAULT NULL,
  `sexe` char(1) DEFAULT NULL,
  `naissance` date DEFAULT NULL,
  `pays` varchar(15) DEFAULT NULL,
  `description` text,
  `photo` varchar(1) NOT NULL DEFAULT 'n',
  `courriel` varchar(40) DEFAULT NULL,
  `date_connection` datetime DEFAULT NULL,
  `date_inscription` date NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `verif` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `uid` int(4) NOT NULL,
  `session` varchar(30) NOT NULL,
  `timestand` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
