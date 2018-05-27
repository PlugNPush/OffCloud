-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le :  Dim 27 mai 2018 à 20:58
-- Version du serveur :  5.6.38
-- Version de PHP :  7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `projet isn a 3`
--
CREATE DATABASE IF NOT EXISTS `projet isn a 3` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `projet isn a 3`;

-- --------------------------------------------------------

--
-- Structure de la table `donnees`
--
-- Création :  lun. 21 mai 2018 à 13:57
--

DROP TABLE IF EXISTS `donnees`;
CREATE TABLE IF NOT EXISTS `donnees` (
  `id` int(11) NOT NULL,
  `checksum` varchar(255) NOT NULL,
  `nomdufichier` varchar(255) DEFAULT NULL,
  `crypt` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `partage`
--
-- Création :  Dim 27 mai 2018 à 14:54
--

DROP TABLE IF EXISTS `partage`;
CREATE TABLE IF NOT EXISTS `partage` (
  `id` int(11) NOT NULL,
  `checksum` varchar(255) NOT NULL,
  `idreceveur` int(11) NOT NULL,
  `nomdefichier` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--
-- Création :  lun. 21 mai 2018 à 13:57
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `adressFTP` varchar(255) DEFAULT NULL,
  `userFTP` varchar(255) DEFAULT NULL,
  `mdpFTP` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
