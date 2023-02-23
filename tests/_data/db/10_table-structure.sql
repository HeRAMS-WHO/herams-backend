SET FOREIGN_KEY_CHECKS=0;
CREATE TABLE `prime2_access_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `target_class` varchar(255) NOT NULL,
  `target_id` int NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `permissions` json DEFAULT NULL,
  `accepted` tinyint(1) DEFAULT NULL,
  `response` text,
  `responded_by` int DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `responded_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i-access_request-target_class-target_id` (`target_class`,`target_id`),
  KEY `fk-access_request-responded_by-user-id` (`responded_by`),
  KEY `fk-access_request-created_by-user-id` (`created_by`),
  CONSTRAINT `fk-access_request-responded_by-user-id` FOREIGN KEY (`responded_by`) REFERENCES `prime2_user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_audit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `subject_id` int NOT NULL,
  `event` varchar(30) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_configuration` (
  `key` varchar(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `value` json DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_facility` (
  `id` int NOT NULL AUTO_INCREMENT,
  `workspace_id` int NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `data` json DEFAULT NULL,
  `admin_data` json DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `can_receive_situation_update` tinyint(1) NOT NULL DEFAULT '1',
  `use_in_list` tinyint(1) NOT NULL DEFAULT '1',
  `use_in_dashboarding` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `deactivated_at` datetime DEFAULT NULL,
  `latest_date` date DEFAULT NULL,
  `tier` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workspace_id` (`workspace_id`),
  CONSTRAINT `workspace_id` FOREIGN KEY (`workspace_id`) REFERENCES `prime2_workspace` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_favorite` (
  `user_id` int NOT NULL,
  `target_class` varchar(255) NOT NULL,
  `target_id` int NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`user_id`,`target_class`,`target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_page` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `project_id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `sort` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_project` (`project_id`),
  KEY `page_page` (`parent_id`),
  CONSTRAINT `page_page` FOREIGN KEY (`parent_id`) REFERENCES `prime2_page` (`id`),
  CONSTRAINT `page_project` FOREIGN KEY (`project_id`) REFERENCES `prime2_project` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_permission` (
  `id` int NOT NULL AUTO_INCREMENT,
  `source` varchar(255) NOT NULL,
  `source_id` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `target_id` varchar(255) NOT NULL,
  `permission` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_project` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hidden` tinyint(1) DEFAULT '0',
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `visibility` varchar(10) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'public',
  `country` char(3) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `i18n` json DEFAULT NULL,
  `languages` json DEFAULT NULL,
  `admin_survey_id` int DEFAULT NULL,
  `data_survey_id` int DEFAULT NULL,
  `primary_language` varchar(10) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT 'en',
  `dashboard_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_admin_survey` (`admin_survey_id`),
  KEY `project_data_survey` (`data_survey_id`),
  CONSTRAINT `project_admin_survey` FOREIGN KEY (`admin_survey_id`) REFERENCES `prime2_survey` (`id`),
  CONSTRAINT `project_data_survey` FOREIGN KEY (`data_survey_id`) REFERENCES `prime2_survey` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

CREATE TABLE `prime2_session` (
  `id` char(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `expire` int NOT NULL,
  `data` blob,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_survey` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_survey_response` (
  `id` int NOT NULL AUTO_INCREMENT,
  `survey_id` int NOT NULL,
  `facility_id` int NOT NULL,
  `data` json NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-survey_response-survey_id-survey-id` (`survey_id`),
  KEY `fk-survey_response-facility_id-facility-id` (`facility_id`),
  CONSTRAINT `fk-survey_response-facility_id-facility-id` FOREIGN KEY (`facility_id`) REFERENCES `prime2_facility` (`id`),
  CONSTRAINT `fk-survey_response-survey_id-survey-id` FOREIGN KEY (`survey_id`) REFERENCES `prime2_survey` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prime2_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_login_at` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `language` varchar(10) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `newsletter_subscription` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prime2_user_unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `prime2_workspace` (
  `id` int NOT NULL AUTO_INCREMENT,
  `i18n` json DEFAULT NULL,
  `project_id` int DEFAULT NULL,
  `closed_at` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(35) DEFAULT NULL,
  `data` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`project_id`,`token`),
  CONSTRAINT `project_workspace` FOREIGN KEY (`project_id`) REFERENCES `prime2_project` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

SET FOREIGN_KEY_CHECKS=1;
