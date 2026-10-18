-- MariaDB dump 10.19  Distrib 10.11.6-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: welkar_db
-- ------------------------------------------------------
-- Server version	12.3.3-MariaDB

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
-- Table structure for table `abandoned_cart_reminders`
--

DROP TABLE IF EXISTS `abandoned_cart_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abandoned_cart_reminders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `reminder_step` int(11) DEFAULT 1,
  `token` varchar(64) NOT NULL,
  `status` enum('pending','sent','recovered','opted_out') DEFAULT 'pending',
  `sent_at` datetime DEFAULT NULL,
  `recovered_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `fk_acr_cart` (`cart_id`),
  CONSTRAINT `fk_acr_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abandoned_cart_reminders`
--

LOCK TABLES `abandoned_cart_reminders` WRITE;
/*!40000 ALTER TABLE `abandoned_cart_reminders` DISABLE KEYS */;
/*!40000 ALTER TABLE `abandoned_cart_reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ad_integrations`
--

DROP TABLE IF EXISTS `ad_integrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ad_integrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(50) NOT NULL,
  `account_id` varchar(100) DEFAULT NULL,
  `is_connected` tinyint(1) DEFAULT 0,
  `credentials_json` longtext DEFAULT NULL,
  `last_synced_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider` (`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ad_integrations`
--

LOCK TABLES `ad_integrations` WRITE;
/*!40000 ALTER TABLE `ad_integrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ad_integrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_mfa_challenges`
--

DROP TABLE IF EXISTS `admin_mfa_challenges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_mfa_challenges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(10) unsigned NOT NULL,
  `code` varchar(10) NOT NULL,
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_mfa_admin` (`admin_user_id`),
  CONSTRAINT `fk_mfa_admin` FOREIGN KEY (`admin_user_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_mfa_challenges`
--

LOCK TABLES `admin_mfa_challenges` WRITE;
/*!40000 ALTER TABLE `admin_mfa_challenges` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_mfa_challenges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_user_roles`
--

DROP TABLE IF EXISTS `admin_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_user_roles` (
  `admin_user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`admin_user_id`,`role_id`),
  KEY `fk_aur_role` (`role_id`),
  CONSTRAINT `fk_aur_admin` FOREIGN KEY (`admin_user_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_aur_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user_roles`
--

LOCK TABLES `admin_user_roles` WRITE;
/*!40000 ALTER TABLE `admin_user_roles` DISABLE KEYS */;
INSERT INTO `admin_user_roles` VALUES
(1,1);
/*!40000 ALTER TABLE `admin_user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `role_slug` varchar(50) NOT NULL DEFAULT 'manager',
  `status` enum('active','inactive') DEFAULT 'active',
  `mfa_enabled` tinyint(1) DEFAULT 0,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES
(1,'admin','welkarstore@gmail.com','$2y$10$UYsRfclK2vnTHWmryFHrLO3zfZQJzPOLOsu4Gq9xehbRg9YtUHs7q','Welkar Store Admin','owner','active',0,'2026-10-16 19:30:11','2026-10-12 16:57:27','2026-10-16 19:30:11');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analytics_events`
--

DROP TABLE IF EXISTS `analytics_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analytics_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_name` varchar(80) NOT NULL,
  `session_id` varchar(120) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `page_url` varchar(255) NOT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `utm_source` varchar(80) DEFAULT NULL,
  `utm_medium` varchar(80) DEFAULT NULL,
  `utm_campaign` varchar(120) DEFAULT NULL,
  `utm_content` varchar(120) DEFAULT NULL,
  `utm_term` varchar(120) DEFAULT NULL,
  `event_data` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ae_name` (`event_name`),
  KEY `idx_ae_sess` (`session_id`),
  KEY `idx_ae_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics_events`
--

LOCK TABLES `analytics_events` WRITE;
/*!40000 ALTER TABLE `analytics_events` DISABLE KEYS */;
INSERT INTO `analytics_events` VALUES
(1,'view_item','26af4e70d1ffc055c2193f0de6c17950',NULL,'/product/fossil-townsman-automatic-skeleton','http://localhost:8080/admin/products/7/edit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":7,\"name\":\"Fossil Townsman Automatic Skeleton\",\"price\":\"9495.00\"}','2026-10-12 17:58:44'),
(2,'view_item','26af4e70d1ffc055c2193f0de6c17950',NULL,'/product/fossil-townsman-automatic-skeleton','http://localhost:8080/catalog','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":7,\"name\":\"Fossil Townsman Automatic Skeleton\",\"price\":\"9495.00\"}','2026-10-12 17:59:04'),
(3,'view_item','26af4e70d1ffc055c2193f0de6c17950',NULL,'/product/fossil-townsman-automatic-skeleton','http://localhost:8080/catalog','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":7,\"name\":\"Fossil Townsman Automatic Skeleton\",\"price\":\"9495.00\"}','2026-10-12 17:59:08'),
(4,'view_item','656c7bb8d88ffb864f67cff24e5a8f5d',NULL,'/product/welkar-royale-classic-roman-gold','http://localhost:8080/catalog','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-12 19:10:57'),
(5,'view_item','sess_b2f58eb5457157c81858232a90e643ab',NULL,'/product/welkar-royale-classic-roman-gold',NULL,'127.0.0.1','Unknown',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-12 19:13:54'),
(6,'view_item','sess_37be7be69e82fb45aec476273516f306',NULL,'/product/fossil-townsman-automatic-skeleton','http://localhost:8080/','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":7,\"name\":\"Fossil Townsman Automatic Skeleton\",\"price\":\"9495.00\"}','2026-10-12 19:26:33'),
(7,'add_to_cart','712e9f4c339a699ba9d9e55b1660d9a7',NULL,'/cart/add','http://localhost:8080/product/fossil-townsman-automatic-skeleton','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"variant_id\":9,\"quantity\":1}','2026-10-12 19:26:35'),
(8,'view_cart','712e9f4c339a699ba9d9e55b1660d9a7',NULL,'/cart','http://localhost:8080/product/fossil-townsman-automatic-skeleton','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":1}','2026-10-12 19:26:35'),
(9,'view_item','712e9f4c339a699ba9d9e55b1660d9a7',NULL,'/product/fossil-townsman-automatic-skeleton','http://localhost:8080/','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":7,\"name\":\"Fossil Townsman Automatic Skeleton\",\"price\":\"9495.00\"}','2026-10-12 19:26:39'),
(10,'view_item','sess_30a0d6757843206bac60e1cdceb9ab7e',NULL,'/product/welkar-royale-classic-roman-gold',NULL,'127.0.0.1','curl/8.21.0',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-12 19:37:47'),
(11,'view_cart','712e9f4c339a699ba9d9e55b1660d9a7',NULL,'/cart','http://localhost:8080/account/login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":1}','2026-10-12 19:45:56'),
(12,'view_item','712e9f4c339a699ba9d9e55b1660d9a7',NULL,'/product/welkar-royale-classic-roman-gold','http://localhost:8080/catalog?filter=dial_types','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-12 19:46:02'),
(13,'add_to_cart','712e9f4c339a699ba9d9e55b1660d9a7',NULL,'/cart/add','http://localhost:8080/product/welkar-royale-classic-roman-gold','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"variant_id\":5,\"quantity\":1}','2026-10-12 19:46:06'),
(14,'view_cart','712e9f4c339a699ba9d9e55b1660d9a7',NULL,'/cart','http://localhost:8080/product/welkar-royale-classic-roman-gold','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":2}','2026-10-12 19:46:06'),
(15,'view_cart','712e9f4c339a699ba9d9e55b1660d9a7',NULL,'/cart','http://localhost:8080/','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":2}','2026-10-12 19:46:12'),
(16,'view_cart','712e9f4c339a699ba9d9e55b1660d9a7',NULL,'/cart','http://localhost:8080/cart','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":1}','2026-10-12 19:46:14'),
(17,'view_cart','712e9f4c339a699ba9d9e55b1660d9a7',NULL,'/cart','http://localhost:8080/cart','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":0}','2026-10-12 19:46:17'),
(18,'view_item','36097a19361662ffb92fa9e55ded469f',NULL,'/product/welkar-3-piece-transparent-travel-washbag-set','http://localhost:8080/','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":24,\"name\":\"Welkar 3-Piece Transparent Travel Washbag & Storage Set\",\"price\":\"699.00\"}','2026-10-13 10:40:45'),
(19,'view_item','sess_6be841815ad935916c5f2dd2a0e052ac',NULL,'/product/welkar-stealth-square-dial-analog-quartz',NULL,'::1','curl/8.21.0',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":11,\"name\":\"Welkar Stealth Square Dial Analog Quartz\",\"price\":\"899.00\"}','2026-10-13 10:51:23'),
(20,'view_item','36097a19361662ffb92fa9e55ded469f',NULL,'/product/welkar-3-piece-transparent-travel-washbag-set','http://localhost:8080/catalog?filter=new-arrivals','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":24,\"name\":\"Welkar 3-Piece Transparent Travel Washbag & Storage Set\",\"price\":\"699.00\"}','2026-10-13 11:03:40'),
(21,'view_item','sess_6be841815ad935916c5f2dd2a0e052ac',NULL,'/product/welkar-stealth-square-dial-analog-quartz',NULL,'::1','curl/8.21.0',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":11,\"name\":\"Welkar Stealth Square Dial Analog Quartz\",\"price\":\"899.00\"}','2026-10-13 11:03:43'),
(22,'view_item','f5ed5f9f772d05b2d778c5b2e0ed0561',NULL,'/product/welkar-hydro-lab-submersion-master','http://localhost:8080/','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":13,\"name\":\"Welkar Hydro-Lab Submersion Master Edition\",\"price\":\"999.00\"}','2026-10-15 16:55:58'),
(23,'view_item','sess_3ebe46234302823bd4c27411673ef25b',NULL,'/product/welkar-royale-classic-roman-gold',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.26100.9711',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-15 17:28:06'),
(24,'view_item','sess_975527274e1ecbdcb06f345ba617eb1a',NULL,'/product/welkar-royale-classic-roman-gold',NULL,'::1','Unknown',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-15 17:28:39'),
(25,'view_item','sess_975527274e1ecbdcb06f345ba617eb1a',NULL,'/product/welkar-royale-classic-roman-gold',NULL,'::1','Unknown',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-15 17:31:42'),
(26,'view_item','sess_975527274e1ecbdcb06f345ba617eb1a',NULL,'/product/welkar-royale-classic-roman-gold',NULL,'::1','Unknown',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-15 17:33:15'),
(27,'view_item','f5ed5f9f772d05b2d778c5b2e0ed0561',NULL,'/product/welkar-black-out-sport-racer','http://localhost:8080/collections/mens-watches','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":16,\"name\":\"Welkar Black-Out Sport Racer with Tachymeter Bezel\",\"price\":\"949.00\"}','2026-10-15 17:33:38'),
(28,'view_item','sess_975527274e1ecbdcb06f345ba617eb1a',NULL,'/product/welkar-royale-classic-roman-gold',NULL,'::1','Unknown',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-15 17:33:42'),
(29,'view_item','f5ed5f9f772d05b2d778c5b2e0ed0561',NULL,'/product/welkar-black-out-sport-racer','http://localhost:8080/collections/mens-watches','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":16,\"name\":\"Welkar Black-Out Sport Racer with Tachymeter Bezel\",\"price\":\"949.00\"}','2026-10-15 17:37:22'),
(30,'view_item','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/product/welkar-royale-classic-roman-gold','http://localhost:8080/','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-15 18:58:35'),
(31,'view_item','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/product/welkar-black-out-sport-racer','http://localhost:8080/catalog?gender=Men','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":16,\"name\":\"Welkar Black-Out Sport Racer with Tachymeter Bezel\",\"price\":\"949.00\"}','2026-10-15 19:01:48'),
(32,'view_item','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/product/welkar-sunray-teal-dial-silver-metal-strap','http://localhost:8080/catalog?gender=Men','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":14,\"name\":\"Welkar Sunray Teal Dial Silver Metal Strap\",\"price\":\"899.00\"}','2026-10-15 19:02:11'),
(33,'add_to_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart/add','http://localhost:8080/product/welkar-sunray-teal-dial-silver-metal-strap','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"variant_id\":14,\"quantity\":1}','2026-10-15 19:02:21'),
(34,'view_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart','http://localhost:8080/product/welkar-sunray-teal-dial-silver-metal-strap','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":1}','2026-10-15 19:02:21'),
(35,'begin_checkout','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/checkout','http://localhost:8080/cart','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"value\":1120.82,\"items_count\":1}','2026-10-15 19:02:26'),
(36,'view_item','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/product/fossil-townsman-automatic-skeleton','http://localhost:8080/','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":7,\"name\":\"Fossil Townsman Automatic Skeleton\",\"price\":\"9495.00\"}','2026-10-15 19:06:41'),
(37,'add_to_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart/add','http://localhost:8080/product/fossil-townsman-automatic-skeleton','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"variant_id\":9,\"quantity\":1}','2026-10-15 19:06:47'),
(38,'begin_checkout','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/checkout','http://localhost:8080/product/fossil-townsman-automatic-skeleton','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"value\":12264.92,\"items_count\":2}','2026-10-15 19:06:47'),
(39,'add_to_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart/add','http://localhost:8080/product/fossil-townsman-automatic-skeleton','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"variant_id\":9,\"quantity\":1}','2026-10-15 19:06:58'),
(40,'view_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart','http://localhost:8080/product/fossil-townsman-automatic-skeleton','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":2}','2026-10-15 19:06:58'),
(41,'begin_checkout','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/checkout','http://localhost:8080/cart','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"value\":23469.02,\"items_count\":3}','2026-10-15 19:07:02'),
(42,'view_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart','http://localhost:8080/cart','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":2}','2026-10-15 19:07:14'),
(43,'view_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart','http://localhost:8080/cart','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":1}','2026-10-15 19:07:14'),
(44,'view_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart','http://localhost:8080/cart','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":0}','2026-10-15 19:07:15'),
(45,'view_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart','http://localhost:8080/cart','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":0}','2026-10-15 19:07:18'),
(46,'view_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart','http://localhost:8080/cart','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":0}','2026-10-15 19:07:19'),
(47,'view_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart','http://localhost:8080/product/fossil-townsman-automatic-skeleton','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":0}','2026-10-15 19:07:21'),
(48,'view_item','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/product/welkar-hydro-lab-submersion-master','http://localhost:8080/catalog','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":13,\"name\":\"Welkar Hydro-Lab Submersion Master Edition\",\"price\":\"999.00\"}','2026-10-15 19:07:27'),
(49,'add_to_cart','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/cart/add','http://localhost:8080/product/welkar-hydro-lab-submersion-master','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"variant_id\":13,\"quantity\":1}','2026-10-15 19:07:30'),
(50,'begin_checkout','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/checkout','http://localhost:8080/product/welkar-hydro-lab-submersion-master','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"value\":1238.82,\"items_count\":1}','2026-10-15 19:07:30'),
(51,'view_item','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/product/welkar-black-out-sport-racer','http://localhost:8080/collections/mens-watches','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":16,\"name\":\"Welkar Black-Out Sport Racer with Tachymeter Bezel\",\"price\":\"949.00\"}','2026-10-15 19:12:06'),
(52,'view_item','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/product/welkar-royale-classic-roman-gold','http://localhost:8080/','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-15 19:33:30'),
(53,'view_item','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/product/welkar-royale-classic-roman-gold','http://localhost:8080/','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-15 19:58:33'),
(54,'view_item','deee85ecf919bd2e1a38ebac30d45c2f',NULL,'/product/welkar-royale-classic-roman-gold','http://localhost:8080/','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-15 19:58:53'),
(55,'view_item','sess_3ebe46234302823bd4c27411673ef25b',NULL,'/product/welkar-royale-classic-roman-gold',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-IN) WindowsPowerShell/5.1.26100.9711',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-15 23:54:01'),
(56,'view_cart','138d13aedea6426d430ac1297c067a24',NULL,'/cart',NULL,'::1','Go-http-client/1.1',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":0}','2026-10-16 11:49:16'),
(57,'view_cart','62b8b5be5f5ff5fa6b629299ed189f24',NULL,'/cart',NULL,'::1','Go-http-client/1.1',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":0}','2026-10-16 11:49:16'),
(58,'view_item','fcd579cc3b83bbccf1bb87bd94b26bd2',NULL,'/product/welkar-royale-classic-roman-gold','http://localhost:8000/','127.0.0.1','Mozilla/5.0 (Linux; Android 16; Pixel 10) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":3,\"name\":\"Welkar Royale Classic Roman Gold\",\"price\":\"2899.00\"}','2026-10-16 17:39:22'),
(59,'add_to_cart','fcd579cc3b83bbccf1bb87bd94b26bd2',NULL,'/cart/add','http://localhost:8000/product/welkar-royale-classic-roman-gold','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"variant_id\":5,\"quantity\":1}','2026-10-16 17:39:42'),
(60,'view_cart','fcd579cc3b83bbccf1bb87bd94b26bd2',NULL,'/cart','http://localhost:8000/product/welkar-royale-classic-roman-gold','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":1}','2026-10-16 17:39:42'),
(61,'view_cart','fcd579cc3b83bbccf1bb87bd94b26bd2',NULL,'/cart','http://localhost:8000/cart','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"items_count\":0}','2026-10-16 17:40:00'),
(62,'view_item','sess_7b528a6edf9a3e25d6948bd932c29aa6',NULL,'/product/welkar-junior-shock-guard-digital-led-blaze-orange','http://localhost:8000/','127.0.0.1','Mozilla/5.0 (Linux; Android 16; Pixel 10) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":21,\"name\":\"Welkar Junior Shock-Guard Digital LED Watch - Blaze Orange\",\"price\":\"499.00\"}','2026-10-16 18:14:46'),
(63,'view_item','d86017f8ffeeb7cb1a005036e2380cf4',NULL,'/product/welkar-black-out-sport-racer','http://localhost:8000/collections/mens-watches','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36',NULL,NULL,NULL,NULL,NULL,'{\"product_id\":16,\"name\":\"Welkar Black-Out Sport Racer with Tachymeter Bezel\",\"price\":\"949.00\"}','2026-10-16 18:25:38');
/*!40000 ALTER TABLE `analytics_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analytics_integrations`
--

DROP TABLE IF EXISTS `analytics_integrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analytics_integrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(50) NOT NULL,
  `is_connected` tinyint(1) DEFAULT 0,
  `credentials_json` longtext DEFAULT NULL,
  `last_synced_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider` (`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics_integrations`
--

LOCK TABLES `analytics_integrations` WRITE;
/*!40000 ALTER TABLE `analytics_integrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `analytics_integrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` enum('admin','customer','system') NOT NULL DEFAULT 'system',
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(80) NOT NULL,
  `entity_id` varchar(100) DEFAULT NULL,
  `old_values` longtext DEFAULT NULL,
  `new_values` longtext DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_al_action` (`action`),
  KEY `idx_al_entity` (`entity_type`,`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner_translations`
--

DROP TABLE IF EXISTS `banner_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banner_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banner_id` int(10) unsigned NOT NULL,
  `locale` varchar(10) NOT NULL,
  `title` varchar(150) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `button_text` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_bt` (`banner_id`,`locale`),
  CONSTRAINT `fk_bt_banner` FOREIGN KEY (`banner_id`) REFERENCES `banners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner_translations`
--

LOCK TABLES `banner_translations` WRITE;
/*!40000 ALTER TABLE `banner_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `banner_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image_desktop` varchar(255) NOT NULL,
  `image_mobile` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) NOT NULL DEFAULT '/catalog',
  `button_text` varchar(80) NOT NULL DEFAULT 'Explore Collection',
  `display_order` int(11) DEFAULT 0,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES
(1,'Pure Precision. Timeless Steel.','Discover the Welkar Aura Minimalist Collection crafted with Japanese Quartz movement.','/assets/images/banners/hero-aura-desk.jpg','/assets/images/banners/hero-aura-mob.jpg','/product/welkar-aura-white-silver-steel','Explore Aura Series',1,NULL,NULL,1,'2026-10-12 16:57:27'),
(2,'Pick Your Perfect Dial','From Classic Roman to High-Precision Chronographs. Find your aesthetic.','/assets/images/banners/hero-dials-desk.jpg','/assets/images/banners/hero-dials-mob.jpg','/catalog?filter=dial_types','Shop By Dial Style',2,NULL,NULL,1,'2026-10-12 16:57:27');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `logo_url` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES
(1,'Welkar','welkar','/assets/images/brands/welkar.png','Welkar Signature Timepieces: Engineered for modern precision and classic Indian elegance.',1,1,'2026-10-12 16:57:27'),
(2,'Titan','titan','/assets/images/brands/titan.png','Iconic craftsmanship with contemporary aesthetics.',1,1,'2026-10-12 16:57:27'),
(3,'Casio','casio','/assets/images/brands/casio.png','Renowned durability and innovative digital-analog performance.',1,1,'2026-10-12 16:57:27'),
(4,'Fossil','fossil','/assets/images/brands/fossil.png','Vintage authentic American style with modern watchmaking.',1,1,'2026-10-12 16:57:27'),
(5,'Timex','timex','/assets/images/brands/timex.png','True heritage watchmaking since 1854.',1,1,'2026-10-12 16:57:27');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(160) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_spend` decimal(10,2) DEFAULT 0.00,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_events`
--

DROP TABLE IF EXISTS `cart_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint(20) unsigned NOT NULL,
  `event_type` varchar(60) NOT NULL,
  `session_id` varchar(120) NOT NULL,
  `payload` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_events`
--

LOCK TABLES `cart_events` WRITE;
/*!40000 ALTER TABLE `cart_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_ci_cart_var` (`cart_id`,`variant_id`),
  KEY `fk_ci_var` (`variant_id`),
  CONSTRAINT `fk_ci_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ci_var` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES
(41,117,13,1,999.00,'2026-10-15 19:07:30','2026-10-15 19:07:30');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(120) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`),
  KEY `idx_cart_session` (`session_id`),
  KEY `idx_cart_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES
(1,'test_sess_d968447ff2b3',1,NULL,'2026-10-12 17:13:54','2026-10-12 17:13:54'),
(2,'test_sess_c531e99841e8',1,NULL,'2026-10-12 17:14:20','2026-10-12 17:14:20'),
(3,'test_sess_d823fd5d96a3',1,NULL,'2026-10-12 17:14:38','2026-10-12 17:14:38'),
(4,'test_sess_8e602200555c',2,NULL,'2026-10-12 17:14:55','2026-10-12 17:14:55'),
(5,'4fc10b65fb041bb372ee9e07ec2eb7b4',NULL,NULL,'2026-10-12 17:16:20','2026-10-12 17:16:20'),
(6,'6a0928ac98dab4a9446265f49fe2414c',NULL,NULL,'2026-10-12 17:17:17','2026-10-12 17:17:17'),
(7,'test_sess_e0fa8ad4c9f8',3,NULL,'2026-10-12 17:17:32','2026-10-12 17:17:32'),
(8,'54f0347bb82248e119d0fad4c10cac07',NULL,NULL,'2026-10-12 17:19:19','2026-10-12 17:19:19'),
(9,'49f65655df1f7de2255b9bd06dc65b8b',NULL,NULL,'2026-10-12 17:19:26','2026-10-12 17:19:26'),
(10,'26af4e70d1ffc055c2193f0de6c17950',NULL,NULL,'2026-10-12 17:19:37','2026-10-12 17:19:37'),
(11,'bdffc4fc54cb18854c08013c7f999191',NULL,NULL,'2026-10-12 17:37:07','2026-10-12 17:37:07'),
(12,'f9ff580f91f1e97ccf422870546e4f53',NULL,NULL,'2026-10-12 17:37:52','2026-10-12 17:37:52'),
(13,'test_sess_2776af79f06a',4,NULL,'2026-10-12 17:37:59','2026-10-12 17:37:59'),
(14,'70b00db384f677e2d96479bbe1d64411',NULL,NULL,'2026-10-12 17:47:06','2026-10-12 17:47:06'),
(15,'44cfac12d4dc7633eb9f57ff6bb78b60',NULL,NULL,'2026-10-12 17:47:06','2026-10-12 17:47:06'),
(16,'5e2bc315a7511b244686dcedecb9967a',NULL,NULL,'2026-10-12 17:47:06','2026-10-12 17:47:06'),
(17,'test_sess_04975bfe57bf',5,NULL,'2026-10-12 17:47:20','2026-10-12 17:47:20'),
(18,'test_sess_cf5189ac9407',6,NULL,'2026-10-12 17:57:04','2026-10-12 17:57:04'),
(19,'test_sess_22098470b78a',7,NULL,'2026-10-12 18:15:12','2026-10-12 18:15:12'),
(20,'85277fd12563831a28d29ef6c75bcf39',NULL,NULL,'2026-10-12 18:27:28','2026-10-12 18:27:28'),
(21,'test_sess_eeabd59dfbec',8,NULL,'2026-10-12 18:37:33','2026-10-12 18:37:33'),
(22,'test_sess_73c5057550a0',9,NULL,'2026-10-12 18:38:23','2026-10-12 18:38:23'),
(23,'test_sess_8500413779dc',10,NULL,'2026-10-12 18:39:44','2026-10-12 18:39:44'),
(24,'656c7bb8d88ffb864f67cff24e5a8f5d',NULL,NULL,'2026-10-12 18:40:41','2026-10-12 18:40:41'),
(25,'88eb26f1c62d8eaffb82b82edf75f5c3',NULL,NULL,'2026-10-12 19:13:54','2026-10-12 19:13:54'),
(26,'c6df89b4bb22bce6dae68ec2f0ae7185',NULL,NULL,'2026-10-12 19:13:54','2026-10-12 19:13:54'),
(27,'589e02309c1e3d00a55430b0b214f653',NULL,NULL,'2026-10-12 19:13:54','2026-10-12 19:13:54'),
(28,'test_sess_f68010260277',11,NULL,'2026-10-12 19:14:05','2026-10-12 19:14:05'),
(29,'712e9f4c339a699ba9d9e55b1660d9a7',NULL,NULL,'2026-10-12 19:26:33','2026-10-12 19:26:33'),
(30,'test_sess_ef33be22497e',12,NULL,'2026-10-12 19:30:22','2026-10-12 19:30:22'),
(31,'test_sess_2bce4ad1b065',13,NULL,'2026-10-12 19:36:35','2026-10-12 19:36:35'),
(32,'9aa5188142d5e7176891ab806979d839',NULL,NULL,'2026-10-12 19:37:13','2026-10-12 19:37:13'),
(33,'72f61c898a725b4337fa08abc65adf6f',NULL,NULL,'2026-10-12 19:37:27','2026-10-12 19:37:27'),
(34,'922e07cb803b1bdf16f8221f0b2da9d5',NULL,NULL,'2026-10-12 19:37:35','2026-10-12 19:37:35'),
(35,'adfb45c3fa3b881bd3f3cd74afad3d20',NULL,NULL,'2026-10-12 19:37:47','2026-10-12 19:37:47'),
(36,'test_sess_89083a13b84e',14,NULL,'2026-10-12 19:37:53','2026-10-12 19:37:53'),
(37,'8a14ccb1a4d89632166b9d6608cf4e46',NULL,NULL,'2026-10-12 19:43:17','2026-10-12 19:43:17'),
(38,'test_sess_d14c8f45ab74',15,NULL,'2026-10-12 19:43:27','2026-10-12 19:43:27'),
(39,'test_sess_f2f9990ccf39',16,NULL,'2026-10-13 09:24:43','2026-10-13 09:24:43'),
(40,'4a2f97ea84db6284fea992d76c9f0153',NULL,NULL,'2026-10-13 09:25:11','2026-10-13 09:25:11'),
(41,'54a6d197aa3eb656bf069ca70a475ca5',NULL,NULL,'2026-10-13 09:25:44','2026-10-13 09:25:44'),
(42,'988992e5625817fc6214ec0b10538bb1',NULL,NULL,'2026-10-13 09:26:03','2026-10-13 09:26:03'),
(43,'69cc2d622be434a26aae7a8454cfa550',NULL,NULL,'2026-10-13 09:26:10','2026-10-13 09:26:10'),
(44,'a931b0e9afc4bcc60b17028621db202c',NULL,NULL,'2026-10-13 09:26:16','2026-10-13 09:26:16'),
(45,'2704c5a22191e59033cfd33ce10c6b7a',NULL,NULL,'2026-10-13 09:26:34','2026-10-13 09:26:34'),
(46,'test_sess_ac9c4fbc0960',17,NULL,'2026-10-13 09:26:41','2026-10-13 09:26:41'),
(47,'bdcf4d492d38658b4907fa8f700a89f4',NULL,NULL,'2026-10-13 09:26:55','2026-10-13 09:26:55'),
(48,'9c61cc025403a18bdd9bf7b83b500823',NULL,NULL,'2026-10-13 09:26:55','2026-10-13 09:26:55'),
(49,'7fc3e015871ec94fbb95220a83d25403',NULL,NULL,'2026-10-13 09:26:55','2026-10-13 09:26:55'),
(50,'36097a19361662ffb92fa9e55ded469f',NULL,NULL,'2026-10-13 09:27:12','2026-10-13 09:27:12'),
(51,'7612e22ee06517c7a0c4001e337f1fba',NULL,NULL,'2026-10-13 09:39:45','2026-10-13 09:39:45'),
(52,'test_sess_a17e9651d4db',18,NULL,'2026-10-13 09:39:53','2026-10-13 09:39:53'),
(53,'bee0cdbac5460b279abed05604e74531',NULL,NULL,'2026-10-13 09:49:26','2026-10-13 09:49:26'),
(54,'test_sess_a5e534f0038e',19,NULL,'2026-10-13 09:49:34','2026-10-13 09:49:34'),
(55,'test_sess_2a70ab7f97de',20,NULL,'2026-10-13 10:40:12','2026-10-13 10:40:12'),
(56,'test_sess_699bfa5f1cbd',21,NULL,'2026-10-13 10:48:03','2026-10-13 10:48:03'),
(57,'test_sess_38fcfb311b7a',22,NULL,'2026-10-13 10:48:37','2026-10-13 10:48:37'),
(58,'1ba137a5c3f81c1d6ce91df0a740b617',NULL,NULL,'2026-10-13 10:50:54','2026-10-13 10:50:54'),
(59,'test_sess_3d41a304be16',23,NULL,'2026-10-13 10:50:58','2026-10-13 10:50:58'),
(60,'16cebd1b7c3198cca54ce8372c3b639c',NULL,NULL,'2026-10-13 10:51:03','2026-10-13 10:51:03'),
(61,'84610f053f98674ad985c5a03c81a4cd',NULL,NULL,'2026-10-13 10:51:07','2026-10-13 10:51:07'),
(62,'0616eda858d938babd6069915732a6ec',NULL,NULL,'2026-10-13 10:51:11','2026-10-13 10:51:11'),
(63,'223fad5d1543d055415f7681b2b7d83e',NULL,NULL,'2026-10-13 10:51:23','2026-10-13 10:51:23'),
(64,'test_sess_176cbe9f3317',24,NULL,'2026-10-13 10:51:51','2026-10-13 10:51:51'),
(65,'dc1c404b0106ff285e717d215daa05c3',NULL,NULL,'2026-10-13 11:02:44','2026-10-13 11:02:44'),
(66,'d7a1e4b14e981f05c41702607b0b8d72',NULL,NULL,'2026-10-13 11:02:50','2026-10-13 11:02:50'),
(67,'b154d863fd0d01a0b1b73dc2e754d08b',NULL,NULL,'2026-10-13 11:02:50','2026-10-13 11:02:50'),
(68,'b250e898b43c2301e6aff33f6b8b62e6',NULL,NULL,'2026-10-13 11:03:43','2026-10-13 11:03:43'),
(69,'test_sess_16c506732391',25,NULL,'2026-10-13 11:03:48','2026-10-13 11:03:48'),
(70,'a73e6ca42dd332415ec08faf43401212',NULL,NULL,'2026-10-13 11:37:28','2026-10-13 11:37:28'),
(71,'7a624d59d4be8c28e92ca20b1c93df22',NULL,NULL,'2026-10-13 11:37:28','2026-10-13 11:37:28'),
(72,'482b93bf8cc1f3fa0aa98c4157f11b95',NULL,NULL,'2026-10-13 11:37:38','2026-10-13 11:37:38'),
(73,'b005ef5f14ece444253bfab0668de93c',NULL,NULL,'2026-10-13 11:37:46','2026-10-13 11:37:46'),
(74,'test_sess_e3556daa853e',26,NULL,'2026-10-13 11:37:53','2026-10-13 11:37:53'),
(75,'b6752b8bacc3905172afcfaf9b044f76',NULL,NULL,'2026-10-13 11:44:45','2026-10-13 11:44:45'),
(76,'test_sess_7d56349e7af6',27,NULL,'2026-10-13 11:44:52','2026-10-13 11:44:52'),
(77,'79f5b694e148dfd3575ee6c9ac79993e',NULL,NULL,'2026-10-13 11:54:02','2026-10-13 11:54:02'),
(78,'9cd6e5fa117e469a72da0e90bf851477',NULL,NULL,'2026-10-13 11:54:07','2026-10-13 11:54:07'),
(79,'test_sess_4fe164317602',28,NULL,'2026-10-13 11:54:13','2026-10-13 11:54:13'),
(80,'dda96d208d19f0df5e269f68bf77068d',NULL,NULL,'2026-10-13 11:55:51','2026-10-13 11:55:51'),
(81,'fc84af818c385d700dba79221839ea55',NULL,NULL,'2026-10-13 12:00:20','2026-10-13 12:00:20'),
(82,'8111980da9bb07c7beb393299ed15d7b',NULL,NULL,'2026-10-13 12:00:28','2026-10-13 12:00:28'),
(83,'test_sess_4fd0a0ddc895',29,NULL,'2026-10-13 12:00:35','2026-10-13 12:00:35'),
(84,'264f8e4f729cef0a14a52298bab0e9ed',NULL,NULL,'2026-10-13 12:10:27','2026-10-13 12:10:27'),
(85,'2b8e33f6938d565c8772c211a1bb372e',NULL,NULL,'2026-10-13 12:10:32','2026-10-13 12:10:32'),
(86,'8286f2f1126c4422cba7236fb5b6f416',NULL,NULL,'2026-10-13 12:10:39','2026-10-13 12:10:39'),
(87,'test_sess_f2e8a8053751',30,NULL,'2026-10-13 12:10:44','2026-10-13 12:10:44'),
(88,'8c3a4ab2aa5daf2f0d0e21ba2afca9ca',NULL,NULL,'2026-10-13 12:22:19','2026-10-13 12:22:19'),
(89,'test_sess_302b2031c3f3',31,NULL,'2026-10-13 12:22:26','2026-10-13 12:22:26'),
(90,'cd9a9f2d2801e676d53b541b9bb6f586',NULL,NULL,'2026-10-13 12:24:41','2026-10-13 12:24:41'),
(91,'test_sess_1785c633d892',32,NULL,'2026-10-13 12:24:48','2026-10-13 12:24:48'),
(92,'3bc207b5fd684432d31d31fe09d1c208',NULL,NULL,'2026-10-13 12:37:13','2026-10-13 12:37:13'),
(93,'test_sess_9a33099cfb34',33,NULL,'2026-10-13 12:37:22','2026-10-13 12:37:22'),
(94,'test_sess_de907c6b0b88',34,NULL,'2026-10-13 15:09:57','2026-10-13 15:09:57'),
(95,'4cb04f954ffcd17ad60aa4123fe52ce6',NULL,NULL,'2026-10-13 15:11:10','2026-10-13 15:11:10'),
(96,'c5d2be0dababd4d0d9a98434776534ea',NULL,NULL,'2026-10-13 15:11:17','2026-10-13 15:11:17'),
(97,'4ee02d4bdfe04a5f17fa4b2fc577d080',NULL,NULL,'2026-10-13 15:15:23','2026-10-13 15:15:23'),
(98,'16f10885f3c5f516a4dfafb123524e48',NULL,NULL,'2026-10-13 15:15:26','2026-10-13 15:15:26'),
(99,'5a6fb09884b9e920bd1b3e0dedea70d0',NULL,NULL,'2026-10-13 15:16:48','2026-10-13 15:16:48'),
(100,'c952eab6fac7f6089ab5973d0c1bbcb4',NULL,NULL,'2026-10-13 19:08:53','2026-10-13 19:08:53'),
(101,'f2edd2b4c6a2780b70beefd5201fa1d9',NULL,NULL,'2026-10-15 16:19:59','2026-10-15 16:19:59'),
(102,'f5ed5f9f772d05b2d778c5b2e0ed0561',NULL,NULL,'2026-10-15 16:20:28','2026-10-15 16:20:28'),
(103,'d7bd1829a89c7f978580e555c3c1355e',NULL,NULL,'2026-10-15 16:31:17','2026-10-15 16:31:17'),
(104,'e5ee3f8c1b9e0996f466f4ea7ff1cfeb',NULL,NULL,'2026-10-15 16:59:24','2026-10-15 16:59:24'),
(105,'565720f24c3e34e4db6fc3020e40a291',NULL,NULL,'2026-10-15 16:59:31','2026-10-15 16:59:31'),
(106,'5804f2c7c584c0a5ab8fab8b124c8e31',NULL,NULL,'2026-10-15 16:59:37','2026-10-15 16:59:37'),
(107,'083832e7edbea162bfea3fc76c2ca1e9',NULL,NULL,'2026-10-15 16:59:48','2026-10-15 16:59:48'),
(108,'fd996d4c4f915a3695bb441aaa1692df',NULL,NULL,'2026-10-15 16:59:54','2026-10-15 16:59:54'),
(109,'5e020afdd9c2e363afb2873a85ab75c8',NULL,NULL,'2026-10-15 17:00:01','2026-10-15 17:00:01'),
(110,'c9c3d2b5ed5829b10c7136dc60d68a9c',NULL,NULL,'2026-10-15 17:26:50','2026-10-15 17:26:50'),
(111,'14b4ea9e7d94cd8c06986eb8d0d5b7ec',NULL,NULL,'2026-10-15 17:28:06','2026-10-15 17:28:06'),
(112,'1db65f6e96abb614788bace1d2a2152d',NULL,NULL,'2026-10-15 17:28:39','2026-10-15 17:28:39'),
(113,'d2561198835a940ec86231c545f3f7c7',NULL,NULL,'2026-10-15 17:31:42','2026-10-15 17:31:42'),
(114,'613261a116da35f3dfeb46dfe65d07eb',NULL,NULL,'2026-10-15 17:33:15','2026-10-15 17:33:15'),
(115,'9a25e9d5c0cf8265b607ef7f8a6254bb',NULL,NULL,'2026-10-15 17:33:42','2026-10-15 17:33:42'),
(116,'0037da1614767f998500fc226111606c',NULL,NULL,'2026-10-15 18:56:21','2026-10-15 18:56:21'),
(117,'deee85ecf919bd2e1a38ebac30d45c2f',NULL,NULL,'2026-10-15 18:56:53','2026-10-15 18:56:53'),
(118,'b2a186271fe0e973c5d930cea41251bd',NULL,NULL,'2026-10-15 23:19:33','2026-10-15 23:19:33'),
(119,'d616d134c0825ed4caa94bff0748c405',NULL,NULL,'2026-10-15 23:22:26','2026-10-15 23:22:26'),
(120,'0476789d33ed994ccb44c3c91c16995e',NULL,NULL,'2026-10-15 23:43:51','2026-10-15 23:43:51'),
(121,'7b9d1c895418b6e0a6e29804772e8fe5',NULL,NULL,'2026-10-15 23:52:48','2026-10-15 23:52:48'),
(122,'428ea53660ed4bca10fe4377252d1c90',NULL,NULL,'2026-10-15 23:52:59','2026-10-15 23:52:59'),
(123,'3705ea1bc614613dfa4916948572c1c5',NULL,NULL,'2026-10-15 23:53:09','2026-10-15 23:53:09'),
(124,'191b41b8c1a8442a961a2ba06641fc06',NULL,NULL,'2026-10-15 23:53:21','2026-10-15 23:53:21'),
(125,'d7920fd58e9d03b55cfc9c86e9609b4c',NULL,NULL,'2026-10-15 23:53:36','2026-10-15 23:53:36'),
(126,'8d30f944c0bfca5cf862b2986b570e4e',NULL,NULL,'2026-10-15 23:54:01','2026-10-15 23:54:01'),
(127,'f28cdf1f12396b5a4136108cb388a73f',NULL,NULL,'2026-10-15 23:54:11','2026-10-15 23:54:11'),
(128,'b608b36d1f68bfcddcc8fabefb8a9590',NULL,NULL,'2026-10-15 23:55:02','2026-10-15 23:55:02'),
(129,'a80c9f7fb6ea3ce561aef592ed9ae373',NULL,NULL,'2026-10-16 00:09:21','2026-10-16 00:09:21'),
(130,'5a9b4a40e1ee0ef164196a357a3c89bc',NULL,NULL,'2026-10-16 00:13:03','2026-10-16 00:13:03'),
(131,'7c6f2c1273c7c21e28988ce35882710a',NULL,NULL,'2026-10-16 10:19:11','2026-10-16 10:19:11'),
(132,'48ae920124753b9006ec1ad370ac1070',NULL,NULL,'2026-10-16 10:20:05','2026-10-16 10:20:05'),
(133,'885e5a8f837ead0dba062da7c33a9e04',NULL,NULL,'2026-10-16 10:34:16','2026-10-16 10:34:16'),
(134,'6f1bb9a706a55da7dd5d48f1b986dd85',NULL,NULL,'2026-10-16 11:48:49','2026-10-16 11:48:49'),
(135,'666919a8d4d60c01b8f4a8a79010d732',NULL,NULL,'2026-10-16 11:48:49','2026-10-16 11:48:49'),
(136,'dedf9f5069ee1f54dc0da3e957912ff3',NULL,NULL,'2026-10-16 11:49:00','2026-10-16 11:49:00'),
(137,'d324b03f1fd279ff9a1c39c2be4218b6',NULL,NULL,'2026-10-16 11:49:00','2026-10-16 11:49:00'),
(138,'1c1aae5bd72a8b62536b3106498a0b66',NULL,NULL,'2026-10-16 11:49:11','2026-10-16 11:49:11'),
(139,'6a495801c34c4aea8c01fb6a1e6b46b3',NULL,NULL,'2026-10-16 11:49:11','2026-10-16 11:49:11'),
(140,'138d13aedea6426d430ac1297c067a24',NULL,NULL,'2026-10-16 11:49:16','2026-10-16 11:49:16'),
(141,'62b8b5be5f5ff5fa6b629299ed189f24',NULL,NULL,'2026-10-16 11:49:16','2026-10-16 11:49:16'),
(142,'410b30b48b1e43a79f1120ee300048aa',NULL,NULL,'2026-10-16 11:50:46','2026-10-16 11:50:46'),
(143,'9325d61fc724c042df590279ca2b6dc3',NULL,NULL,'2026-10-16 11:51:24','2026-10-16 11:51:24'),
(144,'070b26b3097da9a2f65da4296122838a',NULL,NULL,'2026-10-16 11:51:24','2026-10-16 11:51:24'),
(145,'22fcf8fa758dea952e362f159df61685',NULL,NULL,'2026-10-16 15:48:24','2026-10-16 15:48:24'),
(146,'fcd579cc3b83bbccf1bb87bd94b26bd2',NULL,NULL,'2026-10-16 16:02:19','2026-10-16 16:02:19'),
(147,'3809674101852a888ee8ee7874a6db95',NULL,NULL,'2026-10-16 16:45:44','2026-10-16 16:45:44'),
(148,'d86017f8ffeeb7cb1a005036e2380cf4',NULL,NULL,'2026-10-16 18:14:46','2026-10-16 18:14:46'),
(149,'1743f2fa2285f5eb5d7e15de6cc89893',NULL,NULL,'2026-10-16 19:17:01','2026-10-16 19:17:01');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_cat_parent` (`parent_id`),
  CONSTRAINT `fk_cat_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,NULL,'Men\'s Watches','mens-watches','Distinguished chronographs, automatics, and dress watches crafted for men.','/assets/images/categories/cat-men.jpg',1,1,'2026-10-12 16:57:27'),
(2,NULL,'Women\'s Watches','womens-watches','Elegant, minimalist, and statement timepieces tailored for women.','/assets/images/categories/cat-women.jpg',2,1,'2026-10-12 16:57:27'),
(3,NULL,'Unisex Watches','unisex-watches','Versatile and modern silhouettes suited for any wrist.','/assets/images/categories/cat-unisex.jpg',3,1,'2026-10-12 16:57:27'),
(4,NULL,'Luxury Watches','luxury-watches','Sapphire crystal, mechanical automatic movements, and premium stainless steel.','/assets/images/categories/cat-luxury.jpg',4,1,'2026-10-12 16:57:27'),
(5,NULL,'Sports Watches','sports-watches','Rugged, water-resistant, shockproof watches engineered for endurance.','/assets/images/categories/cat-sports.jpg',5,1,'2026-10-12 16:57:27'),
(6,NULL,'Kids & Youth','kids-and-youth','Shock-resistant and vibrant everyday sports watches engineered for young explorers.','/assets/images/categories/cat-youth.jpg',6,1,'2026-10-13 10:38:42'),
(7,NULL,'Travel & Organizers','travel-organizers','High-frequency welded transparent cosmetic washbags and modular waterproof pouches.','/assets/images/categories/cat-travel.jpg',7,1,'2026-10-13 10:38:42');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(160) NOT NULL,
  `description` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collections`
--

LOCK TABLES `collections` WRITE;
/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
INSERT INTO `collections` VALUES
(1,'Best Sellers','best-sellers','India\'s most loved watches, backed by verified buyer satisfaction.','/assets/images/collections/best-sellers.jpg',1,'2026-10-12 16:57:27'),
(2,'New Arrivals','new-arrivals','Fresh drops featuring our latest dial designs and premium straps.','/assets/images/collections/new-arrivals.jpg',1,'2026-10-12 16:57:27'),
(3,'Pick Your Perfect Dial','pick-your-perfect-dial','Explore watches categorized by unique dial styles from Roman numerals to Chronographs.','/assets/images/collections/dial-styles.jpg',1,'2026-10-12 16:57:27'),
(4,'Executive Steel','executive-steel','Solid stainless steel mesh and link bracelets for boardroom elegance.','/assets/images/collections/steel.jpg',1,'2026-10-12 16:57:27');
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_founders`
--

DROP TABLE IF EXISTS `company_founders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_founders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `designation` varchar(150) NOT NULL DEFAULT 'Co-Founder',
  `perspective` varchar(150) NOT NULL,
  `bio` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `image_alt` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_founders`
--

LOCK TABLES `company_founders` WRITE;
/*!40000 ALTER TABLE `company_founders` DISABLE KEYS */;
INSERT INTO `company_founders` VALUES
(1,'Mr. Sumit Singh','Co-Founder','Artistic Perspective','Mr. Sumit Singh is one of WELKAR\'s founders. His artistic perspective represents the creative side of the founding partnership and the importance of visual character, design expression, and attention to detail in the brand\'s identity.',NULL,'Mr. Sumit Singh, Co-Founder of WELKAR',1,1,'2026-10-15 23:41:17','2026-10-15 23:41:17'),
(2,'Mr. Nevil Sharma','Co-Founder','Technical Perspective','Mr. Nevil Sharma is one of WELKAR\'s founders. His technical perspective represents the problem-solving and technical thinking that complement the brand\'s artistic outlook.',NULL,'Mr. Nevil Sharma, Co-Founder of WELKAR',2,1,'2026-10-15 23:41:17','2026-10-15 23:41:17');
/*!40000 ALTER TABLE `company_founders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_milestones`
--

DROP TABLE IF EXISTS `company_milestones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_milestones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `year_date` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_milestones`
--

LOCK TABLES `company_milestones` WRITE;
/*!40000 ALTER TABLE `company_milestones` DISABLE KEYS */;
INSERT INTO `company_milestones` VALUES
(1,'2002','Founding of WELKAR','Mr. Sumit Singh and Mr. Nevil Sharma establish WELKAR, bringing artistic vision and technical problem-solving together to create a brand founded on precision, heritage, and trust.',NULL,1,1,'2026-10-15 23:41:17');
/*!40000 ALTER TABLE `company_milestones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_profile`
--

DROP TABLE IF EXISTS `company_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_profile` (
  `id` int(10) unsigned NOT NULL DEFAULT 1,
  `brand_name` varchar(100) NOT NULL DEFAULT 'WELKAR',
  `tagline` varchar(255) NOT NULL DEFAULT 'Precision, Heritage, and Trust—Since 2002.',
  `founding_year` varchar(10) NOT NULL DEFAULT '2002',
  `hero_title` varchar(255) NOT NULL DEFAULT 'Precision, Heritage, and Trust—Since 2002.',
  `hero_intro` text NOT NULL,
  `story_title` varchar(255) NOT NULL DEFAULT 'Our Story',
  `story_intro` text NOT NULL,
  `story_content` longtext NOT NULL,
  `philosophy_title` varchar(255) NOT NULL DEFAULT 'Our Philosophy',
  `philosophy_intro` text NOT NULL,
  `approach_watches_title` varchar(255) NOT NULL DEFAULT 'Our Approach to Watches',
  `approach_watches_content` longtext NOT NULL,
  `quality_title` varchar(255) NOT NULL DEFAULT 'Our Approach to Quality',
  `quality_content` longtext NOT NULL,
  `mission_statement` text NOT NULL,
  `vision_statement` text NOT NULL,
  `commitment_title` varchar(255) NOT NULL DEFAULT 'Our Commitment to Customers',
  `commitment_content` longtext NOT NULL,
  `journey_ahead_title` varchar(255) NOT NULL DEFAULT 'The Journey Ahead',
  `journey_ahead_content` text NOT NULL,
  `home_summary` text NOT NULL,
  `footer_summary` text NOT NULL,
  `pdp_summary` text NOT NULL,
  `collection_summary` text NOT NULL,
  `seo_title` varchar(255) NOT NULL DEFAULT 'About Welkar | Precision, Heritage, and Trust—Since 2002',
  `seo_description` varchar(255) NOT NULL DEFAULT 'Learn the story of WELKAR, founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma on the principles of precision, heritage, and lasting trust.',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_profile`
--

LOCK TABLES `company_profile` WRITE;
/*!40000 ALTER TABLE `company_profile` DISABLE KEYS */;
INSERT INTO `company_profile` VALUES
(1,'WELKAR','Precision, Heritage, and Trust—Since 2002.','2002','Precision, Heritage, and Trust—Since 2002.','WELKAR is a watch brand founded in 2002, shaped by an appreciation for design, technical thinking, and the importance of trust. Our story began with two founders bringing complementary perspectives together, united by a vision to build a brand around precision, heritage, and lasting style.','Our Story','WELKAR began in 2002 with the coming together of two complementary perspectives. Mr. Sumit Singh brought an artistic outlook, while Mr. Nevil Sharma brought a technical approach. Together, they established the foundation of a brand shaped by the ideas of precision, heritage, and trust.','Our company story reflects the importance of bringing creative vision and technical thinking together. These principles continue to inform the identity WELKAR seeks to build and the experience it aims to provide its customers.\n\nFrom the outset, our goal has been to create an authentic horological destination where design integrity meets reliable functionality. By combining artistic expression with structured problem-solving, WELKAR continues to offer timepieces designed to complement daily life with poise and dependability.','Our Philosophy','Guided by a genuine passion for watchmaking, our philosophy centers on timeless elegance, mechanical reliability, rigorous attention to detail, and collaboration with skilled craftspeople.','Our Approach to Watches','We believe a watch is more than an instrument for telling time; it is a personal statement of style and character. Our approach harmonizes distinctive visual aesthetics with everyday comfort and practical utility.\n\nEvery piece in our catalog is selected with keen attention to dial balance, tactile presence, case proportions, and clear technical specifications—giving customers the transparent knowledge they need to choose a timepiece they will be proud to wear for years to come.','Our Approach to Quality','Our commitment to quality begins with clarity, honesty, and verified specifications. Rather than making unsubstantiated claims, we focus on diligent product selection, transparent technical descriptions, and dependable customer information.\n\nWe specify movement calibers, casing materials, and water-resistance metrics straightforwardly, ensuring every customer receives honest value, accessible care guidance, and reliable service.','To offer watches that bring together thoughtful design, practical considerations, and a sense of lasting style, while giving customers clear information and a brand experience they can feel confident about.','To build WELKAR into a watch brand recognised for its identity, enduring design philosophy, and commitment to customer trust.','Our Commitment to Customers','We believe in building lasting relationships grounded in transparency and respect. That means providing comprehensive product information, upfront pricing, responsive customer support, accessible policy terms, and straightforward post-purchase assistance.','The Journey Ahead','Our journey continues with the same principles that define our identity: precision, heritage, and trust. We aim to keep building a thoughtful brand experience, communicate clearly with our customers, and offer watches people can feel proud to wear.','Founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, WELKAR unites artistic design with technical perspective to craft watches defined by precision, heritage, and trust.','Founded in 2002, WELKAR unites creative vision and technical precision to deliver timepieces built on heritage and trust.','Founded in 2002, WELKAR creates timepieces balancing thoughtful design, practical utility, and transparent quality.','Thoughtfully curated timepieces reflecting WELKAR\'s heritage of precision, balanced proportions, and timeless styling.','About WELKAR | Precision, Heritage, and Trust—Since 2002','Discover WELKAR, an Indian watch brand founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, defined by precision, heritage, and customer trust.','2026-10-15 23:41:17');
/*!40000 ALTER TABLE `company_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_values`
--

DROP TABLE IF EXISTS `company_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `tagline` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `icon_class` varchar(100) NOT NULL DEFAULT 'bi-check-circle',
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_values`
--

LOCK TABLES `company_values` WRITE;
/*!40000 ALTER TABLE `company_values` DISABLE KEYS */;
INSERT INTO `company_values` VALUES
(1,'Precision','Attention to Detail & Accuracy','We prioritize careful attention to product specifications, honest presentation, and transparent documentation across every interaction.','bi-bullseye',1,1,'2026-10-15 23:41:17'),
(2,'Heritage','Respect for our 2002 Origin','Our journey began in 2002. That history forms an essential part of our brand identity, guiding our evolution while honoring our founding roots.','bi-hourglass-split',2,1,'2026-10-15 23:41:17'),
(3,'Trust','Honest Communication & Transparency','We earn customer confidence through truthful product descriptions, clear policies, and dependable after-sales care.','bi-shield-check',3,1,'2026-10-15 23:41:17'),
(4,'Timelessness','Enduring Style Over Passing Fads','We appreciate classic horological aesthetics and create watches designed to gracefully accompany our customers through every season of life.','bi-compass',4,1,'2026-10-15 23:41:17'),
(5,'Responsibility','Fair & Accountable Customer Relations','We commit to communicating responsibly, addressing customer needs with integrity, and treating every patron fairly.','bi-handshake',5,1,'2026-10-15 23:41:17');
/*!40000 ALTER TABLE `company_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consent_records`
--

DROP TABLE IF EXISTS `consent_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consent_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `consent_type` varchar(80) NOT NULL,
  `granted` tinyint(1) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consent_records`
--

LOCK TABLES `consent_records` WRITE;
/*!40000 ALTER TABLE `consent_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `consent_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_pages`
--

DROP TABLE IF EXISTS `content_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) NOT NULL,
  `locale` varchar(10) NOT NULL DEFAULT 'en',
  `title` varchar(200) NOT NULL,
  `content_html` longtext NOT NULL,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_page_slug_loc` (`slug`,`locale`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_pages`
--

LOCK TABLES `content_pages` WRITE;
/*!40000 ALTER TABLE `content_pages` DISABLE KEYS */;
INSERT INTO `content_pages` VALUES
(1,'about-us','en','About WELKAR — Precision, Heritage, and Trust—Since 2002','<h2>Precision, Heritage, and Trust—Since 2002</h2><p>WELKAR is a watch brand founded in 2002, shaped by an appreciation for design, technical thinking, and the importance of trust. Our story began with two founders—Mr. Sumit Singh and Mr. Nevil Sharma—bringing complementary artistic and technical perspectives together.</p><p><a href=\"/about\" class=\"btn btn-sm btn-outline-dark mt-2\">Read the full interactive company profile &rarr;</a></p>','About WELKAR | Precision, Heritage, and Trust—Since 2002','Discover the founding story of WELKAR, established in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, guided by artistic design, technical perspective, and trust.',1,'2026-10-15 23:41:17'),
(2,'shipping-policy','en','Shipping & Delivery Policy','<h2>Domestic Shipping Across India</h2><p>Welkar delivers to serviceable pincodes across India via insured courier partners. Orders are dispatched within 24 to 48 business hours.</p><ul><li>Standard Delivery: 3 to 6 business days.</li><li>Free shipping on all prepaid and COD orders over ???1,499.</li><li>A flat shipping fee of ???60 applies to orders below ???1,499.</li><li>Real-time tracking links are provided via email upon dispatch.</li></ul>','Shipping Policy - Welkar Watches','Domestic shipping terms, delivery timelines, and courier tracking.',1,'2026-10-12 16:57:27'),
(3,'returns-exchanges','en','Returns & Replacement Policy','<h2>Hassle-Free 7-Day Returns</h2><p>We want you to love your timepiece. If you receive a damaged, defective, or incorrect watch, you may initiate a return or exchange request within 7 calendar days of delivery from your account dashboard.</p><p>Returned watches must be unused, in original packaging with protective films, tags, and invoice intact. Once verified by our inspection team, refunds are processed within 5-7 working days to the original payment source.</p>','Returns & Exchanges - Welkar','7-day return and exchange policy for Welkar watches.',1,'2026-10-12 16:57:27'),
(4,'warranty','en','Warranty Information','<h2>Official Warranty Coverage</h2><p>All Welkar private-label watches carry a 1 to 2-year manufacturer warranty against internal movement defects. Partner brands (Titan, Casio, Fossil, Timex) include their official brand warranty cards honored at authorized service centers across India.</p>','Warranty Terms - Welkar Timepieces','Official watch movement warranty terms and service coverage.',1,'2026-10-12 16:57:27'),
(5,'privacy-policy','en','Privacy Policy','<h2>Your Privacy Matters</h2><p>Welkar respects your personal privacy. We collect minimal customer information strictly necessary to process orders, comply with Indian tax regulations, and provide transactional shipping updates. We never store credit or debit card numbers on our servers.</p>','Privacy Policy - Welkar','How Welkar collects, uses, and protects customer data.',1,'2026-10-12 16:57:27'),
(6,'terms-conditions','en','Terms & Conditions','<h2>Terms of Service</h2><p>By placing an order on Welkar.in, you agree to our standard terms of sale. Prices are in Indian Rupees (INR) and applicable Goods and Services Tax (GST) is calculated at checkout based on the delivery destination.</p>','Terms & Conditions - Welkar','Terms and conditions governing online purchases on Welkar.',1,'2026-10-12 16:57:27'),
(7,'faqs','en','Frequently Asked Questions | WELKAR','<h2>Frequently Asked Questions</h2>\n<p class=\"text-muted\">Find answers to commonly asked questions about WELKAR, our founding story, timepiece specifications, and store policies.</p>\n\n<div class=\"accordion my-4\" id=\"welkarFaqAccordion\">\n  <div class=\"accordion-item border rounded mb-3\">\n    <h3 class=\"accordion-header\" id=\"faqHeading1\">\n      <button class=\"accordion-button fw-bold text-dark\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#faqCollapse1\" aria-expanded=\"true\" aria-controls=\"faqCollapse1\">\n        What is WELKAR?\n      </button>\n    </h3>\n    <div id=\"faqCollapse1\" class=\"accordion-collapse collapse show\" aria-labelledby=\"faqHeading1\">\n      <div class=\"accordion-body text-secondary\" style=\"line-height: 1.8;\">\n        WELKAR is a watch brand founded on the principles of thoughtful design, technical balance, and customer trust. We offer timepieces that harmonize distinctive visual character with practical utility, clear specifications, and accessible elegance.\n      </div>\n    </div>\n  </div>\n\n  <div class=\"accordion-item border rounded mb-3\">\n    <h3 class=\"accordion-header\" id=\"faqHeading2\">\n      <button class=\"accordion-button collapsed fw-bold text-dark\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#faqCollapse2\" aria-expanded=\"false\" aria-controls=\"faqCollapse2\">\n        When was WELKAR founded?\n      </button>\n    </h3>\n    <div id=\"faqCollapse2\" class=\"accordion-collapse collapse\" aria-labelledby=\"faqHeading2\">\n      <div class=\"accordion-body text-secondary\" style=\"line-height: 1.8;\">\n        WELKAR was established in <strong>2002</strong>. Over two decades of history guide our current brand identity and reinforce our ongoing commitment to customer trust.\n      </div>\n    </div>\n  </div>\n\n  <div class=\"accordion-item border rounded mb-3\">\n    <h3 class=\"accordion-header\" id=\"faqHeading3\">\n      <button class=\"accordion-button collapsed fw-bold text-dark\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#faqCollapse3\" aria-expanded=\"false\" aria-controls=\"faqCollapse3\">\n        Who founded WELKAR?\n      </button>\n    </h3>\n    <div id=\"faqCollapse3\" class=\"accordion-collapse collapse\" aria-labelledby=\"faqHeading3\">\n      <div class=\"accordion-body text-secondary\" style=\"line-height: 1.8;\">\n        WELKAR was founded by two partners bringing complementary perspectives together:\n        <ul>\n          <li><strong>Mr. Sumit Singh:</strong> Associated with the artistic outlook, representing creative vision, visual character, and attention to detailing.</li>\n          <li><strong>Mr. Nevil Sharma:</strong> Associated with the technical outlook, representing problem-solving, structural balance, and technical thinking.</li>\n        </ul>\n        Learn more on our <a href=\"/about\" class=\"text-decoration-underline fw-semibold\">About Us page</a>.\n      </div>\n    </div>\n  </div>\n\n  <div class=\"accordion-item border rounded mb-3\">\n    <h3 class=\"accordion-header\" id=\"faqHeading4\">\n      <button class=\"accordion-button collapsed fw-bold text-dark\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#faqCollapse4\" aria-expanded=\"false\" aria-controls=\"faqCollapse4\">\n        What does the WELKAR tagline mean?\n      </button>\n    </h3>\n    <div id=\"faqCollapse4\" class=\"accordion-collapse collapse\" aria-labelledby=\"faqHeading4\">\n      <div class=\"accordion-body text-secondary\" style=\"line-height: 1.8;\">\n        Our official tagline is <em>&ldquo;Precision, Heritage, and Trust—Since 2002.&rdquo;</em>\n        <ul>\n          <li><strong>Precision:</strong> Meticulous attention to dial proportion, finish, and accurate product specifications.</li>\n          <li><strong>Heritage:</strong> Respect for our journey that began in 2002.</li>\n          <li><strong>Trust:</strong> Honest communication, transparent pricing, insured transit, and customer-first service.</li>\n        </ul>\n      </div>\n    </div>\n  </div>\n\n  <div class=\"accordion-item border rounded mb-3\">\n    <h3 class=\"accordion-header\" id=\"faqHeading5\">\n      <button class=\"accordion-button collapsed fw-bold text-dark\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#faqCollapse5\" aria-expanded=\"false\" aria-controls=\"faqCollapse5\">\n        How can I contact WELKAR?\n      </button>\n    </h3>\n    <div id=\"faqCollapse5\" class=\"accordion-collapse collapse\" aria-labelledby=\"faqHeading5\">\n      <div class=\"accordion-body text-secondary\" style=\"line-height: 1.8;\">\n        You can reach our team directly at <strong>welkarstore@gmail.com</strong> for questions about watches, order tracking, and after-sales support.\n      </div>\n    </div>\n  </div>\n\n  <div class=\"accordion-item border rounded mb-3\">\n    <h3 class=\"accordion-header\" id=\"faqHeading6\">\n      <button class=\"accordion-button collapsed fw-bold text-dark\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#faqCollapse6\" aria-expanded=\"false\" aria-controls=\"faqCollapse6\">\n        Where can I read the company\'s policies?\n      </button>\n    </h3>\n    <div id=\"faqCollapse6\" class=\"accordion-collapse collapse\" aria-labelledby=\"faqHeading6\">\n      <div class=\"accordion-body text-secondary\" style=\"line-height: 1.8;\">\n        Our official store policies are publicly available at:\n        <ul>\n          <li><a href=\"/page/warranty\" class=\"text-decoration-underline\">Official Warranty Terms</a></li>\n          <li><a href=\"/page/returns-exchanges\" class=\"text-decoration-underline\">7-Day Return &amp; Exchange Policy</a></li>\n          <li><a href=\"/page/shipping-policy\" class=\"text-decoration-underline\">Shipping, Delivery &amp; COD Guidelines</a></li>\n          <li><a href=\"/page/privacy-policy\" class=\"text-decoration-underline\">Privacy Policy</a></li>\n        </ul>\n      </div>\n    </div>\n  </div>\n</div>','Frequently Asked Questions | WELKAR Watch Co.','Official answers about WELKAR, founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, including warranty, returns, and support.',1,'2026-10-15 23:51:16');
/*!40000 ALTER TABLE `content_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupon_redemptions`
--

DROP TABLE IF EXISTS `coupon_redemptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupon_redemptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` int(10) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `discount_applied` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_cr_coupon` (`coupon_id`),
  CONSTRAINT `fk_cr_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_redemptions`
--

LOCK TABLES `coupon_redemptions` WRITE;
/*!40000 ALTER TABLE `coupon_redemptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupon_redemptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_discount_value` decimal(10,2) DEFAULT NULL,
  `usage_limit_total` int(10) unsigned DEFAULT NULL,
  `usage_limit_per_user` int(10) unsigned DEFAULT 1,
  `usage_count` int(10) unsigned DEFAULT 0,
  `is_first_order_only` tinyint(1) DEFAULT 0,
  `starts_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES
(1,'WELKAR10','10% instant discount on orders above ???1,999','percentage',10.00,1999.00,1000.00,1000,1,0,0,NULL,NULL,1,'2026-10-12 16:57:27'),
(2,'WELCOME500','Flat ???500 off on your first order above ???2,999','fixed',500.00,2999.00,500.00,500,1,0,1,NULL,NULL,1,'2026-10-12 16:57:27');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit_notes`
--

DROP TABLE IF EXISTS `credit_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `credit_note_number` varchar(60) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `credit_note_number` (`credit_note_number`),
  KEY `fk_cn_inv` (`invoice_id`),
  CONSTRAINT `fk_cn_inv` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_notes`
--

LOCK TABLES `credit_notes` WRITE;
/*!40000 ALTER TABLE `credit_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `credit_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_addresses`
--

DROP TABLE IF EXISTS `customer_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `state_code` varchar(10) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_addr_user` (`user_id`),
  CONSTRAINT `fk_addr_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_addresses`
--

LOCK TABLES `customer_addresses` WRITE;
/*!40000 ALTER TABLE `customer_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_segments`
--

DROP TABLE IF EXISTS `customer_segments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_segments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `criteria_json` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_segments`
--

LOCK TABLES `customer_segments` WRITE;
/*!40000 ALTER TABLE `customer_segments` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_segments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_analytics_summaries`
--

DROP TABLE IF EXISTS `daily_analytics_summaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_analytics_summaries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `summary_date` date NOT NULL,
  `visitors_count` int(10) unsigned DEFAULT 0,
  `sessions_count` int(10) unsigned DEFAULT 0,
  `page_views` int(10) unsigned DEFAULT 0,
  `add_to_cart_count` int(10) unsigned DEFAULT 0,
  `checkouts_count` int(10) unsigned DEFAULT 0,
  `orders_count` int(10) unsigned DEFAULT 0,
  `gross_revenue` decimal(12,2) DEFAULT 0.00,
  `net_revenue` decimal(12,2) DEFAULT 0.00,
  `ad_spend` decimal(12,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `summary_date` (`summary_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_analytics_summaries`
--

LOCK TABLES `daily_analytics_summaries` WRITE;
/*!40000 ALTER TABLE `daily_analytics_summaries` DISABLE KEYS */;
/*!40000 ALTER TABLE `daily_analytics_summaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_job_rows`
--

DROP TABLE IF EXISTS `import_job_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import_job_rows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `import_job_id` bigint(20) unsigned NOT NULL,
  `row_index` int(11) NOT NULL,
  `raw_data` longtext NOT NULL,
  `status` enum('success','skipped','failed') NOT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_ijr_job` (`import_job_id`),
  CONSTRAINT `fk_ijr_job` FOREIGN KEY (`import_job_id`) REFERENCES `import_jobs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_job_rows`
--

LOCK TABLES `import_job_rows` WRITE;
/*!40000 ALTER TABLE `import_job_rows` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_job_rows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_jobs`
--

DROP TABLE IF EXISTS `import_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mode` enum('create_only','update_only','upsert') DEFAULT 'upsert',
  `total_rows` int(11) DEFAULT 0,
  `imported_rows` int(11) DEFAULT 0,
  `skipped_rows` int(11) DEFAULT 0,
  `failed_rows` int(11) DEFAULT 0,
  `status` enum('pending','processing','completed','failed') DEFAULT 'pending',
  `error_report_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_jobs`
--

LOCK TABLES `import_jobs` WRITE;
/*!40000 ALTER TABLE `import_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_locations`
--

DROP TABLE IF EXISTS `inventory_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(40) NOT NULL,
  `is_default` tinyint(1) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_locations`
--

LOCK TABLES `inventory_locations` WRITE;
/*!40000 ALTER TABLE `inventory_locations` DISABLE KEYS */;
INSERT INTO `inventory_locations` VALUES
(1,'Primary Central Fulfillment Warehouse','MUM-WH-01',1,1);
/*!40000 ALTER TABLE `inventory_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_movements`
--

DROP TABLE IF EXISTS `inventory_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `variant_id` bigint(20) unsigned NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `change_qty` int(11) NOT NULL,
  `balance_after` int(11) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `reference_type` varchar(60) DEFAULT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_mov_var` (`variant_id`),
  CONSTRAINT `fk_mov_var` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_movements`
--

LOCK TABLES `inventory_movements` WRITE;
/*!40000 ALTER TABLE `inventory_movements` DISABLE KEYS */;
INSERT INTO `inventory_movements` VALUES
(37,5,1,10,60,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-12 19:14:05'),
(38,5,1,-10,50,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-12 19:14:05'),
(39,5,1,-1,49,'Order Fulfilment','order','WEL-ORD-20261012-BEA5AB','storefront_checkout','2026-10-12 19:14:05'),
(40,5,1,10,59,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-12 19:30:22'),
(41,5,1,-10,49,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-12 19:30:22'),
(42,5,1,-1,48,'Order Fulfilment','order','WEL-ORD-20261012-A7B3DB','storefront_checkout','2026-10-12 19:30:22'),
(43,5,1,10,58,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-12 19:36:35'),
(44,5,1,-10,48,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-12 19:36:35'),
(45,5,1,-1,47,'Order Fulfilment','order','WEL-ORD-20261012-4BDA80','storefront_checkout','2026-10-12 19:36:35'),
(46,5,1,10,57,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-12 19:37:53'),
(47,5,1,-10,47,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-12 19:37:53'),
(48,5,1,-1,46,'Order Fulfilment','order','WEL-ORD-20261012-FC6942','storefront_checkout','2026-10-12 19:37:53'),
(49,5,1,10,56,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-12 19:43:27'),
(50,5,1,-10,46,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-12 19:43:27'),
(51,5,1,-1,45,'Order Fulfilment','order','WEL-ORD-20261012-71649A','storefront_checkout','2026-10-12 19:43:27'),
(52,5,1,10,55,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 09:24:43'),
(53,5,1,-10,45,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 09:24:43'),
(54,5,1,-1,44,'Order Fulfilment','order','WEL-ORD-20261013-904146','storefront_checkout','2026-10-13 09:24:43'),
(55,5,1,10,54,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 09:26:41'),
(56,5,1,-10,44,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 09:26:41'),
(57,5,1,-1,43,'Order Fulfilment','order','WEL-ORD-20261013-3AB9BF','storefront_checkout','2026-10-13 09:26:41'),
(58,5,1,10,53,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 09:39:53'),
(59,5,1,-10,43,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 09:39:53'),
(60,5,1,-1,42,'Order Fulfilment','order','WEL-ORD-20261013-17D301','storefront_checkout','2026-10-13 09:39:53'),
(61,5,1,10,52,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 09:49:34'),
(62,5,1,-10,42,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 09:49:34'),
(63,5,1,-1,41,'Order Fulfilment','order','WEL-ORD-20261013-D3D433','storefront_checkout','2026-10-13 09:49:34'),
(64,5,1,10,51,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 10:40:12'),
(65,5,1,-10,41,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 10:40:12'),
(66,5,1,-1,40,'Order Fulfilment','order','WEL-ORD-20261013-796AEE','storefront_checkout','2026-10-13 10:40:12'),
(67,5,1,10,50,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 10:48:03'),
(68,5,1,-10,40,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 10:48:03'),
(69,5,1,-1,39,'Order Fulfilment','order','WEL-ORD-20261013-7B5B7D','storefront_checkout','2026-10-13 10:48:03'),
(70,5,1,10,49,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 10:48:37'),
(71,5,1,-10,39,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 10:48:37'),
(72,5,1,-1,38,'Order Fulfilment','order','WEL-ORD-20261013-C91290','storefront_checkout','2026-10-13 10:48:37'),
(73,5,1,10,48,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 10:50:58'),
(74,5,1,-10,38,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 10:50:58'),
(75,5,1,-1,37,'Order Fulfilment','order','WEL-ORD-20261013-32F781','storefront_checkout','2026-10-13 10:50:58'),
(76,5,1,10,47,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 10:51:51'),
(77,5,1,-10,37,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 10:51:51'),
(78,5,1,-1,36,'Order Fulfilment','order','WEL-ORD-20261013-77B7BB','storefront_checkout','2026-10-13 10:51:51'),
(79,5,1,10,46,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 11:03:48'),
(80,5,1,-10,36,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 11:03:48'),
(81,5,1,-1,35,'Order Fulfilment','order','WEL-ORD-20261013-BDA138','storefront_checkout','2026-10-13 11:03:48'),
(82,5,1,10,45,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 11:37:53'),
(83,5,1,-10,35,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 11:37:53'),
(84,5,1,-1,34,'Order Fulfilment','order','WEL-ORD-20261013-46F71C','storefront_checkout','2026-10-13 11:37:53'),
(85,5,1,10,44,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 11:44:52'),
(86,5,1,-10,34,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 11:44:52'),
(87,5,1,-1,33,'Order Fulfilment','order','WEL-ORD-20261013-2FA804','storefront_checkout','2026-10-13 11:44:52'),
(88,5,1,10,43,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 11:54:13'),
(89,5,1,-10,33,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 11:54:13'),
(90,5,1,-1,32,'Order Fulfilment','order','WEL-ORD-20261013-6DFA11','storefront_checkout','2026-10-13 11:54:13'),
(91,5,1,10,42,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 12:00:35'),
(92,5,1,-10,32,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 12:00:35'),
(93,5,1,-1,31,'Order Fulfilment','order','WEL-ORD-20261013-E5DE2A','storefront_checkout','2026-10-13 12:00:35'),
(94,5,1,10,41,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 12:10:44'),
(95,5,1,-10,31,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 12:10:44'),
(96,5,1,-1,30,'Order Fulfilment','order','WEL-ORD-20261013-B32965','storefront_checkout','2026-10-13 12:10:44'),
(97,5,1,10,40,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 12:22:26'),
(98,5,1,-10,30,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 12:22:26'),
(99,5,1,-1,29,'Order Fulfilment','order','WEL-ORD-20261013-A3501B','storefront_checkout','2026-10-13 12:22:26'),
(100,5,1,10,39,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 12:24:48'),
(101,5,1,-10,29,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 12:24:48'),
(102,5,1,-1,28,'Order Fulfilment','order','WEL-ORD-20261013-088404','storefront_checkout','2026-10-13 12:24:48'),
(103,5,1,10,38,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 12:37:22'),
(104,5,1,-10,28,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 12:37:22'),
(105,5,1,-1,27,'Order Fulfilment','order','WEL-ORD-20261013-E4A721','storefront_checkout','2026-10-13 12:37:22'),
(106,5,1,10,37,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 15:09:57'),
(107,5,1,-10,27,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 15:09:57'),
(108,5,1,-1,26,'Order Fulfilment','order','WEL-ORD-20261013-25E20E','storefront_checkout','2026-10-13 15:09:57'),
(109,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 16:18:52'),
(110,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 16:18:52'),
(111,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 16:39:36'),
(112,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 16:39:36'),
(113,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 17:00:09'),
(114,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 17:00:09'),
(115,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 17:33:49'),
(116,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 17:33:49'),
(117,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 18:55:32'),
(118,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 18:55:32'),
(119,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 23:19:11'),
(120,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 23:19:11'),
(121,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 23:41:28'),
(122,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 23:41:28'),
(123,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 23:52:16'),
(124,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 23:52:16'),
(125,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 23:55:38'),
(126,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 23:55:38'),
(127,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 00:13:10'),
(128,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 00:13:10'),
(129,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 10:18:26'),
(130,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 10:18:26'),
(131,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 10:34:23'),
(132,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 10:34:23'),
(133,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 11:48:11'),
(134,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 11:48:11'),
(135,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 15:47:52'),
(136,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 15:47:52'),
(137,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 16:19:46'),
(138,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 16:19:46'),
(139,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 16:27:12'),
(140,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 16:27:12'),
(141,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 17:32:23'),
(142,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 17:32:23'),
(143,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 17:50:20'),
(144,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 17:50:20'),
(145,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 17:51:11'),
(146,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 17:51:11'),
(147,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 17:51:44'),
(148,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 17:51:44'),
(149,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 17:53:12'),
(150,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 17:53:12'),
(151,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 18:04:43'),
(152,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 18:04:43'),
(153,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 18:09:30'),
(154,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 18:09:30'),
(155,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 18:13:10'),
(156,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 18:13:10'),
(157,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 19:13:19'),
(158,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 19:13:19'),
(159,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 19:25:50'),
(160,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 19:25:50'),
(161,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 19:26:57'),
(162,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 19:26:57'),
(163,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 19:30:11'),
(164,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 19:30:11');
/*!40000 ALTER TABLE `inventory_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_reservations`
--

DROP TABLE IF EXISTS `inventory_reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_reservations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `variant_id` bigint(20) unsigned NOT NULL,
  `session_id` varchar(120) NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `expires_at` datetime NOT NULL,
  `status` enum('active','completed','expired','released') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_res_exp` (`expires_at`,`status`),
  KEY `fk_res_var` (`variant_id`),
  CONSTRAINT `fk_res_var` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_reservations`
--

LOCK TABLES `inventory_reservations` WRITE;
/*!40000 ALTER TABLE `inventory_reservations` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_stock`
--

DROP TABLE IF EXISTS `inventory_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_stock` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `variant_id` bigint(20) unsigned NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `reserved_quantity` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(11) NOT NULL DEFAULT 5,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_stock_var_loc` (`variant_id`,`location_id`),
  KEY `fk_stock_loc` (`location_id`),
  CONSTRAINT `fk_stock_loc` FOREIGN KEY (`location_id`) REFERENCES `inventory_locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_stock_var` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_stock`
--

LOCK TABLES `inventory_stock` WRITE;
/*!40000 ALTER TABLE `inventory_stock` DISABLE KEYS */;
INSERT INTO `inventory_stock` VALUES
(5,5,1,26,0,8,'2026-10-12 16:57:27','2026-10-16 19:30:11'),
(6,6,1,28,0,5,'2026-10-12 16:57:27','2026-10-12 16:57:27'),
(9,9,1,12,0,3,'2026-10-12 16:57:27','2026-10-12 16:57:27'),
(34,12,1,48,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(35,13,1,35,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(36,14,1,52,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(37,15,1,40,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(38,16,1,30,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(42,20,1,55,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(43,21,1,40,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30');
/*!40000 ALTER TABLE `inventory_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_sequences`
--

DROP TABLE IF EXISTS `invoice_sequences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_sequences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fiscal_year` varchar(10) NOT NULL,
  `prefix` varchar(20) NOT NULL DEFAULT 'WEL',
  `current_number` int(10) unsigned NOT NULL DEFAULT 1000,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fiscal_year` (`fiscal_year`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_sequences`
--

LOCK TABLES `invoice_sequences` WRITE;
/*!40000 ALTER TABLE `invoice_sequences` DISABLE KEYS */;
INSERT INTO `invoice_sequences` VALUES
(1,'2026-27','WEL',1035);
/*!40000 ALTER TABLE `invoice_sequences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `invoice_number` varchar(60) NOT NULL,
  `invoice_date` date NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `total_taxable` decimal(10,2) NOT NULL,
  `total_cgst` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_sgst` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_igst` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  CONSTRAINT `fk_inv_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES
(1,2,'WEL-2026-27-1002','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:14:20'),
(2,3,'WEL-2026-27-1003','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:14:55'),
(3,4,'WEL-2026-27-1004','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:17:32'),
(4,5,'WEL-2026-27-1005','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:37:59'),
(5,6,'WEL-2026-27-1006','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:47:20'),
(6,7,'WEL-2026-27-1007','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:57:04'),
(7,8,'WEL-2026-27-1008','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 18:15:12'),
(8,9,'WEL-2026-27-1009','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 18:37:33'),
(9,10,'WEL-2026-27-1010','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 18:38:23'),
(10,11,'WEL-2026-27-1011','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 18:39:44'),
(11,12,'WEL-2026-27-1012','2026-10-12',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-12 19:14:05'),
(12,13,'WEL-2026-27-1013','2026-10-12',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-12 19:30:22'),
(13,14,'WEL-2026-27-1014','2026-10-12',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-12 19:36:35'),
(14,15,'WEL-2026-27-1015','2026-10-12',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-12 19:37:53'),
(15,16,'WEL-2026-27-1016','2026-10-12',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-12 19:43:27'),
(16,17,'WEL-2026-27-1017','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 09:24:43'),
(17,18,'WEL-2026-27-1018','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 09:26:41'),
(18,19,'WEL-2026-27-1019','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 09:39:53'),
(19,20,'WEL-2026-27-1020','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 09:49:34'),
(20,21,'WEL-2026-27-1021','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 10:40:12'),
(21,22,'WEL-2026-27-1022','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 10:48:03'),
(22,23,'WEL-2026-27-1023','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 10:48:37'),
(23,24,'WEL-2026-27-1024','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 10:50:58'),
(24,25,'WEL-2026-27-1025','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 10:51:51'),
(25,26,'WEL-2026-27-1026','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 11:03:48'),
(26,27,'WEL-2026-27-1027','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 11:37:53'),
(27,28,'WEL-2026-27-1028','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 11:44:52'),
(28,29,'WEL-2026-27-1029','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 11:54:13'),
(29,30,'WEL-2026-27-1030','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 12:00:35'),
(30,31,'WEL-2026-27-1031','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 12:10:44'),
(31,32,'WEL-2026-27-1032','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 12:22:26'),
(32,33,'WEL-2026-27-1033','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 12:24:48'),
(33,34,'WEL-2026-27-1034','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 12:37:22'),
(34,35,'WEL-2026-27-1035','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 15:09:57');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_logs`
--

DROP TABLE IF EXISTS `notification_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `channel` enum('email','sms','whatsapp') NOT NULL,
  `recipient` varchar(191) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `status` enum('queued','sent','failed') DEFAULT 'sent',
  `error_message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_logs`
--

LOCK TABLES `notification_logs` WRITE;
/*!40000 ALTER TABLE `notification_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `notification_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_addresses`
--

DROP TABLE IF EXISTS `order_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `type` enum('shipping','billing') DEFAULT 'shipping',
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `state_code` varchar(10) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_oa_order` (`order_id`),
  CONSTRAINT `fk_oa_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_addresses`
--

LOCK TABLES `order_addresses` WRITE;
/*!40000 ALTER TABLE `order_addresses` DISABLE KEYS */;
INSERT INTO `order_addresses` VALUES
(2,2,'shipping','Test Buyer','9999988888','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:14:20'),
(3,3,'shipping','Test Buyer','9999285660','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:14:55'),
(4,4,'shipping','Test Buyer','9999812649','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:17:32'),
(5,5,'shipping','Test Buyer','9999275854','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:37:59'),
(6,6,'shipping','Test Buyer','9999353224','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:47:20'),
(7,7,'shipping','Test Buyer','9999774483','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:57:04'),
(8,8,'shipping','Test Buyer','9999301523','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 18:15:12'),
(9,9,'shipping','Test Buyer','9999722284','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 18:37:33'),
(10,10,'shipping','Test Buyer','9999639282','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 18:38:23'),
(11,11,'shipping','Test Buyer','9999477659','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 18:39:44'),
(12,12,'shipping','Test Buyer','9999164076','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 19:14:05'),
(13,13,'shipping','Test Buyer','9999284334','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 19:30:22'),
(14,14,'shipping','Test Buyer','9999985943','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 19:36:35'),
(15,15,'shipping','Test Buyer','9999357922','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 19:37:53'),
(16,16,'shipping','Test Buyer','9999295716','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 19:43:27'),
(17,17,'shipping','Test Buyer','9999150644','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 09:24:43'),
(18,18,'shipping','Test Buyer','9999494762','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 09:26:41'),
(19,19,'shipping','Test Buyer','9999559018','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 09:39:53'),
(20,20,'shipping','Test Buyer','9999568560','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 09:49:34'),
(21,21,'shipping','Test Buyer','9999295815','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 10:40:12'),
(22,22,'shipping','Test Buyer','9999379252','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 10:48:03'),
(23,23,'shipping','Test Buyer','9999752850','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 10:48:37'),
(24,24,'shipping','Test Buyer','9999999823','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 10:50:58'),
(25,25,'shipping','Test Buyer','9999138211','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 10:51:51'),
(26,26,'shipping','Test Buyer','9999474776','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 11:03:48'),
(27,27,'shipping','Test Buyer','9999982305','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 11:37:53'),
(28,28,'shipping','Test Buyer','9999856096','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 11:44:52'),
(29,29,'shipping','Test Buyer','9999168002','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 11:54:13'),
(30,30,'shipping','Test Buyer','9999300888','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 12:00:35'),
(31,31,'shipping','Test Buyer','9999628748','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 12:10:44'),
(32,32,'shipping','Test Buyer','9999931876','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 12:22:26'),
(33,33,'shipping','Test Buyer','9999400102','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 12:24:48'),
(34,34,'shipping','Test Buyer','9999679620','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 12:37:22'),
(35,35,'shipping','Test Buyer','9999364294','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 15:09:57');
/*!40000 ALTER TABLE `order_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `variant_name` varchar(120) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount_share` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 18.00,
  `tax_amount` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `snapshot_data` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_oi_order` (`order_id`),
  CONSTRAINT `fk_oi_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES
(2,2,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:14:20'),
(3,3,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:14:55'),
(4,4,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:17:32'),
(5,5,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:37:59'),
(6,6,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:47:20'),
(7,7,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:57:04'),
(8,8,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 18:15:12'),
(9,9,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 18:37:33'),
(10,10,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 18:38:23'),
(11,11,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 18:39:44'),
(12,12,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-12 19:14:05'),
(13,13,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-12 19:30:22'),
(14,14,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-12 19:36:35'),
(15,15,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-12 19:37:53'),
(16,16,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-12 19:43:27'),
(17,17,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 09:24:43'),
(18,18,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 09:26:41'),
(19,19,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 09:39:53'),
(20,20,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 09:49:34'),
(21,21,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 10:40:12'),
(22,22,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 10:48:03'),
(23,23,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 10:48:37'),
(24,24,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 10:50:58'),
(25,25,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 10:51:51'),
(26,26,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 11:03:48'),
(27,27,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 11:37:53'),
(28,28,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 11:44:52'),
(29,29,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 11:54:13'),
(30,30,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 12:00:35'),
(31,31,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 12:10:44'),
(32,32,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 12:22:26'),
(33,33,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 12:24:48'),
(34,34,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 12:37:22'),
(35,35,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 15:09:57');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_status_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `comment` text DEFAULT NULL,
  `changed_by` varchar(100) NOT NULL DEFAULT 'system',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_osh_order` (`order_id`),
  CONSTRAINT `fk_osh_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_history`
--

LOCK TABLES `order_status_history` WRITE;
/*!40000 ALTER TABLE `order_status_history` DISABLE KEYS */;
INSERT INTO `order_status_history` VALUES
(2,2,NULL,'pending','Order placed by customer','customer','2026-10-12 17:14:20'),
(3,3,NULL,'pending','Order placed by customer','customer','2026-10-12 17:14:55'),
(4,4,NULL,'pending','Order placed by customer','customer','2026-10-12 17:17:32'),
(5,5,NULL,'pending','Order placed by customer','customer','2026-10-12 17:37:59'),
(6,6,NULL,'pending','Order placed by customer','customer','2026-10-12 17:47:20'),
(7,7,NULL,'pending','Order placed by customer','customer','2026-10-12 17:57:04'),
(8,8,NULL,'pending','Order placed by customer','customer','2026-10-12 18:15:12'),
(9,9,NULL,'pending','Order placed by customer','customer','2026-10-12 18:37:33'),
(10,10,NULL,'pending','Order placed by customer','customer','2026-10-12 18:38:23'),
(11,11,NULL,'pending','Order placed by customer','customer','2026-10-12 18:39:44'),
(12,12,NULL,'pending','Order placed by customer','customer','2026-10-12 19:14:05'),
(13,13,NULL,'pending','Order placed by customer','customer','2026-10-12 19:30:22'),
(14,14,NULL,'pending','Order placed by customer','customer','2026-10-12 19:36:35'),
(15,15,NULL,'pending','Order placed by customer','customer','2026-10-12 19:37:53'),
(16,16,NULL,'pending','Order placed by customer','customer','2026-10-12 19:43:27'),
(17,17,NULL,'pending','Order placed by customer','customer','2026-10-13 09:24:43'),
(18,18,NULL,'pending','Order placed by customer','customer','2026-10-13 09:26:41'),
(19,19,NULL,'pending','Order placed by customer','customer','2026-10-13 09:39:53'),
(20,20,NULL,'pending','Order placed by customer','customer','2026-10-13 09:49:34'),
(21,21,NULL,'pending','Order placed by customer','customer','2026-10-13 10:40:12'),
(22,22,NULL,'pending','Order placed by customer','customer','2026-10-13 10:48:03'),
(23,23,NULL,'pending','Order placed by customer','customer','2026-10-13 10:48:37'),
(24,24,NULL,'pending','Order placed by customer','customer','2026-10-13 10:50:58'),
(25,25,NULL,'pending','Order placed by customer','customer','2026-10-13 10:51:51'),
(26,26,NULL,'pending','Order placed by customer','customer','2026-10-13 11:03:48'),
(27,27,NULL,'pending','Order placed by customer','customer','2026-10-13 11:37:53'),
(28,28,NULL,'pending','Order placed by customer','customer','2026-10-13 11:44:52'),
(29,29,NULL,'pending','Order placed by customer','customer','2026-10-13 11:54:13'),
(30,30,NULL,'pending','Order placed by customer','customer','2026-10-13 12:00:35'),
(31,31,NULL,'pending','Order placed by customer','customer','2026-10-13 12:10:44'),
(32,32,NULL,'pending','Order placed by customer','customer','2026-10-13 12:22:26'),
(33,33,NULL,'pending','Order placed by customer','customer','2026-10-13 12:24:48'),
(34,34,NULL,'pending','Order placed by customer','customer','2026-10-13 12:37:22'),
(35,35,NULL,'pending','Order placed by customer','customer','2026-10-13 15:09:57');
/*!40000 ALTER TABLE `order_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `guest_email` varchar(191) DEFAULT NULL,
  `guest_phone` varchar(20) NOT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled','refund_pending','partially_refunded','refunded') DEFAULT 'pending',
  `payment_status` enum('unpaid','authorized','captured','failed','refunded') DEFAULT 'unpaid',
  `fulfilment_status` enum('unfulfilled','processing','shipped','delivered','returned') DEFAULT 'unfulfilled',
  `currency` varchar(10) NOT NULL DEFAULT 'INR',
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('razorpay','cod') NOT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `shipping_state` varchar(100) NOT NULL,
  `is_cancelled` tinyint(1) DEFAULT 0,
  `cancelled_at` datetime DEFAULT NULL,
  `cancel_reason` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_order_user` (`user_id`),
  KEY `idx_order_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES
(2,'WEL-ORD-20261012-DADFB1',1,'test@buyer.com','9999988888','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:14:20','2026-10-12 17:14:20'),
(3,'WEL-ORD-20261012-C2D4C8',2,'test@buyer.com','9999285660','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:14:55','2026-10-12 17:14:55'),
(4,'WEL-ORD-20261012-801EA2',3,'test@buyer.com','9999812649','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:17:32','2026-10-12 17:17:32'),
(5,'WEL-ORD-20261012-EBC16C',4,'test@buyer.com','9999275854','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:37:59','2026-10-12 17:37:59'),
(6,'WEL-ORD-20261012-5EF8F7',5,'test@buyer.com','9999353224','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:47:20','2026-10-12 17:47:20'),
(7,'WEL-ORD-20261012-68DE02',6,'test@buyer.com','9999774483','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:57:04','2026-10-12 17:57:04'),
(8,'WEL-ORD-20261012-AC96B2',7,'test@buyer.com','9999301523','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 18:15:12','2026-10-12 18:15:12'),
(9,'WEL-ORD-20261012-F2D688',8,'test@buyer.com','9999722284','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 18:37:33','2026-10-12 18:37:33'),
(10,'WEL-ORD-20261012-40E075',9,'test@buyer.com','9999639282','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 18:38:23','2026-10-12 18:38:23'),
(11,'WEL-ORD-20261012-1AB934',10,'test@buyer.com','9999477659','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 18:39:44','2026-10-12 18:39:44'),
(12,'WEL-ORD-20261012-BEA5AB',11,'test@buyer.com','9999164076','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 19:14:05','2026-10-12 19:14:05'),
(13,'WEL-ORD-20261012-A7B3DB',12,'test@buyer.com','9999284334','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 19:30:22','2026-10-12 19:30:22'),
(14,'WEL-ORD-20261012-4BDA80',13,'test@buyer.com','9999985943','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 19:36:35','2026-10-12 19:36:35'),
(15,'WEL-ORD-20261012-FC6942',14,'test@buyer.com','9999357922','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 19:37:53','2026-10-12 19:37:53'),
(16,'WEL-ORD-20261012-71649A',15,'test@buyer.com','9999295716','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 19:43:27','2026-10-12 19:43:27'),
(17,'WEL-ORD-20261013-904146',16,'test@buyer.com','9999150644','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 09:24:43','2026-10-13 09:24:43'),
(18,'WEL-ORD-20261013-3AB9BF',17,'test@buyer.com','9999494762','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 09:26:41','2026-10-13 09:26:41'),
(19,'WEL-ORD-20261013-17D301',18,'test@buyer.com','9999559018','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 09:39:53','2026-10-13 09:39:53'),
(20,'WEL-ORD-20261013-D3D433',19,'test@buyer.com','9999568560','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 09:49:34','2026-10-13 09:49:34'),
(21,'WEL-ORD-20261013-796AEE',20,'test@buyer.com','9999295815','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 10:40:12','2026-10-13 10:40:12'),
(22,'WEL-ORD-20261013-7B5B7D',21,'test@buyer.com','9999379252','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 10:48:03','2026-10-13 10:48:03'),
(23,'WEL-ORD-20261013-C91290',22,'test@buyer.com','9999752850','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 10:48:37','2026-10-13 10:48:37'),
(24,'WEL-ORD-20261013-32F781',23,'test@buyer.com','9999999823','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 10:50:58','2026-10-13 10:50:58'),
(25,'WEL-ORD-20261013-77B7BB',24,'test@buyer.com','9999138211','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 10:51:51','2026-10-13 10:51:51'),
(26,'WEL-ORD-20261013-BDA138',25,'test@buyer.com','9999474776','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 11:03:48','2026-10-13 11:03:48'),
(27,'WEL-ORD-20261013-46F71C',26,'test@buyer.com','9999982305','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 11:37:53','2026-10-13 11:37:53'),
(28,'WEL-ORD-20261013-2FA804',27,'test@buyer.com','9999856096','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 11:44:52','2026-10-13 11:44:52'),
(29,'WEL-ORD-20261013-6DFA11',28,'test@buyer.com','9999168002','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 11:54:13','2026-10-13 11:54:13'),
(30,'WEL-ORD-20261013-E5DE2A',29,'test@buyer.com','9999300888','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 12:00:35','2026-10-13 12:00:35'),
(31,'WEL-ORD-20261013-B32965',30,'test@buyer.com','9999628748','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 12:10:44','2026-10-13 12:10:44'),
(32,'WEL-ORD-20261013-A3501B',31,'test@buyer.com','9999931876','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 12:22:26','2026-10-13 12:22:26'),
(33,'WEL-ORD-20261013-088404',32,'test@buyer.com','9999400102','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 12:24:48','2026-10-13 12:24:48'),
(34,'WEL-ORD-20261013-E4A721',33,'test@buyer.com','9999679620','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 12:37:22','2026-10-13 12:37:22'),
(35,'WEL-ORD-20261013-25E20E',34,'test@buyer.com','9999364294','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 15:09:57','2026-10-13 15:09:57');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otp_challenges`
--

DROP TABLE IF EXISTS `otp_challenges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `otp_challenges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(191) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'login',
  `attempts` int(10) unsigned DEFAULT 0,
  `max_attempts` int(10) unsigned DEFAULT 5,
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_otp_ident` (`identifier`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otp_challenges`
--

LOCK TABLES `otp_challenges` WRITE;
/*!40000 ALTER TABLE `otp_challenges` DISABLE KEYS */;
INSERT INTO `otp_challenges` VALUES
(1,'8511115597','429478','login',0,5,'2026-10-15 16:51:00',NULL,'2026-10-15 16:41:00'),
(2,'8511115597','636249','login',0,5,'2026-10-15 16:51:38',NULL,'2026-10-15 16:41:38'),
(3,'8511115597','560709','login',0,5,'2026-10-15 16:51:57',NULL,'2026-10-15 16:41:57');
/*!40000 ALTER TABLE `otp_challenges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_events`
--

DROP TABLE IF EXISTS `payment_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` bigint(20) unsigned NOT NULL,
  `event_name` varchar(80) NOT NULL,
  `payload` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_pe_pay` (`payment_id`),
  CONSTRAINT `fk_pe_pay` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_events`
--

LOCK TABLES `payment_events` WRITE;
/*!40000 ALTER TABLE `payment_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `gateway` varchar(50) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `gateway_order_id` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'INR',
  `status` enum('pending','authorized','captured','failed','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `gateway_payload` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_pay_order` (`order_id`),
  CONSTRAINT `fk_pay_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `module` varchar(60) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES
(1,'View Dashboard','dashboard.view','Dashboard','View executive KPIs and operational summaries','2026-10-12 16:57:27'),
(2,'View Orders','orders.view','Orders','View customer orders and status details','2026-10-12 16:57:27'),
(3,'Update Order Status','orders.update_status','Orders','Advance or modify order status and tracking','2026-10-12 16:57:27'),
(4,'Cancel Orders','orders.cancel','Orders','Cancel eligible customer orders','2026-10-12 16:57:27'),
(5,'Request Refunds','refunds.request','Orders','Initiate payment refund flow','2026-10-12 16:57:27'),
(6,'Approve Refunds','refunds.approve','Orders','Authorize and finalize payment refunds','2026-10-12 16:57:27'),
(7,'Create Products','products.create','Catalog','Add new watch models and variants','2026-10-12 16:57:27'),
(8,'Update Products','products.update','Catalog','Edit product details, prices and assets','2026-10-12 16:57:27'),
(9,'Archive Products','products.archive','Catalog','Unpublish or archive products','2026-10-12 16:57:27'),
(10,'View Inventory','inventory.view','Inventory','View stock levels across warehouses','2026-10-12 16:57:27'),
(11,'Adjust Inventory','inventory.adjust','Inventory','Perform manual stock adjustments','2026-10-12 16:57:27'),
(12,'Run Bulk Imports','imports.run','Catalog','Upload and process bulk product CSV files','2026-10-12 16:57:27'),
(13,'View Customers','customers.view','Customers','View customer CRM profiles and orders','2026-10-12 16:57:27'),
(14,'Export Customer Data','customers.export','Customers','Download authorized customer lists','2026-10-12 16:57:27'),
(15,'Manage Coupons','coupons.manage','Promotions','Create and toggle discount coupon codes','2026-10-12 16:57:27'),
(16,'Manage Campaigns','campaigns.manage','Promotions','Configure promotional discount campaigns','2026-10-12 16:57:27'),
(17,'Manage Banners','banners.manage','Promotions','Update homepage hero and promotional banners','2026-10-12 16:57:27'),
(18,'View Analytics','analytics.view','Analytics','Access first-party traffic and sales analytics','2026-10-12 16:57:27'),
(19,'Export Analytics','analytics.export','Analytics','Export analytics reports as CSV','2026-10-12 16:57:27'),
(20,'Manage Ad Integrations','ads_integrations.manage','Marketing','Configure Meta, Google Ads and Pixel tracking','2026-10-12 16:57:27'),
(21,'Manage Settings','settings.manage','Settings','Update business, GST, invoice and payment configurations','2026-10-12 16:57:27'),
(22,'Manage Staff Roles','users.manage_roles','Settings','Create staff accounts and assign permissions','2026-10-12 16:57:27'),
(23,'View Audit Logs','audit_logs.view','Settings','Inspect administrative action audit trail','2026-10-12 16:57:27');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_categories` (
  `product_id` bigint(20) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`),
  KEY `fk_pc_cat` (`category_id`),
  CONSTRAINT `fk_pc_cat` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pc_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES
(3,1),
(4,1),
(7,1),
(12,1),
(13,1),
(14,1),
(15,1),
(16,1),
(7,4),
(14,4),
(15,4),
(12,5),
(13,5),
(16,5),
(20,5),
(21,5),
(20,6),
(21,6);
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_collections`
--

DROP TABLE IF EXISTS `product_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_collections` (
  `product_id` bigint(20) unsigned NOT NULL,
  `collection_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`collection_id`),
  KEY `fk_pcoll_coll` (`collection_id`),
  CONSTRAINT `fk_pcoll_coll` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pcoll_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_collections`
--

LOCK TABLES `product_collections` WRITE;
/*!40000 ALTER TABLE `product_collections` DISABLE KEYS */;
INSERT INTO `product_collections` VALUES
(3,1),
(4,1),
(3,3),
(7,4);
/*!40000 ALTER TABLE `product_collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_comparison_rows`
--

DROP TABLE IF EXISTS `product_comparison_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_comparison_rows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feature_name` varchar(150) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_comparison_rows`
--

LOCK TABLES `product_comparison_rows` WRITE;
/*!40000 ALTER TABLE `product_comparison_rows` DISABLE KEYS */;
INSERT INTO `product_comparison_rows` VALUES
(1,'Case Diameter',1,1),
(2,'Movement Caliber',2,1),
(3,'Dial Glass',3,1),
(4,'Water Resistance',4,1),
(5,'Case & Strap Material',5,1),
(6,'Atelier Warranty',6,1);
/*!40000 ALTER TABLE `product_comparison_rows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_comparison_values`
--

DROP TABLE IF EXISTS `product_comparison_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_comparison_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `row_id` bigint(20) unsigned NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pcv` (`product_id`,`row_id`),
  KEY `fk_pcv_row` (`row_id`),
  CONSTRAINT `fk_pcv_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pcv_row` FOREIGN KEY (`row_id`) REFERENCES `product_comparison_rows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_comparison_values`
--

LOCK TABLES `product_comparison_values` WRITE;
/*!40000 ALTER TABLE `product_comparison_values` DISABLE KEYS */;
INSERT INTO `product_comparison_values` VALUES
(1,3,1,'42mm Standard'),
(2,3,2,'Japanese Quartz (+/-15s/mo)'),
(3,3,3,'Hardened Mineral Crystal'),
(4,3,4,'30M / 3 ATM (Hydro-Tested)'),
(5,3,5,'316L Surgical Stainless Steel'),
(6,3,6,'1 Year Mechanism Warranty'),
(7,4,1,'42mm Standard'),
(8,4,2,'Japanese Quartz (+/-15s/mo)'),
(9,4,3,'Hardened Mineral Crystal'),
(10,4,4,'30M / 3 ATM (Hydro-Tested)'),
(11,4,5,'316L Surgical Stainless Steel'),
(12,4,6,'1 Year Mechanism Warranty'),
(13,7,1,'42mm Standard'),
(14,7,2,'Japanese Quartz (+/-15s/mo)'),
(15,7,3,'Hardened Mineral Crystal'),
(16,7,4,'30M / 3 ATM (Hydro-Tested)'),
(17,7,5,'316L Surgical Stainless Steel'),
(18,7,6,'1 Year Mechanism Warranty'),
(19,12,1,'42mm Standard'),
(20,12,2,'Japanese Quartz (+/-15s/mo)'),
(21,12,3,'Hardened Mineral Crystal'),
(22,12,4,'30M / 3 ATM (Hydro-Tested)'),
(23,12,5,'316L Surgical Stainless Steel'),
(24,12,6,'1 Year Mechanism Warranty'),
(25,13,1,'42mm Standard'),
(26,13,2,'Japanese Quartz (+/-15s/mo)'),
(27,13,3,'Hardened Mineral Crystal'),
(28,13,4,'30M / 3 ATM (Hydro-Tested)'),
(29,13,5,'316L Surgical Stainless Steel'),
(30,13,6,'1 Year Mechanism Warranty'),
(31,14,1,'42mm Standard'),
(32,14,2,'Japanese Quartz (+/-15s/mo)'),
(33,14,3,'Hardened Mineral Crystal'),
(34,14,4,'30M / 3 ATM (Hydro-Tested)'),
(35,14,5,'316L Surgical Stainless Steel'),
(36,14,6,'1 Year Mechanism Warranty'),
(37,15,1,'42mm Standard'),
(38,15,2,'Japanese Quartz (+/-15s/mo)'),
(39,15,3,'Hardened Mineral Crystal'),
(40,15,4,'30M / 3 ATM (Hydro-Tested)'),
(41,15,5,'316L Surgical Stainless Steel'),
(42,15,6,'1 Year Mechanism Warranty'),
(43,16,1,'42mm Standard'),
(44,16,2,'Japanese Quartz (+/-15s/mo)'),
(45,16,3,'Hardened Mineral Crystal'),
(46,16,4,'30M / 3 ATM (Hydro-Tested)'),
(47,16,5,'316L Surgical Stainless Steel'),
(48,16,6,'1 Year Mechanism Warranty'),
(49,20,1,'42mm Standard'),
(50,20,2,'Japanese Quartz (+/-15s/mo)'),
(51,20,3,'Hardened Mineral Crystal'),
(52,20,4,'30M / 3 ATM (Hydro-Tested)'),
(53,20,5,'316L Surgical Stainless Steel'),
(54,20,6,'1 Year Mechanism Warranty'),
(55,21,1,'42mm Standard'),
(56,21,2,'Japanese Quartz (+/-15s/mo)'),
(57,21,3,'Hardened Mineral Crystal'),
(58,21,4,'30M / 3 ATM (Hydro-Tested)'),
(59,21,5,'316L Surgical Stainless Steel'),
(60,21,6,'1 Year Mechanism Warranty');
/*!40000 ALTER TABLE `product_comparison_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_comparisons`
--

DROP TABLE IF EXISTS `product_comparisons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_comparisons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(120) NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cmp` (`session_id`,`product_id`),
  KEY `fk_cmp_prod` (`product_id`),
  CONSTRAINT `fk_cmp_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_comparisons`
--

LOCK TABLES `product_comparisons` WRITE;
/*!40000 ALTER TABLE `product_comparisons` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_comparisons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_faqs`
--

DROP TABLE IF EXISTS `product_faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned DEFAULT NULL COMMENT 'NULL indicates global watch FAQ',
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_pfaq_prod` (`product_id`),
  CONSTRAINT `fk_pfaq_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_faqs`
--

LOCK TABLES `product_faqs` WRITE;
/*!40000 ALTER TABLE `product_faqs` DISABLE KEYS */;
INSERT INTO `product_faqs` VALUES
(41,3,'What movement powers the Welkar Royale Classic?','It is powered by a high-precision Japanese Quartz caliber offering +/-15 seconds per month accuracy and an authentic SR626SW silver-oxide battery with 2 to 3 years lifespan.',1,1),
(42,3,'How should I care for the genuine leather strap?','To preserve the rich brown finish, avoid saturating the leather in water and allow it to air-dry naturally away from direct heat if exposed to moisture.',2,1),
(43,3,'What is the case size and wrist fit for this model?','It features a refined 39mm case diameter and an ultra-slim profile, designed to fit comfortably under dress cuffs for both men and unisex styling on wrists from 5.5 to 7.5 inches.',3,1),
(44,3,'Is this watch water-resistant?','Yes, it is rated at 30M (3 ATM) with dual silicone gasket seals, protecting against rain, hand-washing, and accidental splashes. It is not intended for swimming.',4,1),
(45,4,'Can I wear the Titan Neo Splash Sport Chrono while swimming?','Yes. With a 50M (5 ATM) water resistance rating and a navy blue silicone strap, it is tested safe for shallow recreational surface swimming and showers. Do not press chrono pushers underwater.',1,1),
(46,4,'What functions do the chronograph sub-dials provide?','The multi-function dial features precision 1/10th second stopwatch timing, split lap measurement, and a 24-hour time indicator.',2,1),
(47,4,'How durable is the silicone strap during sports?','The sweat-resistant, hypoallergenic silicone rubber band is engineered for active movement, gym workouts, and humid Indian weather.',3,1),
(48,4,'What warranty coverage is included?','It includes a 2-Year official movement and manufacturing defect warranty supported nationwide.',4,1),
(49,7,'Does the Fossil Townsman Automatic require a battery replacement?','No. It operates on a self-winding automatic mechanical caliber that is wound by the natural kinetic motion of your wrist. It has zero battery.',1,1),
(50,7,'How do I wind or restart the watch if it has stopped?','If left unworn past its ~40-hour power reserve, rotate the crown clockwise 20 to 30 times in the neutral position to manually jumpstart the balance wheel, then set the time.',2,1),
(51,7,'What is visible through the skeleton exhibition dial?','The open-heart skeleton front and exhibition caseback reveal the oscillating balance wheel, escapement gear train, and jeweled bearings in active operation.',3,1),
(52,7,'What is the water resistance of this automatic timepiece?','It is pressure-rated to 50M (5 ATM), offering protection against rain, splashes, and brief water immersion. Avoid submerging the leather strap.',4,1),
(53,12,'How does the bilingual day-and-date complication work?','Pull the crown to position 1 to set the date (clockwise) and day of the week (counter-clockwise). Pull to position 2 to set the time.',1,1),
(54,12,'Can the stainless steel bracelet links be resized?','Yes. All solid 316L link bracelets feature inner directional arrows and can be easily adjusted with a link pin tool at home or by any local jeweler.',2,1),
(55,12,'What is the water resistance rating?','It is sealed to 30M (3 ATM) hydrostatic pressure with silicone crown gaskets, resilient against handwashing, sudden rain, and humidity.',3,1),
(56,12,'What warranty is provided?','It comes with Welkar\'s 2-Year movement warranty with complimentary doorstep repair or exchange pickup across India.',4,1),
(57,13,'What makes the Hydro-Lab Submersion Master resilient?','It features black PVD-coated 316L surgical stainless steel, double O-ring silicone crown gaskets, and individually verified pressure-chamber sealing.',1,1),
(58,13,'Does the black PVD coating scratch or peel?','We use high-density physical vapor deposition (PVD) bonding that fuses molecularly with the steel, providing exceptional resistance to daily scratches and sweat erosion.',2,1),
(59,13,'Can I wear this model for water activities?','It is certified for 30M (3 ATM) everyday water protection against heavy rain and splashes. For dedicated swimming, we recommend 50M-rated sports models.',3,1),
(60,13,'What is the battery lifespan and replacement procedure?','The Japanese quartz movement runs on an SR626SW silver-oxide cell for 2 to 3 years and can be replaced in minutes by any watchmaker.',4,1),
(61,14,'What is special about the sunray teal dial?','The dial features an iridescent sunburst micro-texture that reflects ambient light in shifting shades of deep teal and oceanic emerald.',1,1),
(62,14,'How do I adjust the stainless steel link strap?','The bracelet includes removable links secured by standard split-pins. Push pins in the direction of the stamped arrows to size the band to your wrist.',2,1),
(63,14,'What caliber powers this watch?','It runs on a Japanese three-hand quartz caliber with shock-cushioned mounting for precise, hassle-free timekeeping.',3,1),
(64,14,'Is it protected against water?','Yes, it is rated at 30M (3 ATM) for rain, accidental submersions, and handwashing.',4,1),
(65,15,'How do the chronograph pushers operate?','The top 2 o\'clock pusher starts and stops the stopwatch timer, while the lower 4 o\'clock pusher resets the sub-dial hands to zero.',1,1),
(66,15,'Is this timepiece suited for formal and boardroom attire?','Yes, the 44mm brushed steel bezel, applied polished baton indices, and classic multi-link steel bracelet complement business suits and executive wear.',2,1),
(67,15,'What warranty applies to this chronograph?','All Welkar executive chronographs are covered under our 2-Year movement warranty with pan-India insured transit.',3,1),
(68,15,'Can I swim with this watch?','The watch is sealed to 30M (3 ATM) for rain and splashes. Chronograph pushers should never be actuated in wet conditions.',4,1),
(69,16,'How is the tachymeter bezel used?','The calibrated outer bezel allows you to measure speed based on travel time over a fixed distance (e.g., 1 kilometer) using the chronograph second hand.',1,1),
(70,16,'Is this watch safe for swimming and intense workouts?','Yes. It is pressure-tested to 50M (5 ATM) water resistance and equipped with a breathable perforated silicone strap that wicks away sweat and dries quickly.',2,1),
(71,16,'What movement powers the Sport Racer?','A high-impact quartz caliber engineered with shock-absorbent movement holders to withstand vibrations from running, cycling, or motorsport.',3,1),
(72,16,'What is the case finish?','The 44mm case is finished in non-reflective matte black PVD, giving it an understated stealth aesthetic.',4,1),
(73,20,'Can this youth watch be worn while swimming?','Yes. The Arctic Blue Youth watch is sealed to 50M (5 ATM) hydrostatic depth, making it fully safe for recreational pool swimming, water sports, and showers.',1,1),
(74,20,'How does the dual digital-analog display work?','It simultaneously displays traditional analog clock hands and a high-contrast digital screen with 12/24 hour time, date, stopwatch, and hourly chime.',2,1),
(75,20,'What material is used for the strap and casing?','The watch uses impact-resistant drop-safe resin with a soft, non-toxic TPU resin band designed for junior wrist sizes (ages 10-17).',3,1),
(76,20,'Does it have a backlight for night visibility?','Yes, an integrated EL backlight illuminates the digital display at the push of the upper left button.',4,1),
(77,21,'What age group is the Junior Shock-Guard designed for?','It is ergonomically scaled with a 36mm case and soft silicone strap for children aged 5 to 11 years.',1,1),
(78,21,'Is this watch drop-resistant and playground safe?','Yes, the reinforced bumper bezel absorbs shocks and drops onto hard surfaces, while the mineral-acrylic crystal resists shattering.',2,1),
(79,21,'Can a child wear this watch in the swimming pool or bath?','Yes. It is sealed to 50M (5 ATM) water resistance, safe for swimming and daily baths. Do not press buttons underwater.',3,1),
(80,21,'What features does the digital display include?','It features clear digital time, daily alarm, date calendar, split-second stopwatch, and vibrant multi-color LED night backlight.',4,1);
/*!40000 ALTER TABLE `product_faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_hotspots`
--

DROP TABLE IF EXISTS `product_hotspots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_hotspots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `x_pos_percent` decimal(5,2) NOT NULL DEFAULT 50.00,
  `y_pos_percent` decimal(5,2) NOT NULL DEFAULT 50.00,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(1000) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_ph_prod` (`product_id`),
  CONSTRAINT `fk_ph_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_hotspots`
--

LOCK TABLES `product_hotspots` WRITE;
/*!40000 ALTER TABLE `product_hotspots` DISABLE KEYS */;
INSERT INTO `product_hotspots` VALUES
(1,3,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(2,3,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(3,3,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(4,3,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(5,4,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(6,4,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(7,4,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(8,4,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(9,7,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(10,7,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(11,7,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(12,7,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(13,12,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(14,12,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(15,12,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(16,12,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(17,13,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(18,13,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(19,13,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(20,13,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(21,14,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(22,14,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(23,14,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(24,14,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(25,15,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(26,15,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(27,15,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(28,15,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(29,16,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(30,16,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(31,16,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(32,16,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(33,20,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(34,20,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(35,20,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(36,20,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(37,21,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(38,21,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(39,21,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(40,21,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1);
/*!40000 ALTER TABLE `product_hotspots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `image_url` text NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_img_prod` (`product_id`),
  KEY `fk_img_var` (`variant_id`),
  CONSTRAINT `fk_img_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_img_var` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES
(11,7,NULL,'/uploads/products/watch_7_1791808086_0a3d2ee4.jpeg',1,1,'2026-10-12 17:58:06'),
(12,7,NULL,'/uploads/products/watch_7_1791808086_6508f920.jpeg',0,2,'2026-10-12 17:58:06'),
(13,7,NULL,'/uploads/products/watch_7_1791808086_9409774d.jpeg',0,3,'2026-10-12 17:58:06'),
(14,7,NULL,'/uploads/products/watch_7_1791808086_1e032462.jpeg',0,4,'2026-10-12 17:58:06'),
(15,7,NULL,'/uploads/products/watch_7_1791808086_95aa2375.jpeg',0,5,'2026-10-12 17:58:06'),
(16,7,NULL,'/uploads/products/watch_7_1791808086_3c4b07d7.jpeg',0,6,'2026-10-12 17:58:06'),
(17,7,NULL,'/uploads/products/watch_7_1791808086_ef63d5f4.jpeg',0,7,'2026-10-12 17:58:06'),
(30,4,NULL,'/uploads/products/watch_4_1791810624_2b947d25.jpg',0,1,'2026-10-12 18:40:24'),
(31,4,NULL,'/uploads/products/watch_4_1791810624_f3eee104.jpg',0,2,'2026-10-12 18:40:24'),
(32,4,NULL,'/uploads/products/watch_4_1791810624_f24e81ac.jpeg',0,3,'2026-10-12 18:40:24'),
(33,4,NULL,'/uploads/products/watch_4_1791810624_d1f9ce04.jpeg',0,4,'2026-10-12 18:40:24'),
(34,4,NULL,'/uploads/products/watch_4_1791810624_8e75dcf4.jpeg',0,5,'2026-10-12 18:40:24'),
(35,4,NULL,'/uploads/products/watch_4_1791810624_eef53220.jpeg',1,6,'2026-10-12 18:40:24'),
(36,4,NULL,'/uploads/products/watch_4_1791810624_0865dd47.jpeg',0,7,'2026-10-12 18:40:24'),
(37,4,NULL,'/uploads/products/watch_4_1791810624_27d53ad8.jpg',0,8,'2026-10-12 18:40:24'),
(38,4,NULL,'/uploads/products/watch_4_1791810624_b2d5bec6.jpg',0,9,'2026-10-12 18:40:24'),
(46,3,NULL,'/uploads/products/watch_3_1791810745_c35caf6c.jpeg',1,2,'2026-10-12 18:42:25'),
(47,3,NULL,'/uploads/products/watch_3_1791810745_b338dbce.jpeg',0,3,'2026-10-12 18:42:25'),
(48,3,NULL,'/uploads/products/watch_3_1791810745_b0737142.jpg',0,4,'2026-10-12 18:42:25'),
(49,3,NULL,'/uploads/products/watch_3_1791810745_0a852667.jpg',0,5,'2026-10-12 18:42:25'),
(50,3,NULL,'/uploads/products/watch_3_1791810745_e52f2897.jpeg',0,6,'2026-10-12 18:42:25'),
(51,3,NULL,'/uploads/products/watch_3_1791810745_d2d67c40.jpeg',0,7,'2026-10-12 18:42:25'),
(52,3,NULL,'/uploads/products/watch_3_1791810745_6ae2c137.jpg',0,8,'2026-10-12 18:42:25'),
(82,12,NULL,'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(83,13,NULL,'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(84,14,NULL,'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(85,15,NULL,'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(86,16,NULL,'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(90,20,NULL,'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(91,21,NULL,'https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_page_modules`
--

DROP TABLE IF EXISTS `product_page_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_page_modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `module_type` varchar(60) NOT NULL COMMENT 'banner, hotspots, stories, lifestyle, video, comparison, faq, spec',
  `title` varchar(255) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `content_json` longtext DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ppm_prod` (`product_id`,`is_enabled`,`sort_order`),
  CONSTRAINT `fk_ppm_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_page_modules`
--

LOCK TABLES `product_page_modules` WRITE;
/*!40000 ALTER TABLE `product_page_modules` DISABLE KEYS */;
INSERT INTO `product_page_modules` VALUES
(1,3,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(2,3,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(3,3,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(4,4,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(5,4,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(6,4,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(7,7,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(8,7,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(9,7,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(10,12,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(11,12,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(12,12,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(13,13,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(14,13,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(15,13,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(16,14,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(17,14,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(18,14,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(19,15,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(20,15,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(21,15,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(22,16,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(23,16,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(24,16,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(25,20,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(26,20,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(27,20,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(28,21,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(29,21,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(30,21,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52');
/*!40000 ALTER TABLE `product_page_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_specifications`
--

DROP TABLE IF EXISTS `product_specifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_specifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `spec_name` varchar(100) NOT NULL,
  `spec_value` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_spec_prod` (`product_id`),
  CONSTRAINT `fk_spec_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_specifications`
--

LOCK TABLES `product_specifications` WRITE;
/*!40000 ALTER TABLE `product_specifications` DISABLE KEYS */;
INSERT INTO `product_specifications` VALUES
(10,3,'Dial Pattern','Classical Sunray Guilloche',1),
(11,3,'Numerals','Applied Roman Numerals',2),
(13,7,'Jewels','21 Synthetic Rubies',1),
(14,7,'Power Reserve','Approximately 42 Hours',2);
/*!40000 ALTER TABLE `product_specifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_translations`
--

DROP TABLE IF EXISTS `product_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `locale` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_prod_trans` (`product_id`,`locale`),
  CONSTRAINT `fk_pt_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_translations`
--

LOCK TABLES `product_translations` WRITE;
/*!40000 ALTER TABLE `product_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(100) NOT NULL,
  `barcode` varchar(60) DEFAULT NULL,
  `variant_name` varchar(120) NOT NULL,
  `dial_color` varchar(50) DEFAULT 'White',
  `strap_color` varchar(50) DEFAULT 'Silver',
  `price` decimal(10,2) NOT NULL,
  `compare_at_price` decimal(10,2) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `fk_var_prod` (`product_id`),
  CONSTRAINT `fk_var_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES
(5,3,'WLK-ROY-GLD-BRN','890123456705','Champagne Gold / Brown Leather','Champagne','Brown',2899.00,3999.00,1450.00,1,'2026-10-12 16:57:27','2026-10-12 16:57:27'),
(6,4,'TTN-NEO-NVY-BLU','890123456706','Navy Blue / Silicone Sport','Navy Blue','Navy Blue',3795.00,4495.00,2500.00,1,'2026-10-12 16:57:27','2026-10-12 16:57:27'),
(9,7,'FSL-TWN-SKL-BRN','890123456709','Skeleton Silver / Dark Brown Leather','Skeleton Open','Dark Brown',9495.00,13995.00,6800.00,1,'2026-10-12 16:57:27','2026-10-12 16:57:27'),
(12,12,'WLK-SLV-DD-02',NULL,'Classic Silver / Black','Black','Silver',799.00,1299.00,380.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(13,13,'WLK-HYD-SUB-03',NULL,'Hydro-Lab 30M Edition','Black','Black',999.00,1699.00,480.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(14,14,'WLK-SUN-TEAL-04',NULL,'Sunray Teal Steel','Teal','Silver',899.00,1449.00,420.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(15,15,'WLK-EXEC-CHR-05',NULL,'Executive Steel Chrono','Black','Silver',849.00,1399.00,400.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(16,16,'WLK-BLK-RCR-06',NULL,'Black-Out Sport Racer','Black','Black',949.00,1599.00,460.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(20,20,'WLK-YTH-BLU-10',NULL,'Arctic Blue Active','Blue','Arctic Blue',599.00,999.00,260.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(21,21,'WLK-YTH-ORG-11',NULL,'Blaze Orange Shock','Orange','Blaze Orange',499.00,849.00,220.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `model_number` varchar(100) DEFAULT NULL,
  `base_sku` varchar(100) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `gender` varchar(20) DEFAULT 'Unisex',
  `dial_type` varchar(60) NOT NULL DEFAULT 'Analog',
  `movement` varchar(80) NOT NULL DEFAULT 'Quartz',
  `case_material` varchar(80) NOT NULL DEFAULT 'Stainless Steel',
  `case_size_mm` int(10) unsigned DEFAULT 42,
  `case_color` varchar(50) DEFAULT 'Silver',
  `strap_material` varchar(80) DEFAULT 'Stainless Steel',
  `strap_color` varchar(50) DEFAULT 'Silver',
  `water_resistance` varchar(50) DEFAULT '30m / 3 ATM',
  `warranty_years` int(10) unsigned DEFAULT 1,
  `country_of_origin` varchar(60) DEFAULT 'India',
  `base_price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `compare_at_price` decimal(10,2) DEFAULT NULL,
  `is_live_dial_animated` tinyint(1) DEFAULT 0,
  `dial_base_image` varchar(255) DEFAULT NULL,
  `dial_hour_hand_image` varchar(255) DEFAULT NULL,
  `dial_minute_hand_image` varchar(255) DEFAULT NULL,
  `dial_second_hand_image` varchar(255) DEFAULT NULL,
  `dial_center_x` int(11) DEFAULT 50,
  `dial_center_y` int(11) DEFAULT 50,
  `hsn_code` varchar(20) DEFAULT '9102',
  `gst_rate` decimal(5,2) DEFAULT 18.00,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `base_sku` (`base_sku`),
  KEY `idx_prod_brand` (`brand_id`),
  KEY `idx_prod_dial` (`dial_type`),
  KEY `idx_prod_gender` (`gender`),
  CONSTRAINT `fk_prod_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES
(3,1,'Welkar Royale Classic Roman Gold','welkar-royale-classic-roman-gold','W-ROY-03','WLK-ROY-GLD-03','Timeless dress watch with embossed Roman dial indices and champagne finish.','<p>Graceful proportions and classic aesthetics. The <strong>Welkar Royale Classic</strong> presents grand Roman numerals over a delicately guilloched champagne dial, encased in warm polished gold-tone steel with an Italian brown leather band.</p>','Unisex','Classic Roman','Japanese Quartz','Gold-plated Stainless Steel',39,'Gold','Genuine Leather','Brown','30m / 3 ATM',1,'India',2899.00,1450.00,3999.00,1,'/uploads/products/watch_3_1791810745_c35caf6c.jpeg',NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Royale Classic Roman Gold Dress Watch','Timeless Roman numerals dress watch in champagne gold by Welkar.','2026-10-12 16:57:27','2026-10-15 17:32:51'),
(4,2,'Titan Neo Splash Sport Chrono','titan-neo-splash-sport-chrono','T-NEO-88','TTN-NEO-8812','Sporty high-durability watch with blue sunray dial and silicone strap.','<p>A modern daily driver built by Titan. The Neo Splash features energetic orange accents, date display, and flexible waterproof silicone strap for active lifestyles.</p>','Men','Analog','Quartz','Polished Steel',43,'Silver','Silicone Rubber','Navy Blue','50m / 5 ATM',2,'India',3795.00,2500.00,4495.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,0,1,'Titan Neo Splash Sport Watch','Shop Titan Neo Splash watch at Welkar.','2026-10-12 16:57:27','2026-10-12 18:40:37'),
(7,4,'Fossil Townsman Automatic Skeleton','fossil-townsman-automatic-skeleton','ME3110','FSL-TWN-SKL-07','Self-winding mechanical watch revealing intricate internal gearwork.','<p>No battery needed. Powered by the natural motion of your wrist, the Townsman Skeleton exposes the heartbeat of mechanical watchmaking through double-sided exhibition crystals.</p>','Men','Automatic','Automatic Mechanical','Brushed Stainless Steel',44,'Silver','Genuine Leather','Dark Brown','50m / 5 ATM',2,'India',9495.00,6800.00,13995.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Fossil Townsman Automatic Skeleton Watch','Luxury self-winding automatic mechanical watch on Welkar.','2026-10-12 16:57:27','2026-10-12 17:59:35'),
(12,1,'Welkar Silver Quartz Analog - Black Dial with Day & Date','welkar-silver-quartz-analog-day-date','W-SLV-DD','WLK-SLV-DD-02','High-polish silver bezel featuring dual day and date calendar aperture with luminous indices and multi-link band.','<p>The <strong>Welkar Silver Quartz Analog</strong> pairs everyday executive poise with practical day-date complications and a scratch-resistant Hardlex crystal face.</p>','Men','Round Chrono','Dual Day-Date Quartz','316L Silver Brushed Steel',42,'Silver','Solid Stainless Steel','Silver','30m / 3 ATM',2,'India',799.00,380.00,1299.00,1,'/uploads/products/watch_3_1791810745_c35caf6c.jpeg',NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Silver Quartz Analog Day-Date Watch','Shop Welkar Silver Quartz Day-Date.','2026-10-13 10:39:08','2026-10-15 17:32:51'),
(13,1,'Welkar Hydro-Lab Submersion Master Edition','welkar-hydro-lab-submersion-master','W-HYD-SUB','WLK-HYD-SUB-03','Engineered for aquatic resilience with specialized double-gasket water seal, scratch-hardened mineral crystal.','<p>The <strong>Welkar Hydro-Lab Submersion Master</strong> undergoes simulated 30M hydrostatic immersion verification to protect the internal precision Japanese caliber from water splashes, rain, and pressure fluctuations.</p>','Men','Round Chrono','Japanese Quartz 30M','316L Solid Steel Alloy',44,'Black','316L Steel Link','Black','30m / 3 ATM',2,'India',999.00,480.00,1699.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Hydro-Lab Submersion Master Watch','Official Hydro-Pressure Submersion Watch.','2026-10-13 10:39:08','2026-10-13 10:39:08'),
(14,1,'Welkar Sunray Teal Dial Silver Metal Strap','welkar-sunray-teal-dial-silver-metal-strap','W-SUN-TEAL','WLK-SUN-TEAL-04','Brushed circular stainless bezel housing a deep charcoal/teal sunburst face with micro-chamfered silver indices.','<p>The <strong>Welkar Sunray Teal Dial</strong> catches natural lighting with shimmering metallic refraction, paired with an ergonomic multi-link bracelet.</p>','Men','Round Chrono','Japanese Quartz','Stainless Steel',42,'Silver','Solid Stainless Steel','Silver','30m / 3 ATM',2,'India',899.00,420.00,1449.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Sunray Teal Dial Watch','Shop Welkar Sunray Teal Dial.','2026-10-13 11:03:30','2026-10-13 11:03:30'),
(15,1,'Welkar Executive Steel Chrono Dial with Stainless Steel Back','welkar-executive-steel-chrono-dial','W-EXEC-CHR','WLK-EXEC-CHR-05','Laser engraved steel back plate, butterfly security clasp, and multi-subdial aesthetic chronograph styling.','<p>Designed for boardroom refinement. Built with a double-push safety clasp and polished 316L case back plate.</p>','Men','Round Chrono','Quartz Chronograph','316L Stainless Steel',44,'Silver','Stainless Steel Link','Silver','30m / 3 ATM',2,'India',849.00,400.00,1399.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Executive Steel Chrono Watch','Executive Chrono Watch by Welkar.','2026-10-13 10:39:08','2026-10-13 10:39:08'),
(16,1,'Welkar Black-Out Sport Racer with Tachymeter Bezel','welkar-black-out-sport-racer','W-BLK-RCR','WLK-BLK-RCR-06','Motorsport-inspired tachymeter bezel, crimson second hand accent, and impact-shielded casing.','<p>Aggressive track styling featuring black PVD finish and vibrant racing accents.</p>','Men','Round Chrono','High-Impact Quartz','Matte Black PVD',44,'Black','Perforated Silicone','Black','50m / 5 ATM',2,'India',949.00,460.00,1599.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Black-Out Sport Racer','Shop Black-Out Sport Racer.','2026-10-13 10:39:08','2026-10-13 10:39:08'),
(20,1,'Welkar Youth Dual-Display Chrono Sport Watch - Arctic Blue','welkar-youth-dual-display-chrono-sport-arctic-blue','W-YTH-BLU','WLK-YTH-BLU-10','Arctic Blue casing with 1/100s split lap timer, EL backlight, and 50M swim-proof dual digital-analog display.','<p>Built inside the Welkar Hydro-Pressure Lab with shock-absorbent composite case, food-grade silicone band, and shatter-proof mineral crystal.</p>','Kids','Round Chrono','Dual Digital-Analog 50M','Drop-Safe Resin',38,'Blue','Flexible TPU Resin','Arctic Blue','50m / 5 ATM',2,'India',599.00,260.00,999.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Youth Chrono Sport Watch - Arctic Blue','Youth Dual-Display Sport Watch.','2026-10-13 10:39:08','2026-10-13 10:55:20'),
(21,1,'Welkar Junior Shock-Guard Digital LED Watch - Blaze Orange','welkar-junior-shock-guard-digital-led-blaze-orange','W-YTH-ORG','WLK-YTH-ORG-11','Blaze Orange reinforced bumper, daily alarm chime, 7-color ambient night light.','<p>Engineered for junior playground adventures with shatter-resistant casing and non-toxic skin-safe silicone strap.</p>','Kids','Round Chrono','Digital LED Quartz','Shock-Guard Resin',36,'Orange','Shock Silicone Rubber','Blaze Orange','50m / 5 ATM',2,'India',499.00,220.00,849.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Junior Shock-Guard Watch','Junior Digital Watch in Blaze Orange.','2026-10-13 11:03:30','2026-10-13 11:03:30');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refunds`
--

DROP TABLE IF EXISTS `refunds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `refunds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `payment_id` bigint(20) unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `gateway_refund_id` varchar(100) DEFAULT NULL,
  `status` enum('pending','processed','failed') DEFAULT 'pending',
  `processed_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_ref_order` (`order_id`),
  CONSTRAINT `fk_ref_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refunds`
--

LOCK TABLES `refunds` WRITE;
/*!40000 ALTER TABLE `refunds` DISABLE KEYS */;
/*!40000 ALTER TABLE `refunds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_items`
--

DROP TABLE IF EXISTS `return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `return_id` bigint(20) unsigned NOT NULL,
  `order_item_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `reason` varchar(255) DEFAULT NULL,
  `condition_notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ri_ret` (`return_id`),
  CONSTRAINT `fk_ri_ret` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_items`
--

LOCK TABLES `return_items` WRITE;
/*!40000 ALTER TABLE `return_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `return_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `returns`
--

DROP TABLE IF EXISTS `returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `returns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('requested','approved','rejected','item_received','completed') DEFAULT 'requested',
  `reason` varchar(255) NOT NULL,
  `comments` text DEFAULT NULL,
  `is_restocked` tinyint(1) DEFAULT 0,
  `restocked_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_ret_order` (`order_id`),
  CONSTRAINT `fk_ret_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `returns`
--

LOCK TABLES `returns` WRITE;
/*!40000 ALTER TABLE `returns` DISABLE KEYS */;
/*!40000 ALTER TABLE `returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review_votes`
--

DROP TABLE IF EXISTS `review_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review_votes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `review_id` bigint(20) unsigned NOT NULL,
  `session_id` varchar(120) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_rv` (`review_id`,`session_id`),
  CONSTRAINT `fk_rv_rev` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review_votes`
--

LOCK TABLES `review_votes` WRITE;
/*!40000 ALTER TABLE `review_votes` DISABLE KEYS */;
INSERT INTO `review_votes` VALUES
(2,5,'jqujtdle2mdr2qc171q0qkjugj','::1','2026-10-15 17:33:15'),
(3,5,'12o2uigm9v44jm8lbd2s1deefp','::1','2026-10-15 17:33:42');
/*!40000 ALTER TABLE `review_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `author_name` varchar(120) NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `comment` text NOT NULL,
  `is_verified_purchase` tinyint(1) DEFAULT 0,
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `helpful_count` int(10) unsigned NOT NULL DEFAULT 0,
  `images_json` text DEFAULT NULL,
  `admin_response` text DEFAULT NULL,
  `admin_responded_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_rev_prod` (`product_id`),
  CONSTRAINT `fk_rev_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES
(5,3,NULL,NULL,NULL,'Arjun Singhania',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',20,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(6,3,NULL,NULL,NULL,'Pooja Deshmukh',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(7,3,NULL,NULL,NULL,'Rohan Mehta',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(8,4,NULL,NULL,NULL,'Arjun Singhania',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(9,4,NULL,NULL,NULL,'Pooja Deshmukh',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(10,4,NULL,NULL,NULL,'Rohan Mehta',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(11,7,NULL,NULL,NULL,'Arjun Singhania',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(12,7,NULL,NULL,NULL,'Pooja Deshmukh',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(13,7,NULL,NULL,NULL,'Rohan Mehta',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(14,12,NULL,NULL,NULL,'Arjun Singhania',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(15,12,NULL,NULL,NULL,'Pooja Deshmukh',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(16,12,NULL,NULL,NULL,'Rohan Mehta',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(17,13,NULL,NULL,NULL,'Arjun Singhania',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(18,13,NULL,NULL,NULL,'Pooja Deshmukh',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(19,13,NULL,NULL,NULL,'Rohan Mehta',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(20,14,NULL,NULL,NULL,'Arjun Singhania',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(21,14,NULL,NULL,NULL,'Pooja Deshmukh',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(22,14,NULL,NULL,NULL,'Rohan Mehta',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(23,15,NULL,NULL,NULL,'Arjun Singhania',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(24,15,NULL,NULL,NULL,'Pooja Deshmukh',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(25,15,NULL,NULL,NULL,'Rohan Mehta',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(26,16,NULL,NULL,NULL,'Arjun Singhania',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(27,16,NULL,NULL,NULL,'Pooja Deshmukh',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(28,16,NULL,NULL,NULL,'Rohan Mehta',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(29,20,NULL,NULL,NULL,'Arjun Singhania',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(30,20,NULL,NULL,NULL,'Pooja Deshmukh',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(31,20,NULL,NULL,NULL,'Rohan Mehta',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(32,21,NULL,NULL,NULL,'Arjun Singhania',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(33,21,NULL,NULL,NULL,'Pooja Deshmukh',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(34,21,NULL,NULL,NULL,'Rohan Mehta',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(35,3,NULL,NULL,NULL,'Kavita Chawla',5,'Exquisite finish and weight','The gold bezel and polished indices reflect light gorgeously. The butterfly clasp feels solid and secure on the wrist.',0,'approved',0,NULL,NULL,NULL,'2026-10-15 17:33:42');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permissions` (
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `fk_rp_perm` (`permission_id`),
  CONSTRAINT `fk_rp_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES
(1,1),
(1,2),
(1,3),
(1,4),
(1,5),
(1,6),
(1,7),
(1,8),
(1,9),
(1,10),
(1,11),
(1,12),
(1,13),
(1,14),
(1,15),
(1,16),
(1,17),
(1,18),
(1,19),
(1,20),
(1,21),
(1,22),
(1,23);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(60) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'Owner','owner','Full administrative authority and system settings control','2026-10-12 16:57:26'),
(2,'Manager','manager','Operational management, orders, catalog and customer handling','2026-10-12 16:57:26'),
(3,'Catalog Manager','catalog_manager','Products, inventory, collections and bulk importer','2026-10-12 16:57:26'),
(4,'Marketing Manager','marketing_manager','Campaigns, banners, coupons and advertising reports','2026-10-12 16:57:26'),
(5,'Support Agent','support_agent','Order lookup, customer support and return reviews','2026-10-12 16:57:26'),
(6,'Analyst','analyst','Read-only business analytics and performance reports','2026-10-12 16:57:26');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_shipments`
--

DROP TABLE IF EXISTS `shipping_shipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shipping_shipments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `courier_name` varchar(100) NOT NULL,
  `tracking_number` varchar(100) NOT NULL,
  `tracking_url` varchar(255) DEFAULT NULL,
  `dispatched_at` datetime DEFAULT NULL,
  `estimated_delivery_at` date DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_ship_order` (`order_id`),
  CONSTRAINT `fk_ship_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_shipments`
--

LOCK TABLES `shipping_shipments` WRITE;
/*!40000 ALTER TABLE `shipping_shipments` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipping_shipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `store_settings`
--

DROP TABLE IF EXISTS `store_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` longtext DEFAULT NULL,
  `group_name` varchar(50) NOT NULL DEFAULT 'general',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_settings`
--

LOCK TABLES `store_settings` WRITE;
/*!40000 ALTER TABLE `store_settings` DISABLE KEYS */;
INSERT INTO `store_settings` VALUES
(1,'site_name','Welkar','general','2026-10-12 16:57:27'),
(2,'business_legal_name','Welkar','legal','2026-10-13 11:07:16'),
(3,'business_gstin','24LOJYK3087M','legal','2026-10-13 11:07:55'),
(4,'business_state','Gujarat','legal','2026-10-13 11:07:16'),
(5,'business_state_code','24','legal','2026-10-13 11:07:16'),
(6,'business_email','welkarstore@gmail.com','general','2026-10-12 16:57:27'),
(7,'business_phone','+91 98765 43210','general','2026-10-12 16:57:27'),
(8,'default_shipping_fee','150','shipping','2026-10-13 11:07:16'),
(9,'free_shipping_threshold','1499.00','shipping','2026-10-12 16:57:27'),
(10,'cod_enabled','1','payment','2026-10-12 16:57:27'),
(11,'cod_max_amount','5000.00','payment','2026-10-12 16:57:27'),
(12,'razorpay_mode','test','payment','2026-10-12 16:57:27'),
(13,'razorpay_key_id','rzp_test_placeholder_key','payment','2026-10-12 16:57:27'),
(14,'razorpay_key_secret','','payment','2026-10-12 16:57:27'),
(15,'default_gst_rate','18.00','tax','2026-10-12 16:57:27'),
(16,'invoice_prefix','WEL','invoice','2026-10-12 16:57:27');
/*!40000 ALTER TABLE `store_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(20) NOT NULL DEFAULT 'info',
  `message` text NOT NULL,
  `context` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_logs`
--

LOCK TABLES `system_logs` WRITE;
/*!40000 ALTER TABLE `system_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author_name` varchar(120) NOT NULL,
  `author_location` varchar(100) DEFAULT NULL,
  `quote` text NOT NULL,
  `rating` tinyint(3) unsigned DEFAULT 5,
  `watch_model` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES
(1,'Kabir Singhania','Mumbai, MH','Welkar has redefined accessible luxury watch shopping in India. The Aura watch on my wrist gets compliments in every boardroom meeting.',5,'Welkar Aura Minimalist Steel',1,1,'2026-10-12 16:57:27'),
(2,'Ananya Roy','Bengaluru, KA','Ordered the Celestial Moon Phase for my anniversary. The packaging, warranty card, and sheer dial detailing blew us away.',5,'Welkar Celestial Moon Phase',1,2,'2026-10-12 16:57:27'),
(3,'Sameer Khan','New Delhi, DL','Safe delivery, genuine invoice with GST breakdown, and clear tracking from order to doorstep. Excellent customer care.',5,'Welkar Chrono Heritage',1,3,'2026-10-12 16:57:27');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `is_phone_verified` tinyint(1) DEFAULT 0,
  `is_email_verified` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_phone` (`phone`),
  KEY `idx_users_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'9999988888',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:13:54','2026-10-12 17:13:54'),
(2,'9999285660',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:14:55','2026-10-12 17:14:55'),
(3,'9999812649',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:17:32','2026-10-12 17:17:32'),
(4,'9999275854',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:37:59','2026-10-12 17:37:59'),
(5,'9999353224',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:47:20','2026-10-12 17:47:20'),
(6,'9999774483',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:57:04','2026-10-12 17:57:04'),
(7,'9999301523',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 18:15:12','2026-10-12 18:15:12'),
(8,'9999722284',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 18:37:33','2026-10-12 18:37:33'),
(9,'9999639282',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 18:38:23','2026-10-12 18:38:23'),
(10,'9999477659',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 18:39:44','2026-10-12 18:39:44'),
(11,'9999164076',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 19:14:05','2026-10-12 19:14:05'),
(12,'9999284334',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 19:30:22','2026-10-12 19:30:22'),
(13,'9999985943',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 19:36:35','2026-10-12 19:36:35'),
(14,'9999357922',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 19:37:53','2026-10-12 19:37:53'),
(15,'9999295716',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 19:43:27','2026-10-12 19:43:27'),
(16,'9999150644',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 09:24:43','2026-10-13 09:24:43'),
(17,'9999494762',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 09:26:41','2026-10-13 09:26:41'),
(18,'9999559018',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 09:39:53','2026-10-13 09:39:53'),
(19,'9999568560',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 09:49:34','2026-10-13 09:49:34'),
(20,'9999295815',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 10:40:12','2026-10-13 10:40:12'),
(21,'9999379252',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 10:48:03','2026-10-13 10:48:03'),
(22,'9999752850',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 10:48:37','2026-10-13 10:48:37'),
(23,'9999999823',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 10:50:58','2026-10-13 10:50:58'),
(24,'9999138211',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 10:51:51','2026-10-13 10:51:51'),
(25,'9999474776',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 11:03:48','2026-10-13 11:03:48'),
(26,'9999982305',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 11:37:53','2026-10-13 11:37:53'),
(27,'9999856096',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 11:44:52','2026-10-13 11:44:52'),
(28,'9999168002',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 11:54:13','2026-10-13 11:54:13'),
(29,'9999300888',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 12:00:35','2026-10-13 12:00:35'),
(30,'9999628748',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 12:10:44','2026-10-13 12:10:44'),
(31,'9999931876',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 12:22:26','2026-10-13 12:22:26'),
(32,'9999400102',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 12:24:48','2026-10-13 12:24:48'),
(33,'9999679620',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 12:37:22','2026-10-13 12:37:22'),
(34,'9999364294',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 15:09:57','2026-10-13 15:09:57');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utm_sessions`
--

DROP TABLE IF EXISTS `utm_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utm_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(120) NOT NULL,
  `utm_source` varchar(80) DEFAULT NULL,
  `utm_medium` varchar(80) DEFAULT NULL,
  `utm_campaign` varchar(120) DEFAULT NULL,
  `utm_content` varchar(120) DEFAULT NULL,
  `utm_term` varchar(120) DEFAULT NULL,
  `first_seen_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_utms_sess` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utm_sessions`
--

LOCK TABLES `utm_sessions` WRITE;
/*!40000 ALTER TABLE `utm_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `utm_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webhook_events`
--

DROP TABLE IF EXISTS `webhook_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webhook_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gateway` varchar(50) NOT NULL,
  `event_id` varchar(100) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `payload` longtext NOT NULL,
  `status` enum('pending','processed','ignored','failed') DEFAULT 'pending',
  `processed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_whe` (`gateway`,`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webhook_events`
--

LOCK TABLES `webhook_events` WRITE;
/*!40000 ALTER TABLE `webhook_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `webhook_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist_items`
--

DROP TABLE IF EXISTS `wishlist_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlist_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `wishlist_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_wli` (`wishlist_id`,`product_id`,`variant_id`),
  KEY `fk_wli_prod` (`product_id`),
  CONSTRAINT `fk_wli_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wli_wl` FOREIGN KEY (`wishlist_id`) REFERENCES `wishlists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist_items`
--

LOCK TABLES `wishlist_items` WRITE;
/*!40000 ALTER TABLE `wishlist_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlist_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(120) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_wl_user` (`user_id`),
  KEY `idx_wl_session` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
INSERT INTO `wishlists` VALUES
(1,NULL,'dbdfd791332d6ab06ae5a2a611e0f5a7','2026-10-15 16:18:52'),
(2,NULL,'tvviniqa8tnc4ulv9258n9jinn','2026-10-15 16:19:59'),
(3,NULL,'td3tijoet4bgrfrn38g6qdq9m6','2026-10-15 16:20:28'),
(4,NULL,'0qgjt9de61f1vocrp5s1ecvqt5','2026-10-15 16:29:16'),
(5,NULL,'3v4n9k1d3fkci10fq7s2hds828','2026-10-15 16:31:17'),
(6,NULL,'5d97afb8dce42d218291bd7dfaa2819b','2026-10-15 16:39:36'),
(7,NULL,'mg1b4ubs4ugahtelnnad54lgbd','2026-10-15 16:59:24'),
(8,NULL,'2vfku3s7g35t8i06mrrq4b9vif','2026-10-15 16:59:31'),
(9,NULL,'psknkfg2ki7a48lm1qepvojp45','2026-10-15 16:59:37'),
(10,NULL,'vjuhmhk2jl3q9vbf26rfq0k6m0','2026-10-15 16:59:48'),
(11,NULL,'stfq5r6nt172gick1nkhvt1oh7','2026-10-15 16:59:54'),
(12,NULL,'euj6tnl2ci6jb3boploq08iplh','2026-10-15 17:00:01'),
(13,NULL,'b91fde279fb8914bc02869d06f42c9a1','2026-10-15 17:00:09'),
(14,NULL,'3s3f15eo044upvgeso4kigs80r','2026-10-15 17:26:50'),
(15,NULL,'9v60jju4f38r6bj12383oaf1g8','2026-10-15 17:28:06'),
(16,NULL,'i1oq2hs2k5s58ff7q6c06nilha','2026-10-15 17:28:39'),
(17,NULL,'2gl0fu7qtsefah15ed6ulmf7v0','2026-10-15 17:31:42'),
(18,NULL,'n87r44j2n2bk4gug01tmj1fek1','2026-10-15 17:33:15'),
(19,NULL,'scmdh2pco97em0hgqpnst7s9e0','2026-10-15 17:33:42'),
(20,NULL,'a3d70da0657fa52922b179b09d774800','2026-10-15 17:33:49'),
(21,NULL,'84b1cdc38fe9800364a30364d027f0db','2026-10-15 18:55:33'),
(22,NULL,'o183u3no73nss09jc2o5pjifct','2026-10-15 18:56:21'),
(23,NULL,'m7de6hm4uksbthelk9b4esoor4','2026-10-15 18:56:53'),
(24,NULL,'b73rkrt52t7ikfmtrlipn527vf','2026-10-15 18:58:06'),
(25,NULL,'b11fbcfd593baeef452b033ddec3b3bb','2026-10-15 23:19:11'),
(26,NULL,'tjglpju17r5sss1ieqqdkqqlii','2026-10-15 23:19:33'),
(27,NULL,'rmner3icafau1aomutuc7s0jq2','2026-10-15 23:22:26'),
(28,NULL,'b801e58fd67538c65ac6cb14ca82286f','2026-10-15 23:41:28'),
(29,NULL,'qkennjjcovmlu13omqojdnhvfd','2026-10-15 23:43:50'),
(30,NULL,'aa1b801a378013cc6cb5a5c7881cf4c2','2026-10-15 23:52:16'),
(31,NULL,'2dn9op3e2ah06ted3uchspb594','2026-10-15 23:52:48'),
(32,NULL,'u12r32u4lfehnu3gagvc3qd59e','2026-10-15 23:52:59'),
(33,NULL,'sl6g94va7na4lhjnuckibfodgp','2026-10-15 23:53:09'),
(34,NULL,'8fapsokvu52a90tjee8dsf7819','2026-10-15 23:53:21'),
(35,NULL,'p2iip6nvjoup3aoqq9k2367d8g','2026-10-15 23:53:36'),
(36,NULL,'jv64kbr1vq3je96kppenirrq9s','2026-10-15 23:54:01'),
(37,NULL,'f75nififmt29aqk0qv08n0ufqu','2026-10-15 23:54:11'),
(38,NULL,'t9r2vft92kttfb4eibjchingko','2026-10-15 23:55:02'),
(39,NULL,'c8e88a0733ada998b94152f434304579','2026-10-15 23:55:39'),
(40,NULL,'3cd4hnl3veil7up0ccfjkflq39','2026-10-16 00:09:21'),
(41,NULL,'a35ibl2lfpmvps6ehfkdj5q098','2026-10-16 00:13:03'),
(42,NULL,'9cbde38c2f042f42822735f27e876785','2026-10-16 00:13:10'),
(43,NULL,'76d925674bd33929a119e21a5d4a7c4f','2026-10-16 10:18:26'),
(44,NULL,'28dvnkvhcehfdo28ctbmgp4lv9','2026-10-16 10:19:11'),
(45,NULL,'qponajbnluqrod4qv9gcfqedu5','2026-10-16 10:20:05'),
(46,NULL,'rs8bthoneu1j49idh0it3ief97','2026-10-16 10:34:16'),
(47,NULL,'0820a2eadf7a5409ff59161b954c5dc7','2026-10-16 10:34:23'),
(48,NULL,'81179878433d597129a5de23aa9f480e','2026-10-16 11:48:11'),
(49,NULL,'6atm1si6u49i2t0knotsqh9r0v','2026-10-16 11:48:49'),
(50,NULL,'v1ldi65tf03jrku4e6drptbf82','2026-10-16 11:48:49'),
(51,NULL,'229ipg8rs7p1gkd3jqj0i1ul5k','2026-10-16 11:49:00'),
(52,NULL,'mk0b5d62sgv0bod1musi7eijrs','2026-10-16 11:49:00'),
(53,NULL,'m9lo5trj3j2r5dohv4v3b08jce','2026-10-16 11:49:11'),
(54,NULL,'eu60aa2ekn1kbrlfv20ovu04m4','2026-10-16 11:49:11'),
(55,NULL,'anvokrjtd5pf6qojjfvkf0eiga','2026-10-16 11:49:16'),
(56,NULL,'rep33co1ioja27t84earnmr7an','2026-10-16 11:49:16'),
(57,NULL,'heqi8t6kpm0qqnai32n7dac3gc','2026-10-16 11:50:46'),
(58,NULL,'or3tqkafi770j5kvqagkgdgs1k','2026-10-16 11:51:24'),
(59,NULL,'emukhprabijg2igo09pus1b4b4','2026-10-16 11:51:24'),
(60,NULL,'705365c2f60fdae3294b180151cfb7e8','2026-10-16 15:47:52'),
(61,NULL,'vmu1u2jc688bccc1kib2rvb6pq','2026-10-16 15:48:24'),
(62,NULL,'i42gfdj7o0pdqu0ff9tho82kbl','2026-10-16 16:02:19'),
(63,NULL,'abcde911fa689fb4b65eac4cd5d54d05','2026-10-16 16:19:46'),
(64,NULL,'9e68d6bb2821104f3f032851482297c2','2026-10-16 16:27:12'),
(65,NULL,'fhtnrm54hbdgnrrdftnbkcamkc','2026-10-16 16:45:44'),
(66,NULL,'374e351f1be3005c38731ffce51e39a3','2026-10-16 17:32:23'),
(67,NULL,'b6c9bb8b41b34efa8012fe9b7bd36f68','2026-10-16 17:50:20'),
(68,NULL,'04e559b9e912bd1b90107db6e42e75cf','2026-10-16 17:51:11'),
(69,NULL,'e639761bb2d4d8e99149264377306667','2026-10-16 17:51:44'),
(70,NULL,'c18fa389c5d7a1670bcbf19b700ab22d','2026-10-16 17:53:12'),
(71,NULL,'4926f4ef1dc62dc6aa132544cda2693f','2026-10-16 18:04:43'),
(72,NULL,'f4c209aade84f1d73b1fb656f1d3bb2a','2026-10-16 18:09:30'),
(73,NULL,'ec6c3e4ca1ba446c9ed440ef7417d64f','2026-10-16 18:13:10'),
(74,NULL,'lan8bidl2lomj09sisoblv38h1','2026-10-16 18:14:46'),
(75,NULL,'0bd3e6069bd31f5709ce4068bff4d1af','2026-10-16 19:13:19'),
(76,NULL,'vcob9jkp5ho45s6ub7drn29via','2026-10-16 19:17:01'),
(77,NULL,'bf7cbefcf5eeaa300a21b31a4d08391f','2026-10-16 19:25:50'),
(78,NULL,'412d85ebfc114d83a02a5a61155af710','2026-10-16 19:26:57'),
(79,NULL,'ee0e70cd3678c5c78eab657c28e73e19','2026-10-16 19:30:11');
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-16 19:30:54
