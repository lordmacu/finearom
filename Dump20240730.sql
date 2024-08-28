-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: finearom
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `branch_offices`
--

DROP TABLE IF EXISTS `branch_offices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branch_offices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `nit` varchar(255) NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `contact` varchar(255) NOT NULL,
  `delivery_address` text NOT NULL,
  `delivery_city` varchar(255) NOT NULL,
  `billing_address` text NOT NULL,
  `billing_contact` text NOT NULL,
  `billing_city` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `shipping_observations` text,
  `general_observations` text,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `branch_offices_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch_offices`
--

LOCK TABLES `branch_offices` WRITE;
/*!40000 ALTER TABLE `branch_offices` DISABLE KEYS */;
INSERT INTO `branch_offices` VALUES (1,'un','111',3,'324','asf','saf','sad','234234','bogota','234','dsf','sf','2024-07-30 05:03:50','2024-07-30 04:50:34'),(2,'una sucursal','1023884027',4,'asf','asf','adsf','ad','sdf','dsf','f24`','sdfs','sdf','2024-07-30 05:24:38','2024-07-30 05:23:15'),(3,'uno uno','424',2,'jh','kjk','sdf','fds','kjk','fds','234','dsf','sfsd','2024-07-30 06:19:37','2024-07-30 06:19:37');
/*!40000 ALTER TABLE `branch_offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_type_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `weight` int NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_category_type_id_slug_unique` (`category_type_id`,`slug`),
  CONSTRAINT `categories_category_type_id_foreign` FOREIGN KEY (`category_type_id`) REFERENCES `category_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_types`
--

DROP TABLE IF EXISTS `category_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `machine_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_flat` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_types_machine_name_unique` (`machine_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_types`
--

LOCK TABLES `category_types` WRITE;
/*!40000 ALTER TABLE `category_types` DISABLE KEYS */;
INSERT INTO `category_types` VALUES (1,'Category','Main Category','category',0,'2024-07-27 04:12:13','2024-07-27 04:12:13'),(2,'Tag','Site Tags','tag',1,'2024-07-27 04:12:13','2024-07-27 04:12:13');
/*!40000 ALTER TABLE `category_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_observations`
--

DROP TABLE IF EXISTS `client_observations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_observations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `requires_physical_invoice` tinyint(1) NOT NULL DEFAULT '0',
  `is_in_free_zone` tinyint(1) NOT NULL DEFAULT '0',
  `packaging_unit` int DEFAULT NULL,
  `reteica` int DEFAULT NULL,
  `reteiva` int DEFAULT NULL,
  `retefuente` int DEFAULT NULL,
  `requires_appointment` tinyint(1) NOT NULL DEFAULT '0',
  `additional_observations` text COLLATE utf8mb4_unicode_ci,
  `billing_closure_date` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_observations_client_id_foreign` (`client_id`),
  CONSTRAINT `client_observations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_observations`
--

LOCK TABLES `client_observations` WRITE;
/*!40000 ALTER TABLE `client_observations` DISABLE KEYS */;
INSERT INTO `client_observations` VALUES (1,2,1,1,122,1,3,2,1,'observacion','8 de cada mes','2024-07-27 05:53:46','2024-07-27 06:25:49');
/*!40000 ALTER TABLE `client_observations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `client_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci,
  `executive` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_closure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commercial_conditions` text COLLATE utf8mb4_unicode_ci,
  `proforma_invoice` tinyint(1) DEFAULT '0',
  `payment_method` tinyint DEFAULT NULL,
  `payment_day` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_nit_unique` (`nit`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (2,'uno','243324','2024-07-27 05:21:19','2024-07-30 04:09:17','pareto','YOO@dfad.com','executive','address','city','billing','comercial condicions',0,1,34,4,'active'),(3,'dos','ni','2024-07-30 04:10:08','2024-07-30 04:10:08','pareto','email@fasdfad.com','executive','address','city','billing','comercial',0,1,33,NULL,'active'),(4,'primera','234234','2024-07-30 04:58:48','2024-07-30 05:23:49','pareto','sdfsa@asfdfd.com','sfsd',NULL,NULL,'sd','asfa',0,1,32,NULL,'active');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `weight` int NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `icon` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,1,'Dashboard','/<admin>',NULL,NULL,0,1,'M13,3V9H21V3M13,21H21V11H13M3,21H11V15H3M3,13H11V3H3V13Z','2024-07-27 04:12:13','2024-07-27 04:12:13'),(2,1,'Permissions','/<admin>/permission',NULL,NULL,1,1,'M12,12H19C18.47,16.11 15.72,19.78 12,20.92V12H5V6.3L12,3.19M12,1L3,5V11C3,16.55 6.84,21.73 12,23C17.16,21.73 21,16.55 21,11V5L12,1Z','2024-07-27 04:12:13','2024-07-27 04:12:13'),(3,1,'Roles','/<admin>/role',NULL,NULL,2,1,'M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z','2024-07-27 04:12:13','2024-07-27 04:12:13'),(4,1,'Users','/<admin>/user',NULL,NULL,3,1,'M16 17V19H2V17S2 13 9 13 16 17 16 17M12.5 7.5A3.5 3.5 0 1 0 9 11A3.5 3.5 0 0 0 12.5 7.5M15.94 13A5.32 5.32 0 0 1 18 17V19H22V17S22 13.37 15.94 13M15 4A3.39 3.39 0 0 0 13.07 4.59A5 5 0 0 1 13.07 10.41A3.39 3.39 0 0 0 15 11A3.5 3.5 0 0 0 15 4Z','2024-07-27 04:12:13','2024-07-27 04:12:13'),(5,1,'Menus','/<admin>/menu',NULL,NULL,4,1,'M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z','2024-07-27 04:12:13','2024-07-27 04:12:13'),(6,1,'Categories','/<admin>/category/type',NULL,NULL,4,1,'M5 3A2 2 0 0 0 3 5H5M7 3V5H9V3M11 3V5H13V3M15 3V5H17V3M19 3V5H21A2 2 0 0 0 19 3M3 7V9H5V7M7 7V11H11V7M13 7V11H17V7M19 7V9H21V7M3 11V13H5V11M19 11V13H21V11M7 13V17H11V13M13 13V17H17V13M3 15V17H5V15M19 15V17H21V15M3 19A2 2 0 0 0 5 21V19M7 19V21H9V19M11 19V21H13V19M15 19V21H17V19M19 19V21A2 2 0 0 0 21 19Z','2024-07-27 04:12:13','2024-07-27 04:12:13'),(7,1,'Clients','/<admin>/client',NULL,NULL,0,1,'M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12M12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z','2024-07-27 04:34:44','2024-07-27 21:28:35'),(11,1,'Product','/<admin>/product',NULL,NULL,0,1,'M7 4H2V2H7C7.28 2 7.53 2.16 7.63 2.43L8.82 5.86L17.35 5.58C18.08 5.56 18.72 6 19.05 6.68L21.84 12.66C21.94 12.88 22 13.13 22 13.39C22 14.26 21.26 15 20.39 15H9.27L9.79 17H19V19H9C8.72 19 8.47 18.84 8.36 18.57L7.18 15.14L4.03 4H7M1 1H5L7 7H17.53L15.57 10.97L9.34 11.1L8.76 8H1V1M6 18C7.11 18 8 18.89 8 20C8 21.11 7.11 22 6 22C4.89 22 4 21.11 4 20C4 18.89 4.89 18 6 18M18 18C19.11 18 20 18.89 20 20C20 21.11 19.11 22 18 22C16.89 22 16 21.11 16 20C16 18.89 16.89 18 18 18Z','2024-07-27 05:23:08','2024-07-27 21:27:20'),(12,1,'Ordenes de Compra','/<admin>/purchase_orders',NULL,NULL,0,1,'M13 9H18V11H13M13 13H18V15H13M13 17H18V19H13M11 9H6V11H11M11 13H6V15H11M11 17H6V19H11M21 3H3C1.9 3 1 3.9 1 5V21C1 22.1 1.9 23 3 23H21C22.1 23 23 22.1 23 21V5C23 3.9 22.1 3 21 3M21 21H3V5H21V21Z','2024-07-27 18:54:52','2024-07-27 21:28:00');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `machine_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_machine_name_unique` (`machine_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'Admin','Admin Menu','admin','2024-07-27 04:12:13','2024-07-27 04:12:13');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2022_02_08_033617_create_permission_tables',1),(6,'2024_07_26_231201_create_category_tables',1),(7,'2024_07_26_231201_create_menu_tables',1),(8,'2024_07_26_232032_create_products_table',2),(9,'2024_07_26_232041_create_purchase_orders_table',2),(10,'2024_07_26_232050_create_clients_table',2),(11,'2024_07_26_235825_create_products_table',3),(12,'2024_07_27_001820_update_clients_table',4),(13,'2024_07_27_003219_create_client_observations_table',5),(14,'2024_07_27_134216_create_purchase_orders_table',6),(15,'2024_07_27_134349_create_purchase_order_products_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_categories`
--

DROP TABLE IF EXISTS `model_has_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_categories` (
  `category_id` bigint unsigned NOT NULL,
  `category_item_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_item_id` bigint unsigned DEFAULT NULL,
  KEY `model_has_categories_category_id_foreign` (`category_id`),
  KEY `model_has_categories_category_item_type_category_item_id_index` (`category_item_type`,`category_item_id`),
  CONSTRAINT `model_has_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_categories`
--

LOCK TABLES `model_has_categories` WRITE;
/*!40000 ALTER TABLE `model_has_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (3,'App\\Models\\User',1),(2,'App\\Models\\User',2),(1,'App\\Models\\User',3),(4,'App\\Models\\User',4),(4,'App\\Models\\User',5),(4,'App\\Models\\User',6);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'permission list','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(2,'permission create','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(3,'permission edit','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(4,'permission delete','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(5,'role list','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(6,'role create','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(7,'role edit','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(8,'role delete','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(9,'user list','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(10,'user create','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(11,'user edit','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(12,'user delete','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(13,'menu list','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(14,'menu create','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(15,'menu edit','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(16,'menu delete','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(17,'menu.item list','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(18,'menu.item create','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(19,'menu.item edit','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(20,'menu.item delete','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(21,'category list','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(22,'category create','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(23,'category edit','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(24,'category delete','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(25,'category.type list','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(26,'category.type create','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(27,'category.type edit','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(28,'category.type delete','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(29,'client list','web','2024-07-27 04:30:26','2024-07-27 04:30:26'),(30,'client edit','web','2024-07-27 04:30:36','2024-07-27 04:30:36'),(31,'client create','web','2024-07-27 04:30:46','2024-07-27 04:30:46'),(32,'client delete','web','2024-07-27 04:32:16','2024-07-27 04:32:16'),(33,'product list','web','2024-07-27 05:21:45','2024-07-27 05:21:45'),(34,'product create','web','2024-07-27 05:21:56','2024-07-27 05:21:56'),(35,'product edit','web','2024-07-27 05:22:06','2024-07-27 05:22:06'),(36,'product delete','web','2024-07-27 05:22:20','2024-07-27 05:22:20'),(37,'purchase_order list','web','2024-07-27 18:52:51','2024-07-27 18:52:51'),(38,'purchase_order create','web','2024-07-27 18:53:05','2024-07-27 18:53:05'),(39,'purchase_order edit','web','2024-07-27 18:53:21','2024-07-27 18:53:21'),(40,'purchase_order delete','web','2024-07-27 18:53:34','2024-07-27 18:53:34');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_code_unique` (`code`),
  KEY `products_client_id_foreign` (`client_id`),
  CONSTRAINT `products_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'one','one',100.00,3,'2024-07-27 19:03:05','2024-07-30 05:46:36'),(2,'3424','producto2',123.00,3,'2024-07-30 06:18:21','2024-07-30 06:18:21'),(3,'23432','uno uno product',3244.00,2,'2024-07-30 06:20:17','2024-07-30 06:20:17'),(4,'iiii','ewrw',333.00,4,'2024-07-30 06:31:08','2024-07-30 06:31:08'),(5,'sdfd','new',222.00,2,'2024-07-30 19:43:25','2024-07-30 19:43:25');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_product`
--

DROP TABLE IF EXISTS `purchase_order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order_product` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL DEFAULT '0',
  `price` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_products_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_products_client_id_foreign` (`product_id`) USING BTREE,
  CONSTRAINT `purchase_order_products_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_product`
--

LOCK TABLES `purchase_order_product` WRITE;
/*!40000 ALTER TABLE `purchase_order_product` DISABLE KEYS */;
INSERT INTO `purchase_order_product` VALUES (7,5,1,11,23,NULL,NULL),(10,6,1,34,0,NULL,NULL),(11,7,1,324,0,NULL,NULL),(20,8,3,34,3244,NULL,NULL),(21,9,3,34,3244,NULL,NULL),(22,10,3,34,3244,NULL,NULL),(28,4,5,66,0,NULL,NULL);
/*!40000 ALTER TABLE `purchase_order_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `branch_office_id` bigint unsigned NOT NULL,
  `order_creation_date` date DEFAULT NULL,
  `required_delivery_date` date DEFAULT NULL,
  `order_consecutive` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observations` text COLLATE utf8mb4_unicode_ci,
  `delivery_address` varbinary(240) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_orders_client_id_foreign` (`client_id`),
  CONSTRAINT `purchase_orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
INSERT INTO `purchase_orders` VALUES (4,2,3,'2024-07-23','2024-08-08','234','safasdf',_binary 'asf','2024-07-27 20:28:32','2024-07-30 19:48:31'),(5,2,0,'2024-07-30','2024-07-18','222','www',_binary 'sss','2024-07-27 21:06:22','2024-07-27 21:06:22'),(6,2,0,'2024-07-10','2024-07-31','232','sadfasd',_binary 'sdafadf','2024-07-28 07:57:19','2024-07-28 07:57:19'),(7,3,1,'2024-08-04','2024-07-27','dfasd','kjk',_binary 'sf','2024-07-30 05:52:17','2024-07-30 05:52:17'),(8,2,3,'2024-07-30','2024-08-07','32423','safd',_binary 'sdfa','2024-07-30 19:07:28','2024-07-30 19:07:28'),(9,2,3,'2024-07-30','2024-08-07','3242334','safd',_binary 'sdfa','2024-07-30 19:08:16','2024-07-30 19:08:16'),(10,2,3,'2024-07-30','2024-08-01','dssdf','afa',_binary 'fafdsasd','2024-07-30 19:10:02','2024-07-30 19:10:02');
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(5,1),(9,1),(13,1),(17,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(16,2),(17,2),(18,2),(19,2),(20,2),(21,2),(22,2),(23,2),(24,2),(25,2),(26,2),(27,2),(28,2),(29,2),(30,2),(31,2),(32,2),(33,2),(34,2),(35,2),(36,2),(37,2),(38,2),(39,2),(40,2);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'writer','web','2024-07-27 04:12:12','2024-07-27 04:12:12'),(2,'admin','web','2024-07-27 04:12:13','2024-07-27 04:12:13'),(3,'super-admin','web','2024-07-27 04:12:13','2024-07-27 04:12:13'),(4,'order-creator','web','2024-07-27 23:04:18','2024-07-27 23:04:18');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','superadmin@example.com','2024-07-27 04:12:13','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','DzpOLCk9UhE9hNNej3Eo29aJ8lRvnthcViIt4lergAmZmIcBrFWMp6Xg4Adx','2024-07-27 04:12:13','2024-07-27 04:12:13'),(2,'Admin User','admin@example.com','2024-07-27 04:12:13','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','ed67fjLtak','2024-07-27 04:12:13','2024-07-27 04:12:13'),(3,'Example User','test@example.com','2024-07-27 04:12:13','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','6J6MtfKL8b','2024-07-27 04:12:13','2024-07-27 04:12:13'),(4,'informacion','informacion@cristiangarcia.co',NULL,'$2y$10$tMYSyUyZdT.IjCBJ9K6tMe/yVoI1KrGGRfd8wOdtDBLE2RD2hxROe',NULL,'2024-07-28 08:00:16','2024-07-28 08:00:16'),(5,'dos','email@fasdfad.com',NULL,'$2y$10$19nAwzpsj5QbV52rsHUKp.pTOGUW4P6oKBcmqyEco7zFMMAy0LCaC',NULL,'2024-07-30 04:10:08','2024-07-30 04:10:08'),(6,'sdf','sdfsa@asfdfd.com',NULL,'$2y$10$Xic6aHepYG0JbVx0DtP4uOFIhzFhiL1ZmGPMmJKwQocIug.B4yxJ6',NULL,'2024-07-30 04:58:48','2024-07-30 04:58:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-07-30 10:16:11
