-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 14 Octobre 2020 à 14:58
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `maki`
--
CREATE DATABASE IF NOT EXISTS `maki` DEFAULT CHARACTER SET latin1 COLLATE latin1_german1_ci;
USE `maki`;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `statut` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `statut`) VALUES
(1, 'Viande', 1),
(2, 'Huile', 1);

-- --------------------------------------------------------

--
-- Structure de la table `expirations`
--

CREATE TABLE IF NOT EXISTS `expirations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qte` int(11) DEFAULT NULL,
  `date_op` timestamp NULL DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `depot` tinyint(1) NOT NULL,
  `boutique` tinyint(1) NOT NULL,
  `date_production` date NOT NULL DEFAULT '0000-00-00',
  `warehouse_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_expirations_products1` (`product_id`),
  KEY `fk_expirations_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_facture` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `valeur` float DEFAULT NULL,
  `date_op` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoices_users1` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_produit` varchar(120) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_german1_ci,
  `cout_achat` float DEFAULT NULL,
  `prix_detaille` float DEFAULT NULL,
  `emballage` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `mesure` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `nombre_contenu` int(11) DEFAULT NULL,
  `perrisable` tinyint(1) DEFAULT NULL,
  `statut` tinyint(1) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_products_categories` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `products`
--

INSERT INTO `products` (`id`, `nom_produit`, `description`, `cout_achat`, `prix_detaille`, `emballage`, `mesure`, `nombre_contenu`, `perrisable`, `statut`, `category_id`) VALUES
(1, 'Gloire', '<p>jfdlksfj dskjskdl</p>', 10, 11, 'Carton', 'PiÃ¨ce', 2, 1, 1, 1),
(2, 'Pantalon', '<p>fdsjhsd fdsijiods fdsijoidsa dsifoids fds</p>', 12, 12.5, 'Vrac', 'PiÃ¨ce', 1, 0, 1, 1),
(3, 'Podsq', '<p>fdksjfslkdj fdskjfkldsjf</p>', 10, 11, 'Carton', 'piÃ¨ce', 12, 0, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qte` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `prix_unit` float DEFAULT NULL,
  `valeur` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sales_invoices1` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `post_nom` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `prenom` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `role` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `username` varchar(45) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL,
  `statut` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `post_nom`, `prenom`, `role`, `username`, `password`, `statut`) VALUES
(1, 'JADO', 'CHIRIMWA', 'Gloire', 'GÃ©rant', 'gloire', 'f21711889ae2bc91e61ccf6f34e7aeb050bde1b1', 1),
(2, 'BUKUZE', 'CHIRIMWAMI', 'Gloire', 'Magasinier', 'root', 'f21711889ae2bc91e61ccf6f34e7aeb050bde1b1', 1);

-- --------------------------------------------------------

--
-- Structure de la table `values`
--

CREATE TABLE IF NOT EXISTS `values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qte` int(11) DEFAULT NULL,
  `montant` float DEFAULT NULL,
  `prix_unit` float DEFAULT NULL,
  `montant_total` float DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_values_invoices1` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `warehouses`
--

CREATE TABLE IF NOT EXISTS `warehouses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qte` int(11) DEFAULT NULL,
  `prix_unit` float DEFAULT NULL,
  `valeur_total` float DEFAULT NULL,
  `qte_total` int(11) DEFAULT NULL,
  `valeur` float DEFAULT NULL,
  `vente` tinyint(1) DEFAULT NULL,
  `transfert` tinyint(1) DEFAULT NULL,
  `correction` tinyint(1) DEFAULT NULL,
  `erreur_stockage` tinyint(1) DEFAULT NULL,
  `stockage` tinyint(1) DEFAULT NULL,
  `solde` tinyint(1) DEFAULT NULL,
  `date_op` timestamp NULL DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `depot` tinyint(1) NOT NULL,
  `boutique` tinyint(1) NOT NULL,
  `mvm` tinyint(1) NOT NULL,
  `annul_facture` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_warehouses_products1` (`product_id`),
  KEY `fk_warehouses_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `expirations`
--
ALTER TABLE `expirations`
  ADD CONSTRAINT `fk_expirations_products1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_expirations_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_invoices1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `values`
--
ALTER TABLE `values`
  ADD CONSTRAINT `fk_values_invoices1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `fk_warehouses_products1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_warehouses_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
