-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Dim 20 Septembre 2020 à 09:17
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
  `nom` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `statut` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `expirations`
--

CREATE TABLE IF NOT EXISTS `expirations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qte` int(11) DEFAULT NULL,
  `date_op` timestamp NULL DEFAULT NULL,
  `date_expiration` timestamp NULL DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_expirations_products1` (`product_id`),
  KEY `fk_expirations_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `idi` int(11) NOT NULL AUTO_INCREMENT,
  `code_facture` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `valeur` float DEFAULT NULL,
  `date_op` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`idi`),
  KEY `fk_invoices_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_produit` varchar(120) COLLATE latin1_german1_ci DEFAULT NULL,
  `description` text COLLATE latin1_german1_ci,
  `cout_achat` float DEFAULT NULL,
  `prix_detaille` float DEFAULT NULL,
  `emballage` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `mesure` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `nombre_contenu` int(11) DEFAULT NULL,
  `perrisable` tinyint(1) DEFAULT NULL,
  `statut` tinyint(1) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_products_categories` (`categorie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qte` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `prix_unit` float DEFAULT NULL,
  `valeur` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `invoice_idi` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sales_invoices1` (`invoice_idi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `post_nom` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `prenom` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `role` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `username` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `password` varchar(255) COLLATE latin1_german1_ci DEFAULT NULL,
  `statut` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `post_nom`, `prenom`, `role`, `username`, `password`, `statut`) VALUES
(5, 'LKJSQJLKDQJ', 'LKJDSQLKDJQLKS', 'Gloire', 'GÃ©rant', 'root', '86e37ea989bd10fe8b8091f747c595a1f348dbdd', 1),
(6, 'GLOIRE', 'GLOIRE', 'Jean', 'Magasinier', 'gloire', '86e37ea989bd10fe8b8091f747c595a1f348dbdd', 0);

-- --------------------------------------------------------

--
-- Structure de la table `values`
--

CREATE TABLE IF NOT EXISTS `values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qte` int(11) DEFAULT NULL,
  `qte_s` varchar(45) COLLATE latin1_german1_ci DEFAULT NULL,
  `montant` float DEFAULT NULL,
  `montant_s` float DEFAULT NULL,
  `prix_unit` float DEFAULT NULL,
  `montant_total` float DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_values_invoices1` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`id`),
  KEY `fk_warehouses_products1` (`product_id`),
  KEY `fk_warehouses_users1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
