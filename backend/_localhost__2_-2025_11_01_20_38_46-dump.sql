-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: handball_management_system
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `age_category`
--

DROP TABLE IF EXISTS `age_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `age_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gender`
--

DROP TABLE IF EXISTS `gender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `league`
--

DROP TABLE IF EXISTS `league`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `league` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `age_category_id` int(11) NOT NULL,
  `gender_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `until_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `age_category_id` (`age_category_id`),
  KEY `gender_id` (`gender_id`),
  CONSTRAINT `league_ibfk_1` FOREIGN KEY (`age_category_id`) REFERENCES `age_category` (`id`),
  CONSTRAINT `league_ibfk_2` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`),
  CONSTRAINT `check_league_dates` CHECK (`until_date` is null or `until_date` > `from_date`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `league_phase`
--

DROP TABLE IF EXISTS `league_phase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `league_phase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `league_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `playing_mode` varchar(50) NOT NULL,
  `matches_per_participant` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `league_id` (`league_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `league_phase_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `league` (`id`),
  CONSTRAINT `league_phase_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `league_phase` (`id`),
  CONSTRAINT `playing_mode_values` CHECK (`playing_mode` in ('knockout','group','container'))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `match_phase`
--

DROP TABLE IF EXISTS `match_phase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `match_phase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `match_stats`
--

DROP TABLE IF EXISTS `match_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `match_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_phase_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL,
  `goals` int(11) NOT NULL,
  `red_cards` int(11) NOT NULL,
  `yellow_cards` int(11) NOT NULL,
  `team_timeouts` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `match_phase_id` (`match_phase_id`),
  KEY `participant_id` (`participant_id`),
  KEY `match_id` (`match_id`),
  CONSTRAINT `match_stats_ibfk_1` FOREIGN KEY (`match_phase_id`) REFERENCES `match_phase` (`id`),
  CONSTRAINT `match_stats_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`id`),
  CONSTRAINT `match_stats_ibfk_3` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `league_id` int(11) NOT NULL,
  `league_phase_id` int(11) NOT NULL,
  `pair_id` int(11) NOT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `home_participant_id` int(11) NOT NULL,
  `away_participant_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pair_id` (`pair_id`),
  KEY `home_participant_id` (`home_participant_id`),
  KEY `away_participant_id` (`away_participant_id`),
  KEY `league_id` (`league_id`),
  KEY `league_phase_id` (`league_phase_id`),
  KEY `venue_id` (`venue_id`),
  CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`pair_id`) REFERENCES `pairs` (`id`),
  CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`home_participant_id`) REFERENCES `participants` (`id`),
  CONSTRAINT `matches_ibfk_3` FOREIGN KEY (`away_participant_id`) REFERENCES `participants` (`id`),
  CONSTRAINT `matches_ibfk_4` FOREIGN KEY (`league_id`) REFERENCES `league` (`id`),
  CONSTRAINT `matches_ibfk_5` FOREIGN KEY (`league_phase_id`) REFERENCES `league_phase` (`id`),
  CONSTRAINT `matches_ibfk_6` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`),
  CONSTRAINT `check_different_participants` CHECK (`home_participant_id` <> `away_participant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pairs`
--

DROP TABLE IF EXISTS `pairs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pairs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `participant_A_id` int(11) NOT NULL,
  `participant_B_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `participant_A_id` (`participant_A_id`),
  KEY `participant_B_id` (`participant_B_id`),
  CONSTRAINT `pairs_ibfk_1` FOREIGN KEY (`participant_A_id`) REFERENCES `participants` (`id`),
  CONSTRAINT `pairs_ibfk_2` FOREIGN KEY (`participant_B_id`) REFERENCES `participants` (`id`),
  CONSTRAINT `check_different_participants` CHECK (`participant_A_id` <> `participant_B_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `participants`
--

DROP TABLE IF EXISTS `participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `league_phase_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `team_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_phase_id` (`league_phase_id`),
  KEY `team_id` (`team_id`),
  CONSTRAINT `participants_ibfk_1` FOREIGN KEY (`league_phase_id`) REFERENCES `league_phase` (`id`),
  CONSTRAINT `participants_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `gender_id` int(11) NOT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gender_id` (`gender_id`),
  CONSTRAINT `players_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `team_player`
--

DROP TABLE IF EXISTS `team_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `until_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`),
  KEY `player_id` (`player_id`),
  CONSTRAINT `team_player_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`),
  CONSTRAINT `team_player_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`),
  CONSTRAINT `check_team_player_dates` CHECK (`until_date` is null or `until_date` > `from_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `age_category_id` int(11) NOT NULL,
  `gender_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `age_category_id` (`age_category_id`),
  KEY `gender_id` (`gender_id`),
  CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`age_category_id`) REFERENCES `age_category` (`id`),
  CONSTRAINT `teams_ibfk_2` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `venues`
--

DROP TABLE IF EXISTS `venues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-01 20:38:46
