-- ===========================================================================
-- ALPHA - schema et donnees de demonstration
--
-- Fichier portable : il ne cree ni ne selectionne la base. Creez-la
-- d'abord, puis importez ce fichier dedans.
--
--   mysql -u root -p -e "CREATE DATABASE projet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
--   mysql -u root -p projet < alpha.sql
--
-- Les tables historiques (client, projet, projet0) ont ete retirees : elles
-- n'etaient utilisees par l'application et contenaient des donnees
-- personnelles obsoletes.
-- ===========================================================================

SET FOREIGN_KEY_CHECKS = 1;

DROP TABLE IF EXISTS `depenses`;
DROP TABLE IF EXISTS `contact_messages`;

-- Table des depenses du laboratoire
CREATE TABLE IF NOT EXISTS `depenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) NOT NULL,
  `quantite` int NOT NULL DEFAULT 1,
  `prix_unitaire` decimal(12,2) NOT NULL DEFAULT 0.00,
  `montant` decimal(14,2) NOT NULL DEFAULT 0.00,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_depenses_date` (`date_creation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des messages envoyes depuis le formulaire de contact
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_contact_date` (`date_creation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- DONNEES DE DEMONSTRATION ---

INSERT INTO `depenses` (`designation`, `quantite`, `prix_unitaire`, `montant`) VALUES
('Carte de developpement IoT', 8, 300.00, 2400.00),
('Camera intelligente', 3, 4500.00, 13500.00),
('Module IA pour edge computing', 5, 1200.00, 6000.00),
('Capteur IoT', 10, 150.00, 1500.00),
('Kit de demarrage IA', 2, 2500.00, 5000.00),
('Serveur IoT', 1, 8000.00, 8000.00),
('Logiciel de traitement de donnees IA', 4, 1500.00, 6000.00);

INSERT INTO `contact_messages` (`nom`, `email`, `sujet`, `message`) VALUES
('Camille Ouattara', 'camille.ouattara@example.com', 'Stage de fin d etudes', 'Bonjour, je recherche un encadrement pour un stage de fin d etudes en vision par ordinateur. Votre projet sur le traitement d images est-il ouvert aux stagiaires ?'),
('Ibrahim Sawadogo', 'ibrahim.sawadogo@example.com', 'Collaboration', 'Nous simulons des capteurs IoT pour un projet agricole ettons reciproquement de documenting les resultats.');
