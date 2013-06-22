-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 22 Juin 2013 à 18:31
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `anthill`
--
CREATE DATABASE `anthill` DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci;
USE `anthill`;

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `id_action` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_ticket` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `type` enum('add_user','closed','opened','assigned','accepted','started') COLLATE latin1_general_ci NOT NULL DEFAULT 'opened',
  `date_action` datetime NOT NULL,
  PRIMARY KEY (`id_action`,`id_ticket`,`id_user`),
  KEY `fk_action_ticket1_idx` (`id_ticket`),
  KEY `fk_action_user1_idx` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(125) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `project`
--

DROP TABLE IF EXISTS `project`;
CREATE TABLE IF NOT EXISTS `project` (
  `id_project` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(125) COLLATE latin1_general_ci NOT NULL,
  `content` text COLLATE latin1_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_project`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `project_has_category`
--

DROP TABLE IF EXISTS `project_has_category`;
CREATE TABLE IF NOT EXISTS `project_has_category` (
  `id_project` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_project`,`id_category`),
  KEY `fk_project_has_category_category1_idx` (`id_category`),
  KEY `fk_project_has_category_project1_idx` (`id_project`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `project_has_role`
--

DROP TABLE IF EXISTS `project_has_role`;
CREATE TABLE IF NOT EXISTS `project_has_role` (
  `id_project` int(10) unsigned NOT NULL,
  `id_role` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_project`,`id_role`),
  KEY `fk_project_has_role_role1_idx` (`id_role`),
  KEY `fk_project_has_role_project1_idx` (`id_project`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `project_has_user`
--

DROP TABLE IF EXISTS `project_has_user`;
CREATE TABLE IF NOT EXISTS `project_has_user` (
  `id_project` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_role` int(10) unsigned NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id_project`,`id_user`,`id_role`),
  KEY `fk_project_has_user_project_idx` (`id_project`),
  KEY `fk_project_has_user_user1_idx` (`id_user`),
  KEY `fk_project_has_user_role1_idx` (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_project` int(10) unsigned NOT NULL,
  `name` varchar(125) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_role`,`id_project`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
CREATE TABLE IF NOT EXISTS `ticket` (
  `id_ticket` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_project` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `content` text COLLATE latin1_general_ci NOT NULL,
  `date_created` datetime NOT NULL,
  `duration_start` int(11) NOT NULL,
  `duration_type` int(11) NOT NULL,
  `type` enum('bug','improvment') COLLATE latin1_general_ci NOT NULL DEFAULT 'bug',
  `significance` enum('low','normal','high','rightnow') COLLATE latin1_general_ci NOT NULL DEFAULT 'normal',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ticket`,`id_project`,`id_user`,`id_category`),
  KEY `fk_ticket_project1_idx` (`id_project`),
  KEY `fk_ticket_user1_idx` (`id_user`),
  KEY `fk_ticket_category1_idx` (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `ticket_has_blocking`
--

DROP TABLE IF EXISTS `ticket_has_blocking`;
CREATE TABLE IF NOT EXISTS `ticket_has_blocking` (
  `id_ticket` int(10) unsigned NOT NULL,
  `id_blocking` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_ticket`,`id_blocking`),
  KEY `fk_ticket_has_blocking_ticket1_idx` (`id_ticket`),
  KEY `fk_ticket_has_blocking_ticket2_idx` (`id_blocking`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ticket_has_user`
--

DROP TABLE IF EXISTS `ticket_has_user`;
CREATE TABLE IF NOT EXISTS `ticket_has_user` (
  `id_ticket` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_ticket`,`id_user`),
  KEY `fk_ticket_has_user_ticket1_idx` (`id_ticket`),
  KEY `fk_ticket_has_user_user1_idx` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `first_name` varchar(125) COLLATE latin1_general_ci NOT NULL,
  `last_name` varchar(125) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `action`
--
ALTER TABLE `action`
  ADD CONSTRAINT `fk_action_ticket1` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_action_user1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `project_has_category`
--
ALTER TABLE `project_has_category`
  ADD CONSTRAINT `fk_project_has_category_category1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_project_has_category_project1` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `project_has_role`
--
ALTER TABLE `project_has_role`
  ADD CONSTRAINT `fk_project_has_role_project1` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_project_has_role_role1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `project_has_user`
--
ALTER TABLE `project_has_user`
  ADD CONSTRAINT `fk_project_has_user_project` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_project_has_user_role1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_project_has_user_user1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `fk_ticket_category1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ticket_project1` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ticket_user1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ticket_has_blocking`
--
ALTER TABLE `ticket_has_blocking`
  ADD CONSTRAINT `fk_ticket_has_blocking_ticket1` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ticket_has_blocking_ticket2` FOREIGN KEY (`id_blocking`) REFERENCES `ticket` (`id_ticket`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ticket_has_user`
--
ALTER TABLE `ticket_has_user`
  ADD CONSTRAINT `fk_ticket_has_user_ticket1` FOREIGN KEY (`id_ticket`) REFERENCES `ticket` (`id_ticket`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ticket_has_user_user1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
