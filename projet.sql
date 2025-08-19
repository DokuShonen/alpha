-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 17 mars 2025 à 07:34
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `designation` text NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` int NOT NULL,
  `montant` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `designation`, `quantite`, `prix_unitaire`, `montant`) VALUES
(1, 'pro', 12, 1555, 18660),
(2, 'pro', 2, 12, 24),
(3, 'pro', 12, 2000, 24000);

-- --------------------------------------------------------

--
-- Structure de la table `depenses`
--

DROP TABLE IF EXISTS `depenses`;
CREATE TABLE IF NOT EXISTS `depenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `depenses`
--

INSERT INTO `depenses` (`id`, `designation`, `quantite`, `prix_unitaire`, `montant`) VALUES
(19, 'Carte de développement IoT', 8, 300.00, 2400.00),
(18, 'Caméra intelligente', 3, 4500.00, 13500.00),
(17, 'Module IA pour edge computing', 5, 1200.00, 6000.00),
(16, 'Capteur IoT', 10, 150.00, 1500.00),
(20, 'Kit de démarrage IA', 2, 2500.00, 5000.00),
(21, 'Serveur IoT', 1, 8000.00, 8000.00),
(22, 'Logiciel de traitement de données IA', 4, 1500.00, 6000.00);

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

DROP TABLE IF EXISTS `projet`;
CREATE TABLE IF NOT EXISTS `projet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `pays` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`id`, `nom`, `prenom`, `date_naissance`, `pays`, `email`, `mot_de_passe`) VALUES
(5, 'OUEDRAOGO', 'Clara', '2023-08-06', 'Afghanistan ', 'ouedraogoclara@gmail.com', '$2y$10$dtGVGU/BrMcZB041iJjut.tevY1E.ggZoNwjiHcelQfPNno9q55IC'),
(6, 'KINDA', 'Ulrich', '2003-05-04', 'Burkina Faso', 'kindaulrich1@gmail.com', '$2y$10$NN2qvF/u8UZadLBY.EF8aupnc8DtQgtZinosr6m3WiYD7y6KH37d6'),
(7, 'KINDA', 'Ulrich Kevin', '2003-05-04', 'Japon', 'kndulrich@gmail.com', '$2y$10$gVNE6uTlXHaH0xcUuTZSS.29bLrn1OFbwmQOuFRBYZuJxzKhDTzNu');

-- --------------------------------------------------------

--
-- Structure de la table `projet0`
--

DROP TABLE IF EXISTS `projet0`;
CREATE TABLE IF NOT EXISTS `projet0` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  `pnom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pass` varchar(45) NOT NULL,
  `mnt` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `projet0`
--

INSERT INTO `projet0` (`id`, `nom`, `pnom`, `pass`, `mnt`) VALUES
(52, 'KINDA', 'Akashi', '04052003', 2500000),
(53, 'YAMEOGO', 'Kevin', '04052003', 1000000),
(56, 'ouedraogo', 'fadila', 'mot', 2500000),
(55, 'DIdi', 'eldad', 'mot', 300000);
COMMIT;

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
