-- MySQL dump 10.13  Distrib 5.5.46, for Linux (x86_64)
--
-- Host: localhost    Database: test_alkitab
-- ------------------------------------------------------
-- Server version	5.5.46

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
-- Table structure for table `en_books`
--

DROP TABLE IF EXISTS `en_books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `en_books` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `abbr` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `en_books`
--

LOCK TABLES `en_books` WRITE;
/*!40000 ALTER TABLE `en_books` DISABLE KEYS */;
INSERT INTO `en_books` VALUES (1,'Gen','Genesis'),(2,'Exo','Exodus'),(3,'Lev','Leviticus'),(4,'Num','Numbers'),(5,'Deu','Deuteronomy'),(6,'Jos','Joshua'),(7,'Jdg','Judges'),(8,'Rut','Ruth'),(9,'1Sa','1 Samuel'),(10,'2Sa','2 Samuel'),(11,'1Ki','1 Kings'),(12,'2Ki','2 Kings'),(13,'1Ch','1 Chronicles'),(14,'2Ch','2 Chronicles'),(15,'Ezr','Ezra'),(16,'Neh','Nehemiah'),(17,'Est','Esther'),(18,'Job','Job'),(19,'Psa','Psalms'),(20,'Pro','Proverbs'),(21,'Ecc','Ecclesiastes'),(22,'Sos','The Song of Songs'),(23,'Isa','Isaiah'),(24,'Jer','Jeremiah'),(25,'Lam','Lamentations'),(26,'Eze','Ezekiel'),(27,'Dan','Daniel'),(28,'Hos','Hosea'),(29,'Joe','Joel'),(30,'Amo','Amos'),(31,'Oba','Obadiah'),(32,'Jon','Jonah'),(33,'Mic','Micah'),(34,'Nah','Nahum'),(35,'Hab','Habakkuk'),(36,'Zep','Zephaniah'),(37,'Hag','Haggai'),(38,'Zec','Zechariah'),(39,'Mal','Malachi'),(40,'Mat','Matthew'),(41,'Mar','Mark'),(42,'Luk','Luke'),(43,'Joh','John'),(44,'Act','Acts'),(45,'Rom','Romans'),(46,'1Co','1 Corinthians'),(47,'2Co','2 Corinthians'),(48,'Gal','Galatians'),(49,'Eph','Ephesians'),(50,'Phi','Philippians'),(51,'Col','Colossians'),(52,'1Th','1 Thessalonians'),(53,'2Th','2 Thessalonians'),(54,'1Ti','1 Timothy'),(55,'2Ti','2 Timothy'),(56,'Tit','Titus'),(57,'Phm','Philemon'),(58,'Heb','Hebrews'),(59,'Jam','James'),(60,'1Pe','1 Peter'),(61,'2Pe','2 Peter'),(62,'1Jo','1 John'),(63,'2Jo','2 John'),(64,'3Jo','3 John'),(65,'Jud','Jude'),(66,'Rev','Revelation');
/*!40000 ALTER TABLE `en_books` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-30 16:57:52
