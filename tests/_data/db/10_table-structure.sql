-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: testdb	Database: test
-- ------------------------------------------------------
-- Server version 	8.0.24
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
-- Table structure for table `prime2_access_request`
--

DROP TABLE IF EXISTS `prime2_access_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_access_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `target_class` varchar(255) NOT NULL,
  `target_id` int NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `permissions` json DEFAULT NULL,
  `accepted` tinyint(1) DEFAULT NULL,
  `response` text,
  `created_by` int DEFAULT NULL,
  `responded_by` int DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `expires_at` int DEFAULT NULL,
  `responded_at` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i-access_request-target_class-target_id` (`target_class`,`target_id`),
  KEY `fk-access_request-created_by-user-id` (`created_by`),
  KEY `fk-access_request-responded_by-user-id` (`responded_by`),
  CONSTRAINT `fk-access_request-created_by-user-id` FOREIGN KEY (`created_by`) REFERENCES `prime2_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk-access_request-responded_by-user-id` FOREIGN KEY (`responded_by`) REFERENCES `prime2_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_auth_assignment`
--

DROP TABLE IF EXISTS `prime2_auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `prime2_idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `prime2_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `prime2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_auth_item`
--

DROP TABLE IF EXISTS `prime2_auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_auth_item` (
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `prime2_idx-auth_item-type` (`type`),
  CONSTRAINT `prime2_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `prime2_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_auth_item_child`
--

DROP TABLE IF EXISTS `prime2_auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_auth_item_child` (
  `parent` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `prime2_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `prime2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prime2_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `prime2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_auth_rule`
--

DROP TABLE IF EXISTS `prime2_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_auth_rule` (
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_country_status`
--

DROP TABLE IF EXISTS `prime2_country_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_country_status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(400) DEFAULT NULL,
  `status_id` int DEFAULT NULL,
  `geodata` text,
  `stats` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_element`
--

DROP TABLE IF EXISTS `prime2_element`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_element` (
  `id` int NOT NULL AUTO_INCREMENT,
  `page_id` int NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `config` json DEFAULT NULL,
  `sort` int NOT NULL,
  `transpose` tinyint(1) NOT NULL DEFAULT '0',
  `width` tinyint unsigned NOT NULL DEFAULT '1',
  `height` tinyint unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=292 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_favorite`
--

DROP TABLE IF EXISTS `prime2_favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_favorite` (
  `user_id` int NOT NULL,
  `target_class` varchar(255) NOT NULL,
  `target_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`target_class`,`target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_file`
--

DROP TABLE IF EXISTS `prime2_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `mime_type` varchar(255) NOT NULL,
  `data` longblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_indicator`
--

DROP TABLE IF EXISTS `prime2_indicator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_indicator` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rendering_type` varchar(20) DEFAULT NULL,
  `indicator_name` varchar(80) DEFAULT NULL,
  `descr` varchar(400) DEFAULT NULL,
  `query` text,
  `cr_date` datetime DEFAULT NULL,
  `up_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_indicator_option`
--

DROP TABLE IF EXISTS `prime2_indicator_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_indicator_option` (
  `id` int NOT NULL AUTO_INCREMENT,
  `indicator_id` int DEFAULT NULL,
  `option_code` varchar(20) DEFAULT NULL,
  `option_label` varchar(200) DEFAULT NULL,
  `option_color` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-indicator-option-indicator-id` (`indicator_id`),
  KEY `idx-indicator-option-option-code` (`option_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_key`
--

DROP TABLE IF EXISTS `prime2_key`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_key` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `hash` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_message`
--

DROP TABLE IF EXISTS `prime2_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_message` (
  `id` int NOT NULL,
  `language` varchar(16) NOT NULL,
  `translation` text,
  PRIMARY KEY (`id`,`language`),
  CONSTRAINT `fk_message_source_message` FOREIGN KEY (`id`) REFERENCES `prime2_source_message` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_migration`
--

DROP TABLE IF EXISTS `prime2_migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_page`
--

DROP TABLE IF EXISTS `prime2_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_page` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `project_id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `sort` int NOT NULL,
  `add_services` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `page_project` (`project_id`),
  KEY `page_page` (`parent_id`),
  CONSTRAINT `page_page` FOREIGN KEY (`parent_id`) REFERENCES `prime2_page` (`id`),
  CONSTRAINT `page_project` FOREIGN KEY (`project_id`) REFERENCES `prime2_project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_permission`
--

DROP TABLE IF EXISTS `prime2_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_permission` (
  `id` int NOT NULL AUTO_INCREMENT,
  `source` varchar(255) NOT NULL,
  `source_id` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `target_id` varchar(255) NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` int DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-permission-created_by-user-id` (`created_by`),
  CONSTRAINT `fk-permission-created_by-user-id` FOREIGN KEY (`created_by`) REFERENCES `prime2_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=458 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_project`
--

DROP TABLE IF EXISTS `prime2_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_project` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `base_survey_eid` int DEFAULT NULL,
  `hidden` tinyint(1) DEFAULT '0',
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `status` int NOT NULL,
  `typemap` json NOT NULL,
  `overrides` json NOT NULL,
  `visibility` varchar(10) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'public',
  `country` char(3) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `manage_implies_create_hf` tinyint(1) NOT NULL DEFAULT '1',
  `i18n` json DEFAULT NULL,
  `languages` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `survey` (`base_survey_eid`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_response`
--

DROP TABLE IF EXISTS `prime2_response`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_response` (
  `id` int NOT NULL,
  `survey_id` int NOT NULL,
  `workspace_id` int NOT NULL,
  `data` json DEFAULT NULL,
  `date` date NOT NULL,
  `hf_id` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `last_updated` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`survey_id`),
  KEY `date` (`date`,`hf_id`),
  KEY `date_2` (`hf_id`,`date`) USING BTREE,
  KEY `workspace` (`workspace_id`),
  KEY `project` (`survey_id`),
  CONSTRAINT `project` FOREIGN KEY (`survey_id`) REFERENCES `prime2_project` (`base_survey_eid`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `workspace` FOREIGN KEY (`workspace_id`) REFERENCES `prime2_workspace` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_response_master`
--

DROP TABLE IF EXISTS `prime2_response_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_response_master` (
  `id` int NOT NULL AUTO_INCREMENT,
  `workspace_id` int DEFAULT NULL,
  `ls_response_id` int DEFAULT NULL,
  `submit_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `uoid` varchar(20) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `hf_type` varchar(40) DEFAULT NULL,
  `lga` varchar(200) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-response-master-hf-type` (`hf_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_session`
--

DROP TABLE IF EXISTS `prime2_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_session` (
  `id` char(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `expire` int NOT NULL,
  `data` blob,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_setting`
--

DROP TABLE IF EXISTS `prime2_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_setting` (
  `key` varchar(32) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_source_message`
--

DROP TABLE IF EXISTS `prime2_source_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_source_message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(32) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_token`
--

DROP TABLE IF EXISTS `prime2_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_token` (
  `user_id` int NOT NULL,
  `code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int NOT NULL,
  `type` smallint NOT NULL,
  UNIQUE KEY `prime2_token_unique` (`user_id`,`code`,`type`),
  CONSTRAINT `prime2_fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `prime2_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_user`
--

DROP TABLE IF EXISTS `prime2_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `last_login_at` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `language` varchar(10) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `newsletter_subscription` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prime2_user_unique_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prime2_workspace`
--

DROP TABLE IF EXISTS `prime2_workspace`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prime2_workspace` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `tool_id` int DEFAULT NULL,
  `closed` date DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(35) DEFAULT NULL,
  `last_sync_date` datetime DEFAULT NULL,
  `last_sync_by` varchar(255) DEFAULT NULL,
  `sync_status` varchar(255) DEFAULT NULL,
  `sync_error` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`tool_id`,`token`),
  CONSTRAINT `project_workspace` FOREIGN KEY (`tool_id`) REFERENCES `prime2_project` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=1715 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `id` int NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `base_survey_eid` int DEFAULT NULL,
  `hidden` tinyint(1) DEFAULT '0',
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `status` int NOT NULL,
  `typemap` json NOT NULL,
  `overrides` json NOT NULL,
  `visibility` varchar(10) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'public',
  `responseCount` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `comparision` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
