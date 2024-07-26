# ************************************************************
# Sequel Ace SQL dump
# Version 20067
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Hôte: localhost (MySQL 9.0.0)
# Base de données: couture_courteline
# Temps de génération: 2024-07-26 12:42:03 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump de la table absences
# ------------------------------------------------------------

DROP TABLE IF EXISTS `absences`;

CREATE TABLE `absences` (
  `id_cours` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_cours`,`id_utilisateur`),
  KEY `id_cours` (`id_cours`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `absences_ibfk_1` FOREIGN KEY (`id_cours`) REFERENCES `cours` (`id_cours`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `absences_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump de la table associe
# ------------------------------------------------------------

DROP TABLE IF EXISTS `associe`;

CREATE TABLE `associe` (
  `id_creation` int NOT NULL,
  `id_image` int NOT NULL,
  PRIMARY KEY (`id_creation`,`id_image`),
  KEY `id_creation` (`id_creation`),
  KEY `id_image` (`id_image`),
  CONSTRAINT `associe_ibfk_1` FOREIGN KEY (`id_creation`) REFERENCES `creation` (`id_creation`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `associe_ibfk_2` FOREIGN KEY (`id_image`) REFERENCES `image` (`id_image`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump de la table categorie
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categorie`;

CREATE TABLE `categorie` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump de la table cours
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cours`;

CREATE TABLE `cours` (
  `id_cours` int NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `id_horaire` int NOT NULL,
  PRIMARY KEY (`id_cours`),
  KEY `id_horaire` (`id_horaire`),
  CONSTRAINT `cours_ibfk_1` FOREIGN KEY (`id_horaire`) REFERENCES `horaire` (`id_horaire`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `cours` WRITE;
/*!40000 ALTER TABLE `cours` DISABLE KEYS */;

INSERT INTO `cours` (`id_cours`, `date`, `id_horaire`)
VALUES
	(5027,'2024-09-18 18:30:00',1),
	(5028,'2024-09-19 09:00:00',2),
	(5029,'2024-09-19 16:00:00',3),
	(5030,'2024-09-19 18:30:00',4),
	(5031,'2024-09-20 13:30:00',5),
	(5032,'2024-09-20 17:30:00',6),
	(5033,'2024-09-21 10:00:00',7),
	(5034,'2024-09-21 14:15:00',8),
	(5035,'2024-09-25 18:30:00',1),
	(5036,'2024-09-26 09:00:00',2),
	(5037,'2024-09-26 16:00:00',3),
	(5038,'2024-09-26 18:30:00',4),
	(5039,'2024-09-27 13:30:00',5),
	(5040,'2024-09-27 17:30:00',6),
	(5041,'2024-09-28 10:00:00',7),
	(5042,'2024-09-28 14:15:00',8),
	(5043,'2024-10-02 18:30:00',1),
	(5044,'2024-10-03 09:00:00',2),
	(5045,'2024-10-03 16:00:00',3),
	(5046,'2024-10-03 18:30:00',4),
	(5047,'2024-10-04 13:30:00',5),
	(5048,'2024-10-04 17:30:00',6),
	(5049,'2024-10-05 10:00:00',7),
	(5050,'2024-10-05 14:15:00',8),
	(5051,'2024-10-09 18:30:00',1),
	(5052,'2024-10-10 09:00:00',2),
	(5053,'2024-10-10 16:00:00',3),
	(5054,'2024-10-10 18:30:00',4),
	(5055,'2024-10-11 13:30:00',5),
	(5056,'2024-10-11 17:30:00',6),
	(5057,'2024-10-12 10:00:00',7),
	(5058,'2024-10-12 14:15:00',8),
	(5059,'2024-10-16 18:30:00',1),
	(5060,'2024-10-17 09:00:00',2),
	(5061,'2024-10-17 16:00:00',3),
	(5062,'2024-10-17 18:30:00',4),
	(5063,'2024-10-18 13:30:00',5),
	(5064,'2024-10-18 17:30:00',6),
	(5065,'2024-10-19 10:00:00',7),
	(5066,'2024-10-19 14:15:00',8),
	(5067,'2024-11-06 18:30:00',1),
	(5068,'2024-11-07 09:00:00',2),
	(5069,'2024-11-07 16:00:00',3),
	(5070,'2024-11-07 18:30:00',4),
	(5071,'2024-11-08 13:30:00',5),
	(5072,'2024-11-08 17:30:00',6),
	(5073,'2024-11-09 10:00:00',7),
	(5074,'2024-11-09 14:15:00',8),
	(5075,'2024-11-13 18:30:00',1),
	(5076,'2024-11-14 09:00:00',2),
	(5077,'2024-11-14 16:00:00',3),
	(5078,'2024-11-14 18:30:00',4),
	(5079,'2024-11-15 13:30:00',5),
	(5080,'2024-11-15 17:30:00',6),
	(5081,'2024-11-16 10:00:00',7),
	(5082,'2024-11-16 14:15:00',8),
	(5083,'2024-11-20 18:30:00',1),
	(5084,'2024-11-21 09:00:00',2),
	(5085,'2024-11-21 16:00:00',3),
	(5086,'2024-11-21 18:30:00',4),
	(5087,'2024-11-22 13:30:00',5),
	(5088,'2024-11-22 17:30:00',6),
	(5089,'2024-11-23 10:00:00',7),
	(5090,'2024-11-23 14:15:00',8),
	(5091,'2024-11-27 18:30:00',1),
	(5092,'2024-11-28 09:00:00',2),
	(5093,'2024-11-28 16:00:00',3),
	(5094,'2024-11-28 18:30:00',4),
	(5095,'2024-11-29 13:30:00',5),
	(5096,'2024-11-29 17:30:00',6),
	(5097,'2024-11-30 10:00:00',7),
	(5098,'2024-11-30 14:15:00',8),
	(5099,'2024-12-04 18:30:00',1),
	(5100,'2024-12-05 09:00:00',2),
	(5101,'2024-12-05 16:00:00',3),
	(5102,'2024-12-05 18:30:00',4),
	(5103,'2024-12-06 13:30:00',5),
	(5104,'2024-12-06 17:30:00',6),
	(5105,'2024-12-07 10:00:00',7),
	(5106,'2024-12-07 14:15:00',8),
	(5107,'2024-12-11 18:30:00',1),
	(5108,'2024-12-12 09:00:00',2),
	(5109,'2024-12-12 16:00:00',3),
	(5110,'2024-12-12 18:30:00',4),
	(5111,'2024-12-13 13:30:00',5),
	(5112,'2024-12-13 17:30:00',6),
	(5113,'2024-12-14 10:00:00',7),
	(5114,'2024-12-14 14:15:00',8),
	(5115,'2024-12-18 18:30:00',1),
	(5116,'2024-12-19 09:00:00',2),
	(5117,'2024-12-19 16:00:00',3),
	(5118,'2024-12-19 18:30:00',4),
	(5119,'2024-12-20 13:30:00',5),
	(5120,'2024-12-20 17:30:00',6),
	(5121,'2024-12-21 10:00:00',7),
	(5122,'2024-12-21 14:15:00',8),
	(5123,'2025-01-08 18:30:00',1),
	(5124,'2025-01-09 09:00:00',2),
	(5125,'2025-01-09 16:00:00',3),
	(5126,'2025-01-09 18:30:00',4),
	(5127,'2025-01-10 13:30:00',5),
	(5128,'2025-01-10 17:30:00',6),
	(5129,'2025-01-11 10:00:00',7),
	(5130,'2025-01-11 14:15:00',8),
	(5131,'2025-01-15 18:30:00',1),
	(5132,'2025-01-16 09:00:00',2),
	(5133,'2025-01-16 16:00:00',3),
	(5134,'2025-01-16 18:30:00',4),
	(5135,'2025-01-17 13:30:00',5),
	(5136,'2025-01-17 17:30:00',6),
	(5137,'2025-01-18 10:00:00',7),
	(5138,'2025-01-18 14:15:00',8),
	(5139,'2025-01-22 18:30:00',1),
	(5140,'2025-01-23 09:00:00',2),
	(5141,'2025-01-23 16:00:00',3),
	(5142,'2025-01-23 18:30:00',4),
	(5143,'2025-01-24 13:30:00',5),
	(5144,'2025-01-24 17:30:00',6),
	(5145,'2025-01-25 10:00:00',7),
	(5146,'2025-01-25 14:15:00',8),
	(5147,'2025-01-29 18:30:00',1),
	(5148,'2025-01-30 09:00:00',2),
	(5149,'2025-01-30 16:00:00',3),
	(5150,'2025-01-30 18:30:00',4),
	(5151,'2025-01-31 13:30:00',5),
	(5152,'2025-01-31 17:30:00',6),
	(5153,'2025-02-01 10:00:00',7),
	(5154,'2025-02-01 14:15:00',8),
	(5155,'2025-02-05 18:30:00',1),
	(5156,'2025-02-06 09:00:00',2),
	(5157,'2025-02-06 16:00:00',3),
	(5158,'2025-02-06 18:30:00',4),
	(5159,'2025-02-07 13:30:00',5),
	(5160,'2025-02-07 17:30:00',6),
	(5161,'2025-02-08 10:00:00',7),
	(5162,'2025-02-08 14:15:00',8),
	(5163,'2025-02-26 18:30:00',1),
	(5164,'2025-02-27 09:00:00',2),
	(5165,'2025-02-27 16:00:00',3),
	(5166,'2025-02-27 18:30:00',4),
	(5167,'2025-02-28 13:30:00',5),
	(5168,'2025-02-28 17:30:00',6),
	(5169,'2025-03-01 10:00:00',7),
	(5170,'2025-03-01 14:15:00',8),
	(5171,'2025-03-05 18:30:00',1),
	(5172,'2025-03-06 09:00:00',2),
	(5173,'2025-03-06 16:00:00',3),
	(5174,'2025-03-06 18:30:00',4),
	(5175,'2025-03-07 13:30:00',5),
	(5176,'2025-03-07 17:30:00',6),
	(5177,'2025-03-08 10:00:00',7),
	(5178,'2025-03-08 14:15:00',8),
	(5179,'2025-03-12 18:30:00',1),
	(5180,'2025-03-13 09:00:00',2),
	(5181,'2025-03-13 16:00:00',3),
	(5182,'2025-03-13 18:30:00',4),
	(5183,'2025-03-14 13:30:00',5),
	(5184,'2025-03-14 17:30:00',6),
	(5185,'2025-03-15 10:00:00',7),
	(5186,'2025-03-15 14:15:00',8),
	(5187,'2025-03-19 18:30:00',1),
	(5188,'2025-03-20 09:00:00',2),
	(5189,'2025-03-20 16:00:00',3),
	(5190,'2025-03-20 18:30:00',4),
	(5191,'2025-03-21 13:30:00',5),
	(5192,'2025-03-21 17:30:00',6),
	(5193,'2025-03-22 10:00:00',7),
	(5194,'2025-03-22 14:15:00',8),
	(5195,'2025-03-26 18:30:00',1),
	(5196,'2025-03-27 09:00:00',2),
	(5197,'2025-03-27 16:00:00',3),
	(5198,'2025-03-27 18:30:00',4),
	(5199,'2025-03-28 13:30:00',5),
	(5200,'2025-03-28 17:30:00',6),
	(5201,'2025-03-29 10:00:00',7),
	(5202,'2025-03-29 14:15:00',8),
	(5203,'2025-04-02 18:30:00',1),
	(5204,'2025-04-03 09:00:00',2),
	(5205,'2025-04-03 16:00:00',3),
	(5206,'2025-04-03 18:30:00',4),
	(5207,'2025-04-04 13:30:00',5),
	(5208,'2025-04-04 17:30:00',6),
	(5209,'2025-04-05 10:00:00',7),
	(5210,'2025-04-05 14:15:00',8),
	(5211,'2025-04-23 18:30:00',1),
	(5212,'2025-04-24 09:00:00',2),
	(5213,'2025-04-24 16:00:00',3),
	(5214,'2025-04-24 18:30:00',4),
	(5215,'2025-04-25 13:30:00',5),
	(5216,'2025-04-25 17:30:00',6),
	(5217,'2025-04-26 10:00:00',7),
	(5218,'2025-04-26 14:15:00',8),
	(5219,'2025-04-30 18:30:00',1),
	(5220,'2025-05-02 13:30:00',5),
	(5221,'2025-05-02 17:30:00',6),
	(5222,'2025-05-03 10:00:00',7),
	(5223,'2025-05-03 14:15:00',8),
	(5224,'2025-05-07 18:30:00',1),
	(5225,'2025-05-09 13:30:00',5),
	(5226,'2025-05-09 17:30:00',6),
	(5227,'2025-05-10 10:00:00',7),
	(5228,'2025-05-10 14:15:00',8),
	(5229,'2025-05-14 18:30:00',1),
	(5230,'2025-05-15 09:00:00',2),
	(5231,'2025-05-15 16:00:00',3),
	(5232,'2025-05-15 18:30:00',4),
	(5233,'2025-05-16 13:30:00',5),
	(5234,'2025-05-16 17:30:00',6),
	(5235,'2025-05-17 10:00:00',7),
	(5236,'2025-05-17 14:15:00',8),
	(5237,'2025-05-21 18:30:00',1),
	(5238,'2025-05-22 09:00:00',2),
	(5239,'2025-05-22 16:00:00',3),
	(5240,'2025-05-22 18:30:00',4),
	(5241,'2025-05-23 13:30:00',5),
	(5242,'2025-05-23 17:30:00',6),
	(5243,'2025-05-24 10:00:00',7),
	(5244,'2025-05-24 14:15:00',8),
	(5245,'2025-05-28 18:30:00',1),
	(5246,'2025-06-04 18:30:00',1),
	(5247,'2025-06-05 09:00:00',2),
	(5248,'2025-06-05 16:00:00',3),
	(5249,'2025-06-05 18:30:00',4),
	(5250,'2025-06-06 13:30:00',5),
	(5251,'2025-06-06 17:30:00',6),
	(5252,'2025-06-07 10:00:00',7),
	(5253,'2025-06-07 14:15:00',8),
	(5254,'2025-06-11 18:30:00',1),
	(5255,'2025-06-12 09:00:00',2),
	(5256,'2025-06-12 16:00:00',3),
	(5257,'2025-06-12 18:30:00',4),
	(5258,'2025-06-13 13:30:00',5),
	(5259,'2025-06-13 17:30:00',6),
	(5260,'2025-06-14 10:00:00',7),
	(5261,'2025-06-14 14:15:00',8),
	(5262,'2025-06-18 18:30:00',1),
	(5263,'2025-06-19 09:00:00',2),
	(5264,'2025-06-19 16:00:00',3),
	(5265,'2025-06-19 18:30:00',4),
	(5266,'2025-06-20 13:30:00',5),
	(5267,'2025-06-20 17:30:00',6),
	(5268,'2025-06-21 10:00:00',7),
	(5269,'2025-06-21 14:15:00',8),
	(5270,'2025-06-25 18:30:00',1),
	(5271,'2025-06-26 09:00:00',2),
	(5272,'2025-06-26 16:00:00',3),
	(5273,'2025-06-26 18:30:00',4),
	(5274,'2025-06-27 13:30:00',5),
	(5275,'2025-06-27 17:30:00',6),
	(5276,'2025-06-28 10:00:00',7),
	(5277,'2025-06-28 14:15:00',8);

/*!40000 ALTER TABLE `cours` ENABLE KEYS */;
UNLOCK TABLES;


# Dump de la table creation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `creation`;

CREATE TABLE `creation` (
  `id_creation` int NOT NULL,
  `nom` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `tissu` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  `surface_tissu` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `patron` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_creation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump de la table horaire
# ------------------------------------------------------------

DROP TABLE IF EXISTS `horaire`;

CREATE TABLE `horaire` (
  `id_horaire` int NOT NULL AUTO_INCREMENT,
  `jour` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `heure` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_horaire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `horaire` WRITE;
/*!40000 ALTER TABLE `horaire` DISABLE KEYS */;

INSERT INTO `horaire` (`id_horaire`, `jour`, `heure`)
VALUES
	(1,'Mercredi','18h30-21h00'),
	(2,'Jeudi','9h00-11h30'),
	(3,'Jeudi','16h00-18h30'),
	(4,'Jeudi','18h30-21h00'),
	(5,'Vendredi','13h30-16h00'),
	(6,'Vendredi','17h30-20h00'),
	(7,'Samedi','10h00-12h30'),
	(8,'Samedi','14h15-16h45');

/*!40000 ALTER TABLE `horaire` ENABLE KEYS */;
UNLOCK TABLES;


# Dump de la table image
# ------------------------------------------------------------

DROP TABLE IF EXISTS `image`;

CREATE TABLE `image` (
  `id_image` int NOT NULL AUTO_INCREMENT,
  `fichier` blob NOT NULL,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_image`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `image_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump de la table rattrapages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rattrapages`;

CREATE TABLE `rattrapages` (
  `id_cours` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_cours`,`id_utilisateur`),
  KEY `id_cours` (`id_cours`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `rattrapages_ibfk_1` FOREIGN KEY (`id_cours`) REFERENCES `cours` (`id_cours`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rattrapages_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump de la table tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tokens`;

CREATE TABLE `tokens` (
  `token` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `id_utilisateur` int NOT NULL,
  `date_creation` int NOT NULL,
  PRIMARY KEY (`token`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump de la table type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `type`;

CREATE TABLE `type` (
  `id_creation` int NOT NULL,
  `id_categorie` int NOT NULL,
  PRIMARY KEY (`id_creation`,`id_categorie`),
  KEY `id_creation` (`id_creation`),
  KEY `id_categorie` (`id_categorie`),
  CONSTRAINT `type_ibfk_1` FOREIGN KEY (`id_creation`) REFERENCES `creation` (`id_creation`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `type_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



# Dump de la table utilisateur
# ------------------------------------------------------------

DROP TABLE IF EXISTS `utilisateur`;

CREATE TABLE `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `nbr_rattrapage` int unsigned NOT NULL,
  `id_horaire` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`),
  KEY `id_horaire` (`id_horaire`),
  CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_horaire`) REFERENCES `horaire` (`id_horaire`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `role`, `nbr_rattrapage`, `id_horaire`)
VALUES
	(1,'Doe','John','j.doe@lacouturedecp.fr','user',0,1),
	(2,'Smith','Jane','j.smith@lacouturecp.fr','user',0,2),
	(3,'Dupont','Martin','m.dumont@lacouturedecp.fr','admin',0,3),
	(4,'Gillot','Gatien','pub@gatiendev.fr','user',0,2),
	(5,'Dubois','Martin','couture@gatiendev.fr','admin',0,4);

/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
