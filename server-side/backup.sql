-- MySQL dump 10.13  Distrib 5.5.62, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: music
-- ------------------------------------------------------
-- Server version	5.5.62-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `artist`
--

DROP TABLE IF EXISTS `artist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `bio` text NOT NULL,
  `birth` date NOT NULL,
  `real` varchar(160) NOT NULL,
  `photo` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artist`
--

LOCK TABLES `artist` WRITE;
/*!40000 ALTER TABLE `artist` DISABLE KEYS */;
INSERT INTO `artist` VALUES (1,'50 Cent','','0000-00-00','',''),(2,'Eazy-E','','0000-00-00','',''),(3,'Ice Cube','','0000-00-00','',''),(4,'DMX','','0000-00-00','',''),(5,'Post Malone','','0000-00-00','','');
/*!40000 ALTER TABLE `artist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `artist_track`
--

DROP TABLE IF EXISTS `artist_track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artist_track` (
  `artist_id` int(11) NOT NULL,
  `track_id` int(11) NOT NULL,
  PRIMARY KEY (`artist_id`,`track_id`),
  KEY `artist_id` (`artist_id`),
  KEY `track_id` (`track_id`),
  CONSTRAINT `artist_track_ibfk_2` FOREIGN KEY (`track_id`) REFERENCES `track` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `artist_track_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artist_track`
--

LOCK TABLES `artist_track` WRITE;
/*!40000 ALTER TABLE `artist_track` DISABLE KEYS */;
INSERT INTO `artist_track` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(2,4),(3,4),(4,4),(5,5);
/*!40000 ALTER TABLE `artist_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `track`
--

DROP TABLE IF EXISTS `track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `duration` int(11) NOT NULL,
  `href` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `track`
--

LOCK TABLES `track` WRITE;
/*!40000 ALTER TABLE `track` DISABLE KEYS */;
INSERT INTO `track` VALUES (1,'Questions',224,'https://cdndl.zaycev.net/play/701354/nSiATWK2QVSHOW4-95WA4rNsWBdjc29Y365dQCfZj-40W3qH5tziaW27nLp14-g9YKyrrubMl_yJhroJi9rF5QQIYwyj2Cl8IidWrwCCV3Bvzjmi3KL51heh8QlqVjRapPFLKA?dlKind=play&format=json'),(2,'Ride Tonight',309,'https://cdndl.zaycev.net/play/2278438/v0B0DBk4xrZFok5dxAdD6XMGJ_AbXGgR5woEYoIsqtUAb_8Sryx87_-t88sOxX4MoQatDHQf8uEVN0p93m5PsKxf8kettDDZL76odnclCuh1gjUMw7yOO_o-nZ2-0HTQj317AugmhKSfW7GfN2k1fOJUq18p8pl3lYQEyGvccGiDbHZt5RHzC-pJz_MwllOBwgDmoQ1bVhADZyWaQW6vk_SELDA?dlKind=play&format=json'),(3,'Comics',257,'https://cdndl.zaycev.net/play/2148504/y_l643-oGf-ZloIVROrnc6TGNkZxPHc-9qXHZ8CVTN3RcroDkr-2QKWPFjBJGLJooy8izJpj54V6JUtCTCNNIpZLCaktxKrOmRNyWib3MDPmjanR_ajGfW9srztmRoIaMCsJd6-pWfA1HmBbxbZODSHMdJs?dlKind=play&format=json'),(4,'Fuck You',235,'https://cdndl.zaycev.net/play/12284/15EVZgXMZXaf8UH_3Anfq3ky6lMZp5Mj2fl1vUBUdTDyn8XTX0ngyYpE2ZX5DqN-5AkLNM9sfPHb3R3DyGsKiKyETaVRsLnaJ_cBrjkcn6TTzIhjHsAZqG1q0qvWcbwuviakPw?dlKind=play&format=json'),(5,'Tryna Fuck Me Over',163,'https://cdndl.zaycev.net/play/3920591/P-z-WmYWJxDHcMVaHUvdr4GjzfdUzaOAhS39ozXXk9M-AZ5O5_XdGg4rPEtPRjIrybJkGM6y3Ou2IuSLjixNP6Xvu7m_7YaIl5x0CgXlMlEzMq5XWgDqSFT_IBh5cw7jn8cQua8oYH6eQmKMSSbSmNfRbbBGv9pKAdeVSLcfs-VdHrgTGTx3s78m8mND66FCWCZ-Mg?dlKind=play&format=json');
/*!40000 ALTER TABLE `track` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-29 14:32:47
