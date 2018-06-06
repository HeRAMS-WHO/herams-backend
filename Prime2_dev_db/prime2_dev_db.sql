-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Aug 23, 2017 at 07:17 AM
-- Server version: 5.7.19
-- PHP Version: 7.0.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `prime2_auth_assignment`
--

CREATE TABLE `prime2_auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prime2_auth_assignment`
--

INSERT INTO `prime2_auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', 1447169434),
('admin', '10121', 1474639249),
('admin', '2', 1458295455),
('admin', '3', 1447168260),
('admin', '4', 1457815703),
('createProject', '6', 1464863876);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_auth_item`
--

CREATE TABLE `prime2_auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prime2_auth_item`
--

INSERT INTO `prime2_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, 'Default admin role, users with this role can do anything.', NULL, NULL, 1447164140, 1466691311),
('createProject', 2, 'Allows the user to create a project for a specific tool.', 'prime\\rules\\CreateToolInstance', NULL, 1464863673, 1464863673),
('manager', 1, 'Role that allows a user to read all projects.', NULL, NULL, 1460104726, 1466502072),
('user', 1, 'Authenticated user', NULL, NULL, 1466502048, 1466502057);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_auth_item_child`
--

CREATE TABLE `prime2_auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prime2_auth_item_child`
--

INSERT INTO `prime2_auth_item_child` (`parent`, `child`) VALUES
('user', 'createProject'),
('admin', 'manager'),
('manager', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `prime2_auth_rule`
--

CREATE TABLE `prime2_auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prime2_auth_rule`
--

INSERT INTO `prime2_auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
('prime\\rules\\CreateToolInstance', 'O:30:\"prime\\rules\\CreateToolInstance\":3:{s:4:\"name\";s:30:\"prime\\rules\\CreateToolInstance\";s:9:\"createdAt\";i:1464863673;s:9:\"updatedAt\";i:1464863673;}', 1464863673, 1464863673);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_file`
--

CREATE TABLE `prime2_file` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mime_type` varchar(255) NOT NULL,
  `data` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_file`
--

INSERT INTO `prime2_file` (`id`, `name`, `mime_type`, `data`) VALUES
(1, NULL, 'text/html', 0x44554d4d59),
(2, NULL, 'text/html', 0x44554d4d59),
(3, NULL, 'text/html', 0x44554d4d59),
(13, NULL, 'text/html', 0x44554d4d59),
(14, NULL, 'text/html', 0x44554d4d59),
(15, NULL, 'text/html', 0x44554d4d59),
(16, NULL, 'text/html', 0x44554d4d59),
(17, NULL, 'text/html', 0x44554d4d59),
(18, NULL, 'text/html', 0x44554d4d59),
(19, NULL, 'text/html', 0x44554d4d59),
(20, NULL, 'text/html', 0x44554d4d59),
(21, NULL, 'text/html', 0x44554d4d59),
(22, NULL, 'text/html', 0x44554d4d59),
(23, NULL, 'text/html', 0x44554d4d59),
(24, NULL, 'text/html', 0x44554d4d59),
(25, NULL, 'text/html', 0x44554d4d59),
(26, NULL, 'text/html', 0x44554d4d59),
(27, NULL, 'text/html', 0x44554d4d59),
(28, NULL, 'text/html', 0x44554d4d59),
(29, NULL, 'text/html', 0x44554d4d59),
(30, NULL, 'text/html', 0x44554d4d59),
(31, NULL, 'text/html', 0x44554d4d59),
(32, NULL, 'text/html', 0x44554d4d59),
(33, NULL, 'text/html', 0x44554d4d59),
(34, NULL, 'text/html', 0x44554d4d59),
(35, NULL, 'text/html', 0x44554d4d59),
(36, NULL, 'text/html', 0x44554d4d59),
(37, NULL, 'text/html', 0x44554d4d59),
(38, NULL, 'text/html', 0x44554d4d59),
(39, NULL, 'text/html', 0x44554d4d59),
(40, NULL, 'text/html', 0x44554d4d59),
(41, NULL, 'text/html', 0x44554d4d59),
(42, NULL, 'text/html', 0x44554d4d59),
(43, NULL, 'text/html', 0x44554d4d59),
(44, NULL, 'text/html', 0x44554d4d59),
(45, NULL, 'text/html', 0x44554d4d59),
(46, NULL, 'text/html', 0x44554d4d59),
(47, NULL, 'text/html', 0x44554d4d59),
(48, NULL, 'text/html', 0x44554d4d59),
(49, NULL, 'text/html', 0x44554d4d59),
(50, NULL, 'text/html', 0x44554d4d59),
(51, NULL, 'text/html', 0x44554d4d59),
(52, NULL, 'text/html', 0x44554d4d59),
(53, NULL, 'text/html', 0x44554d4d59),
(54, NULL, 'text/html', 0x44554d4d59),
(55, NULL, 'text/html', 0x44554d4d59),
(56, NULL, 'text/html', 0x44554d4d59),
(57, NULL, 'text/html', 0x44554d4d59),
(58, NULL, 'text/html', 0x44554d4d59),
(59, NULL, 'text/html', 0x44554d4d59),
(60, NULL, 'text/html', 0x44554d4d59),
(61, NULL, 'text/html', 0x44554d4d59),
(62, NULL, 'text/html', 0x44554d4d59),
(63, NULL, 'text/html', 0x44554d4d59),
(64, NULL, 'text/html', 0x44554d4d59),
(65, NULL, 'text/html', 0x44554d4d59),
(66, NULL, 'text/html', 0x44554d4d59),
(67, NULL, 'text/html', 0x44554d4d59),
(68, NULL, 'text/html', 0x44554d4d59),
(69, NULL, 'text/html', 0x44554d4d59),
(70, NULL, 'text/html', 0x44554d4d59),
(71, NULL, 'text/html', 0x44554d4d59),
(72, NULL, 'text/html', 0x44554d4d59),
(73, NULL, 'text/html', 0x44554d4d59),
(74, NULL, 'text/html', 0x44554d4d59),
(75, NULL, 'text/html', 0x44554d4d59),
(76, NULL, 'text/html', 0x44554d4d59),
(77, NULL, 'text/html', 0x44554d4d59),
(78, NULL, 'text/html', 0x44554d4d59),
(79, NULL, 'text/html', 0x44554d4d59),
(80, NULL, 'text/html', 0x44554d4d59),
(81, NULL, 'text/html', 0x44554d4d59),
(82, NULL, 'text/html', 0x44554d4d59),
(83, NULL, 'text/html', 0x44554d4d59),
(84, NULL, 'text/html', 0x44554d4d59),
(85, NULL, 'text/html', 0x44554d4d59),
(86, NULL, 'text/html', 0x44554d4d59),
(87, NULL, 'text/html', 0x44554d4d59),
(88, NULL, 'text/html', 0x44554d4d59),
(89, NULL, 'text/html', 0x44554d4d59),
(90, NULL, 'text/html', 0x44554d4d59),
(91, NULL, 'text/html', 0x44554d4d59),
(92, NULL, 'text/html', 0x44554d4d59),
(93, NULL, 'text/html', 0x44554d4d59),
(94, NULL, 'text/html', 0x44554d4d59),
(95, NULL, 'text/html', 0x44554d4d59),
(96, NULL, 'text/html', 0x44554d4d59),
(97, NULL, 'text/html', 0x44554d4d59),
(98, NULL, 'text/html', 0x44554d4d59),
(99, NULL, 'text/html', 0x44554d4d59),
(100, 'OSCAR Ecuador 2016-05-12', 'text/html', 0x44554d4d59),
(101, 'OSCAR Ecuador 2016-05-25', 'text/html', 0x44554d4d59),
(102, 'OSCAR Ecuador 2016-05-25', 'text/html', 0x44554d4d59),
(103, 'OSCAR Ecuador 2016-05-26', 'text/html', 0x44554d4d59),
(104, 'CCPM Chad 2016-05-27', 'text/html', 0x44554d4d59),
(105, 'OSCAR South Sudan 2016-05-27', 'text/html', 0x44554d4d59),
(106, 'CD Colombia 2016-06-20', 'text/html', 0x44554d4d59),
(107, 'OSCAR Afghanistan 2016-08-09', 'text/html', 0x44554d4d59);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_migration`
--

CREATE TABLE `prime2_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prime2_migration`
--

INSERT INTO `prime2_migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1442910728),
('m140209_132017_init', 1442910730),
('m140403_174025_create_account_table', 1442910730),
('m140504_113157_update_tables', 1442910731),
('m140504_130429_create_token_table', 1442910732),
('m140506_102106_rbac_init', 1447163440),
('m140830_171933_fix_ip_field', 1442910732),
('m140830_172703_change_account_table_name', 1442910732),
('m141222_110026_update_ip_field', 1442910732),
('m141222_135246_alter_username_length', 1444205576),
('m150614_103145_update_social_account_table', 1444205576),
('m150623_212711_fix_username_notnull', 1444205576),
('m150921_103849_add_columns_to_user_drop_username', 1443616353),
('m150921_110227_create_settings_table', 1442910732),
('m150928_101102_setting_add_primary', 1444205577),
('m151005_081802_create_tools', 1445439387),
('m151005_134258_create_project', 1445439387),
('m151007_122334_add_permissions', 1445439387),
('m151020_084719_add_progress_type_to_tool', 1445439387),
('m151021_114420_create_userData', 1445439387),
('m151022_132419_create_report', 1445603714),
('m151026_090031_add_thumbnail_to_tool', 1446722346),
('m151026_112253_create_user_lists', 1446722346),
('m151026_152935_add_generators_to_tool', 1446722346),
('m151027_100100_add_default_generator_to_project', 1446722346),
('m151028_120616_create_response_table', 1446722346),
('m151104_081900_create_country', 1447065089),
('m151105_135351_add_latitude_longitude_to_project', 1447065089),
('m151105_145625_add_closed_to_project', 1447065089),
('m151112_092421_update_country', 1447336228),
('m151113_125634_add_title_to_report', 1448022155),
('m151120_121551_add_acronym_to_tool', 1448022155),
('m151120_122716_add_locality_name_and_created_to_project', 1448030957),
('m151120_144934_remove_project_countries', 1448033167),
('m151124_084028_create_translation_tables', 1449224647),
('m151126_112952_project_add_token', 1449224647),
('m151130_132254_update_report_data', 1449224647),
('m151130_134444_add_file', 1449224647),
('m151130_134703_update_report_to_use_file', 1449224648),
('m151218_234654_add_timezone_to_profile', 1460463519),
('m160114_120158_profile_defaults', 1452776926),
('m160114_121645_migrate_users_to_prime2', 1452776926),
('m160119_132345_set_dashboard_settings', 1453210049),
('m160208_111454_readd_username_column', 1454930229),
('m160223_125642_add_listed_to_tool', 1456244753),
('m160225_082821_add_fields_to_profile', 1456399101),
('m160225_142422_update_percentage_progress_to_CCPM_progress', 1456410493),
('m160308_122659_user_add_access_token', 1457440257),
('m160407_130403_batch_user_import', 1460104726),
('m160407_132410_add_manager_role', 1460104726),
('m160408_083010_batch_import_projects', 1460107608),
('m160408_092915_batch_publish_reports', 1460113690),
('m160408_114341_tool_progress_optional', 1460116236),
('m160412_121433_rename_published_reports', 1463645548),
('m160531_124602_tool_add_default_report', 1464699095),
('m161026_083647_tool_add_explorer_fields', 1477471080),
('m161116_132913_explorer_add_geo_and_services', 1479305630);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_permission`
--

CREATE TABLE `prime2_permission` (
  `id` int(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `source_id` int(11) NOT NULL,
  `target` varchar(255) NOT NULL,
  `target_id` int(11) NOT NULL,
  `permission` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_permission`
--

INSERT INTO `prime2_permission` (`id`, `source`, `source_id`, `target`, `target_id`, `permission`) VALUES
(1, 'prime\\models\\User', 2, 'prime\\models\\Project', 1, 'share'),
(2, 'prime\\models\\ar\\User', 2, 'prime\\models\\ar\\Project', 3, 'write'),
(3, 'prime\\models\\ar\\User', 3, 'prime\\models\\ar\\Project', 3, 'write'),
(4, 'prime\\models\\ar\\User', 3, 'prime\\models\\ar\\Project', 16, 'read'),
(5, 'prime\\models\\ar\\User', 4, 'prime\\models\\ar\\Project', 16, 'read'),
(6, 'prime\\models\\ar\\User', 2, 'prime\\models\\ar\\Project', 22, 'share'),
(7, 'prime\\models\\ar\\User', 5, 'prime\\models\\ar\\Project', 22, 'share'),
(8, 'prime\\models\\ar\\User', 6, 'prime\\models\\ar\\Project', 22, 'share'),
(10, 'prime\\models\\ar\\User', 4, 'prime\\models\\ar\\Project', 12, 'write'),
(25, 'prime\\models\\ar\\User', 3, 'prime\\models\\ar\\Project', 12, 'write'),
(26, 'prime\\models\\ar\\User', 2, 'prime\\models\\ar\\Project', 12, 'read'),
(28, 'prime\\models\\ar\\User', 5, 'prime\\models\\ar\\Project', 28, 'write'),
(29, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Project', 12, 'write'),
(30, 'prime\\models\\ar\\User', 3, 'prime\\models\\ar\\Project', 35, 'read'),
(31, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Project', 242, 'write'),
(32, 'prime\\models\\ar\\User', 10109, 'prime\\models\\ar\\Project', 330, 'admin'),
(34, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Project', 17, 'read'),
(35, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Project', 330, 'write'),
(38, 'prime\\models\\ar\\User', 2, 'prime\\models\\ar\\Tool', 6, 'instantiate'),
(39, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Project', 360, 'write'),
(40, 'prime\\models\\ar\\User', 5, 'prime\\models\\ar\\Project', 356, 'write'),
(42, 'prime\\models\\ar\\User', 10002, 'prime\\models\\ar\\Tool', 1, 'instantiate'),
(43, 'prime\\models\\ar\\User', 10012, 'prime\\models\\ar\\Tool', 1, 'instantiate'),
(44, 'prime\\models\\ar\\User', 4, 'prime\\models\\ar\\Tool', 1, 'instantiate'),
(45, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Tool', 1, 'instantiate'),
(46, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Tool', 2, 'instantiate'),
(47, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Project', 244, 'write'),
(48, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Project', 25, 'read'),
(49, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Project', 36, 'write'),
(50, 'prime\\models\\ar\\User', 10062, 'prime\\models\\forms\\projects\\CreateUpdate', 366, 'write'),
(51, 'prime\\models\\ar\\User', 10062, 'prime\\models\\forms\\projects\\CreateUpdate', 367, 'write'),
(52, 'prime\\models\\ar\\User', 10062, 'prime\\models\\forms\\projects\\CreateUpdate', 368, 'write'),
(53, 'prime\\models\\ar\\User', 10062, 'prime\\models\\forms\\projects\\CreateUpdate', 369, 'write'),
(54, 'prime\\models\\ar\\User', 10062, 'prime\\models\\forms\\projects\\CreateUpdate', 370, 'write'),
(55, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Project', 371, 'write'),
(56, 'prime\\models\\ar\\User', 10062, 'prime\\models\\ar\\Project', 372, 'admin'),
(57, 'prime\\models\\ar\\User', 10112, 'prime\\models\\ar\\Tool', 6, 'instantiate'),
(58, 'prime\\models\\ar\\User', 10113, 'prime\\models\\ar\\Tool', 6, 'instantiate'),
(59, 'prime\\models\\ar\\User', 10114, 'prime\\models\\ar\\Tool', 6, 'instantiate'),
(60, 'prime\\models\\ar\\User', 10115, 'prime\\models\\ar\\Tool', 6, 'instantiate'),
(61, 'prime\\models\\ar\\User', 10113, 'prime\\models\\ar\\Project', 373, 'admin'),
(62, 'prime\\models\\ar\\User', 10115, 'prime\\models\\ar\\Project', 374, 'admin'),
(63, 'prime\\models\\ar\\User', 10116, 'prime\\models\\ar\\Tool', 6, 'instantiate'),
(64, 'prime\\models\\ar\\User', 10118, 'prime\\models\\ar\\Tool', 6, 'instantiate'),
(65, 'prime\\models\\ar\\User', 10116, 'prime\\models\\ar\\Project', 375, 'admin'),
(66, 'prime\\models\\ar\\User', 10118, 'prime\\models\\ar\\Project', 376, 'admin'),
(67, 'prime\\models\\ar\\User', 10118, 'prime\\models\\ar\\Project', 377, 'admin'),
(68, 'prime\\models\\ar\\User', 10116, 'prime\\models\\ar\\Project', 376, 'write'),
(69, 'prime\\models\\ar\\User', 10118, 'prime\\models\\ar\\Project', 375, 'write'),
(70, 'prime\\models\\ar\\User', 10113, 'prime\\models\\ar\\Project', 376, 'write'),
(71, 'prime\\models\\ar\\User', 10114, 'prime\\models\\ar\\Project', 376, 'write'),
(72, 'prime\\models\\ar\\User', 10117, 'prime\\models\\ar\\Project', 376, 'share'),
(73, 'prime\\models\\ar\\User', 10117, 'prime\\models\\ar\\Tool', 6, 'instantiate'),
(74, 'prime\\models\\ar\\User', 10117, 'prime\\models\\ar\\Project', 378, 'admin'),
(75, 'prime\\models\\ar\\User', 10115, 'prime\\models\\ar\\Project', 379, 'admin'),
(76, 'prime\\models\\ar\\User', 10118, 'prime\\models\\ar\\Project', 332, 'read'),
(77, 'prime\\models\\ar\\User', 10124, 'prime\\models\\ar\\Project', 332, 'read'),
(78, 'prime\\models\\ar\\User', 10125, 'prime\\models\\ar\\Project', 332, 'read');

-- --------------------------------------------------------

--
-- Table structure for table `prime2_profile`
--

CREATE TABLE `prime2_profile` (
  `user_id` int(11) NOT NULL,
  `public_email` varchar(255) DEFAULT NULL,
  `gravatar_email` varchar(255) DEFAULT NULL,
  `gravatar_id` varchar(32) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `bio` text,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `organization` varchar(255) NOT NULL DEFAULT '',
  `office` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(255) NOT NULL DEFAULT '',
  `position` text,
  `phone` text,
  `phone_alternative` text,
  `other_contact` text,
  `timezone` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_profile`
--

INSERT INTO `prime2_profile` (`user_id`, `public_email`, `gravatar_email`, `gravatar_id`, `location`, `website`, `bio`, `first_name`, `last_name`, `organization`, `office`, `country`, `position`, `phone`, `phone_alternative`, `other_contact`, `timezone`) VALUES
(1, NULL, 'petragallos@who.int', 'e01222490e313d755d7886d6f80b9bdc', NULL, NULL, NULL, 'Samuel', 'Petragallo', 'WHO', 'Headquarters', 'CHE', NULL, NULL, NULL, NULL, NULL),
(2, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(3, NULL, NULL, NULL, NULL, NULL, NULL, 'Joey', 'Claessen', 'test', 'test', 'NLD', NULL, NULL, NULL, NULL, NULL),
(4, NULL, NULL, NULL, NULL, NULL, NULL, 'Dirk', 'S', 'X', 'Y', 'ALB', NULL, NULL, NULL, NULL, NULL),
(5, NULL, NULL, NULL, NULL, NULL, NULL, 'Jonathan', 'Polonsky', 'WHO', 'Geneva', 'CHE', NULL, NULL, NULL, NULL, NULL),
(6, NULL, NULL, NULL, NULL, NULL, NULL, 'Xavier', 'de Radiguès', 'WHO', 'M419', 'FRA', NULL, NULL, NULL, NULL, NULL),
(10002, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10004, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10006, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10007, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10009, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10010, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10012, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10013, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10015, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10016, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10017, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10018, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10019, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10020, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10021, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10022, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10023, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10024, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10025, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10029, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10030, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10032, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10033, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10034, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10036, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10037, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10039, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10040, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10041, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10042, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10043, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10044, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10045, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10046, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10047, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10049, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10050, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10051, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10053, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10054, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10055, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10058, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10059, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10062, NULL, NULL, NULL, NULL, NULL, NULL, 'Samuel', 'Petragallo', 'WHO /Emergency Risk Management', '', 'CHE', '', '+41795006506', '+41795006506', '', NULL),
(10066, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10067, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10068, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10069, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10070, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10071, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10072, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10073, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10074, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10075, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10076, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10077, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10078, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10079, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10080, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10081, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10082, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10083, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10084, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10085, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10086, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10087, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10088, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10089, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10090, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10091, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10092, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10093, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10094, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10095, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10096, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10097, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10098, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10099, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10100, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10101, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10102, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10103, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10104, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10105, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10107, NULL, NULL, NULL, NULL, NULL, NULL, 'Samuel', 'Petragallo', 'WHO /Emergency Risk Management', '', 'CHE', '', '+41795006506', '+41795006506', '', NULL),
(10108, NULL, NULL, NULL, NULL, NULL, NULL, 'robert', 'colombo', 'PAHO', 'washintyon', 'USA', 'IMO', '+12027902711', '+12029743215', 'robert_vram', NULL),
(10109, NULL, NULL, NULL, NULL, NULL, NULL, 'Sowmya', 'Adibhatla', 'Pan American Health Organization', 'Washington D.C', 'USA', 'Information Officer/ Emergency support officer', '', '', '', NULL),
(10110, NULL, NULL, NULL, NULL, NULL, NULL, 'EHA', 'WCO BAN', 'WHO', 'Dhaka', 'BGD', 'M&E Officer', '27243', '+8801755532547', 'herrlohse', NULL),
(10111, NULL, NULL, NULL, NULL, NULL, NULL, 'Kevin', 'Crampton', 'WHO', 'Geneva', 'CHE', 'Business Analyst', '0033679385169', '', 'kevincrampton', NULL),
(10112, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10113, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10114, NULL, NULL, NULL, NULL, NULL, NULL, 'Jessica', 'Dell', 'iMMAP', 'Washington', 'USA', 'IMO', '8186342247', '8186342247', '', NULL),
(10115, NULL, NULL, NULL, NULL, NULL, NULL, 'Jessica', 'Dell', 'iMMAP', 'Washington', 'USA', 'IMO', '8186342247', '8186342247', '', NULL),
(10116, NULL, NULL, NULL, NULL, NULL, NULL, 'Seth Annuh', 'Tetteh', 'Malteser-international', 'Addis', 'ETH', 'Information Management Officer', '', '', 'annuh.mint', NULL),
(10117, NULL, NULL, NULL, NULL, NULL, NULL, 'Patrick', 'Fitzgerald', 'iMMAP', 'Kabul', 'AFG', 'IMO', '+93780763601', '', 'fitz.paddy', NULL),
(10118, NULL, NULL, NULL, NULL, NULL, NULL, 'John', 'Kipterer', 'WVI', 'Nairobi', 'KEN', 'IMO', '+254780551180', '', 'john.kapoi', NULL),
(10119, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10120, NULL, NULL, NULL, NULL, NULL, NULL, 'Senait', 'Tekeste', 'WHO', 'Congo', 'REG_A', 'SHOC', '', '', '', NULL),
(10121, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10122, NULL, NULL, NULL, NULL, NULL, NULL, 'test', 'test', 'test', '', 'REG_A', '', '', '', '', NULL),
(10123, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL),
(10124, NULL, NULL, NULL, NULL, NULL, NULL, 'Malick', 'GAI', 'WHO', 'Juba', 'SSD', 'IMO', '+211955671668', '', 'malick.gai', NULL),
(10125, NULL, NULL, NULL, NULL, NULL, NULL, 'Mohamed', 'Elamein', 'WHO', '', 'SYR', '', '', '', 'mohamed.elamein2', NULL),
(10126, NULL, NULL, NULL, NULL, NULL, NULL, 'dawran', 'safi', 'WHO', 'Kabul', 'AFG', 'IMO', '0093782220832', '', '', NULL),
(10127, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_project`
--

CREATE TABLE `prime2_project` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `data_survey_eid` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `tool_id` int(11) DEFAULT NULL,
  `default_generator` varchar(255) DEFAULT NULL,
  `latitude` decimal(12,8) DEFAULT NULL,
  `longitude` decimal(12,8) DEFAULT NULL,
  `closed` date DEFAULT NULL,
  `locality_name` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country_iso_3` varchar(3) NOT NULL,
  `token` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_project`
--

INSERT INTO `prime2_project` (`id`, `title`, `description`, `data_survey_eid`, `owner_id`, `tool_id`, `default_generator`, `latitude`, `longitude`, `closed`, `locality_name`, `created`, `country_iso_3`, `token`) VALUES
(1, 'Test Project', '<p>Adapted <strong>descritpion test</strong></p>\r\n', 1, 2, 1, NULL, 40.89067715, 17.27600098, NULL, NULL, '2015-11-20 15:49:17', 'NLD', 'VpPmk6-NCa1sNAakf2Z7YjpSmq-WGEkpZwH'),
(5, 'CCPM Test Geo', '<p>CCPM</p>\r\n', 67825, 1, 1, NULL, 30.35391637, 79.07958984, '2016-02-03', NULL, '2015-11-20 15:49:17', 'NLD', 'TNmbz-ywLdbt87XGudoDpy-InI86hZco0bk'),
(17, 'OSCAR Test', '<p>OSCAR Test</p>\r\n', 338754, 10062, 6, 'oscar', NULL, NULL, '2016-05-27', NULL, '2016-01-12 14:54:10', 'SSD', 'e4kr3wktfr68um9'),
(20, 'OSCAR Report', '<p>OSCAR Report</p>\r\n', 338754, 1, 6, 'oscar', NULL, NULL, NULL, NULL, '2016-01-22 12:33:08', 'AGO', 'VAfD6oilgLeIP5fyIiGhZion7L_V-ZYpDdc'),
(22, 'OSCAR', '<p>OSC</p>\r\n', 338754, 1, 6, 'oscar', NULL, NULL, NULL, NULL, '2016-02-03 13:27:21', 'AFG', 'b2ush39jmuvw92p'),
(23, 'HCM Test', '<p>HCM Test</p>\r\n', 259688, 10060, 7, 'hc', NULL, NULL, NULL, NULL, '2016-02-23 10:16:29', 'CHE', '9dxt3judhahsc5c'),
(24, 'test', '<p>test</p>\r\n', 525573, 1, 1, NULL, NULL, NULL, NULL, NULL, '2016-03-02 15:49:35', 'AND', 'test'),
(25, 'No Attri', '<p>No Attri</p>\r\n', 211726, 1, 8, NULL, NULL, NULL, NULL, NULL, '2016-03-08 08:48:59', 'DZA', 's16xSoW9I2m5sOd'),
(27, 'GM-CLE Test', '<p>GM - CLE Test</p>\r\n', 473297, 1, 9, 'empty', NULL, NULL, '2016-03-14', NULL, '2016-03-08 13:41:08', 'ALB', 'gy2pmu5yp762qr2'),
(28, 'No Att', '<p>No Att</p>\r\n', 211726, 1, 8, 'empty', NULL, NULL, NULL, NULL, '2016-03-08 13:49:17', 'DZA', 'Ev3QQkFx8hEiZHh4CTtGxc_OA8kJCIgh4v8'),
(36, 'Default DASH TEST - P-91', '<p>Default DASH TEST - P-91</p>\r\n', 473297, 1, 9, NULL, NULL, NULL, NULL, NULL, '2016-03-15 16:13:59', 'AFG', 'saiuh58gh46a-687fasFG'),
(37, 'OSCAR Rep Test', '<p>OSCAR Rep Test</p>\r\n', 338754, 1, 6, 'oscar', NULL, NULL, NULL, NULL, '2016-03-17 09:58:17', 'AIA', 'uqyrdtk7y8wnrfi'),
(241, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10080, 3, 'ccpm', NULL, NULL, NULL, 'Western Equatoria', '2013-03-15 00:00:00', 'SSD', '2tcdkvsgnicatzh'),
(242, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10100, 3, 'ccpm', NULL, NULL, NULL, NULL, '2014-03-24 00:00:00', 'SDN', '3zhvuud5f88hkui'),
(243, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10077, 3, 'ccpm', NULL, NULL, NULL, NULL, '2014-07-09 00:00:00', 'CAF', '4ahdtn6q2qen4j9'),
(244, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10078, 3, 'ccpm', NULL, NULL, NULL, 'Western Darfur', '2015-08-18 00:00:00', 'SDN', '54s6ggjfutusg5d'),
(245, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10075, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-08-31 00:00:00', 'TCD', '6gnz8bi65w4ne3v'),
(246, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10079, 3, 'ccpm', NULL, NULL, NULL, 'Kharkiv', '2015-06-11 00:00:00', 'UKR', '6stx2d5q2m9ve7k'),
(247, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10097, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-07-01 00:00:00', 'COL', '6xz8cjtyucan8dq'),
(248, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10086, 3, 'ccpm', NULL, NULL, NULL, 'Bor', '2014-09-01 00:00:00', 'SSD', '758ev9a88knykgm'),
(249, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10099, 3, 'ccpm', NULL, NULL, NULL, 'Northern Darfur', '2015-08-18 00:00:00', 'SDN', '7jebryiyv4ibdb2'),
(250, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10103, 3, 'ccpm', NULL, NULL, NULL, 'North Kivu', '2013-11-22 00:00:00', 'COD', '8rrnk7janvucpmg'),
(251, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10072, 3, 'ccpm', NULL, NULL, NULL, 'Donetsk', '2015-06-11 00:00:00', 'UKR', '8suvzhp8qfjpg9d'),
(252, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10088, 3, 'ccpm', NULL, NULL, NULL, NULL, '2013-11-14 00:00:00', 'COD', '9j9rf2rqkg2ygrd'),
(253, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10070, 3, 'ccpm', NULL, NULL, NULL, NULL, '2013-09-16 00:00:00', 'YEM', 'a4mgicf8nm8uc3c'),
(254, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10092, 3, 'ccpm', NULL, NULL, NULL, 'Rakhine', '2015-07-01 00:00:00', 'MMR', 'a85b5h5gbav6egb'),
(255, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10083, 3, 'ccpm', NULL, NULL, NULL, NULL, '2014-04-17 00:00:00', 'PAK', 'ar9vi9trq5awncs'),
(256, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10102, 3, 'ccpm', NULL, NULL, NULL, NULL, '2014-09-01 00:00:00', 'SSD', 'bfwkn2w6n5dzznz'),
(257, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10092, 3, 'ccpm', NULL, NULL, NULL, 'Kachin', '2015-07-01 00:00:00', 'MMR', 'bz5c785gbav4h5t'),
(258, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10095, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-05-26 00:00:00', 'LBR', 'c69h9hh9x5khp8v'),
(259, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10071, 3, 'ccpm', NULL, NULL, NULL, NULL, '2014-08-20 00:00:00', 'COL', 'dkcra23cz8bbzwn'),
(260, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10104, 3, 'ccpm', NULL, NULL, NULL, NULL, '2013-03-08 00:00:00', 'PSE', 'dnfcvtazdwcuqu7'),
(261, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10089, 3, 'ccpm', NULL, NULL, NULL, NULL, '2012-02-12 00:00:00', 'SOM', 'e3zy5fnqw88bqnm'),
(262, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10081, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-10-20 00:00:00', 'SYR', 'e5pmi9qjuv2mhwf'),
(263, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10076, 3, 'ccpm', NULL, NULL, NULL, 'Eastern Equatoria', '2013-03-15 00:00:00', 'SSD', 'ejtfhtgwzu9saek'),
(264, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10101, 3, 'ccpm', NULL, NULL, NULL, 'Gaziantep', '2015-08-10 00:00:00', 'SYR', 'eu8mw34xi48u4gs'),
(265, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10096, 3, 'ccpm', NULL, NULL, NULL, NULL, '2013-07-30 00:00:00', 'MLI', 'gfwzhcmfkeuzz5v'),
(266, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10085, 3, 'ccpm', NULL, NULL, NULL, 'Central Equatoria', '2013-03-15 00:00:00', 'SSD', 'hqe36hz4rwjsuky'),
(267, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10090, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-07-06 00:00:00', 'SOM', 'hrhxxn9gapi7pdz'),
(268, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10087, 3, 'ccpm', NULL, NULL, NULL, 'Northern Bahr el Ghazal', '2013-03-15 00:00:00', 'SSD', 'jd5t438bzq9usyh'),
(269, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10073, 3, 'ccpm', NULL, NULL, NULL, 'South Kivu', '2013-12-08 00:00:00', 'COD', 'kp6g35wnj6zah8i'),
(270, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10100, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-08-18 00:00:00', 'SDN', 'p588nuttgzr2p7b'),
(271, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10069, 3, 'ccpm', NULL, NULL, NULL, NULL, '2013-09-13 00:00:00', 'HTI', 'pijaayxh72vd55c'),
(272, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10067, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-02-10 00:00:00', 'IRQ', 'qf324z4didmcei2'),
(273, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10067, 3, 'ccpm', NULL, NULL, NULL, 'South Central Zone', '2012-02-12 00:00:00', 'SOM', 'sjf79c4h98vimf6'),
(274, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10074, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-03-12 00:00:00', 'MLI', 'upxr3866fn3tws4'),
(275, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10084, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-06-11 00:00:00', 'UKR', 'uratyf5bzn9g3t7'),
(276, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10105, 3, 'ccpm', NULL, NULL, NULL, 'Severodonetsk', '2015-06-11 00:00:00', 'UKR', 'utjasqfheejwyvd'),
(277, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10093, 3, 'ccpm', NULL, NULL, NULL, 'Luansk', '2015-06-11 00:00:00', 'UKR', 'v283xvfctetqu5u'),
(278, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10098, 3, 'ccpm', NULL, NULL, NULL, NULL, '2014-06-17 00:00:00', 'AFG', 'wq7eb8d6ytqzik2'),
(279, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10066, 3, 'ccpm', NULL, NULL, NULL, NULL, '2013-07-03 00:00:00', 'AFG', 'wr4eeb8igccudfv'),
(280, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10092, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-07-01 00:00:00', 'MMR', 'yusw973r2uyatks'),
(281, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10082, 3, 'ccpm', NULL, NULL, NULL, 'Southern Darfur', '2015-08-18 00:00:00', 'SDN', 'zg9adebb7dmpddc'),
(282, 'Cluster Coordination Performance Monitoring', 'Cluster Coordination Performance Monitoring', 22814, 10067, 3, 'ccpm', NULL, NULL, NULL, NULL, '2015-04-07 00:00:00', 'AFG', 'zukynxpbu2jj8yv'),
(283, 'Cluster Description', 'Cluster Description', 37964, 10079, 2, 'cd', NULL, NULL, NULL, 'Kharkiv', '2015-06-11 00:00:00', 'UKR', '031jg83hsnb9whx'),
(284, 'Cluster Description', 'Cluster Description', 37964, 10098, 2, 'cd', NULL, NULL, NULL, NULL, '2014-06-17 00:00:00', 'AFG', '2k7t2xx2pr7esxt'),
(285, 'Cluster Description', 'Cluster Description', 37964, 10072, 2, 'cd', NULL, NULL, NULL, 'Donetsk', '2015-06-11 00:00:00', 'UKR', '34h5h345h6ou86lrq'),
(286, 'Cluster Description', 'Cluster Description', 37964, 10080, 2, 'cd', NULL, NULL, NULL, 'Western Equatoria', '2013-03-15 00:00:00', 'SSD', '398gku6bv5tum87'),
(287, 'Cluster Description', 'Cluster Description', 37964, 10094, 2, 'cd', NULL, NULL, NULL, 'Unity', '2013-03-15 00:00:00', 'SSD', '3ntrbdebryixefz'),
(288, 'Cluster Description', 'Cluster Description', 37964, 10076, 2, 'cd', NULL, NULL, NULL, 'Eastern Equatoria', '2013-03-15 00:00:00', 'SSD', '4wfayduw7txrvsg'),
(289, 'Cluster Description', 'Cluster Description', 37964, 10103, 2, 'cd', NULL, NULL, NULL, 'North Kivu', '2013-11-22 00:00:00', 'COD', '5uw69iv7d6s924d'),
(290, 'Cluster Description', 'Cluster Description', 37964, 10105, 2, 'cd', NULL, NULL, NULL, 'Severodonetsk', '2015-06-11 00:00:00', 'UKR', '642d3sd65zgheuh32'),
(291, 'Cluster Description', 'Cluster Description', 37964, 10097, 2, 'cd', NULL, NULL, NULL, NULL, '2015-07-01 00:00:00', 'COL', '76859nfkskbh4g'),
(292, 'Cluster Description', 'Cluster Description', 37964, 10071, 2, 'cd', NULL, NULL, NULL, NULL, '2014-08-20 00:00:00', 'COL', '7enppefanedw4rb'),
(293, 'Cluster Description', 'Cluster Description', 37964, 10092, 2, 'cd', NULL, NULL, NULL, NULL, '2015-07-01 00:00:00', 'MMR', '85fkj347hknxbna3'),
(294, 'Cluster Description', 'Cluster Description', 37964, 10091, 2, 'cd', NULL, NULL, NULL, 'Lakes', '2013-03-15 00:00:00', 'SSD', '8bqnkn2ej88gx38'),
(295, 'Cluster Description', 'Cluster Description', 37964, 10096, 2, 'cd', NULL, NULL, NULL, NULL, '2013-07-30 00:00:00', 'MLI', '8niba42gt9fmseu'),
(296, 'Cluster Description', 'Cluster Description', 37964, 10092, 2, 'cd', NULL, NULL, NULL, 'Kachin', '2015-07-01 00:00:00', 'MMR', 'ads789mf3rslv0'),
(297, 'Cluster Description', 'Cluster Description', 37964, 10073, 2, 'cd', NULL, NULL, NULL, 'South Kivu', '2013-12-08 00:00:00', 'COD', 'btxkq4edjtzrjsy'),
(298, 'Cluster Description', 'Cluster Description', 37964, 10066, 2, 'cd', NULL, NULL, NULL, NULL, '2013-07-03 00:00:00', 'AFG', 'dbzgjtevu5r7gdx'),
(299, 'Cluster Description', 'Cluster Description', 37964, 10086, 2, 'cd', NULL, NULL, NULL, 'Jonglei', '2013-03-15 00:00:00', 'SSD', 'dk8k9tdbf9yhv9t'),
(300, 'Cluster Description', 'Cluster Description', 37964, 10087, 2, 'cd', NULL, NULL, NULL, 'Northern Bahr el Ghazal', '2013-03-15 00:00:00', 'SSD', 'eftk22ddctkbafi'),
(301, 'Cluster Description', 'Cluster Description', 37964, 10088, 2, 'cd', NULL, NULL, NULL, NULL, '2013-11-14 00:00:00', 'COD', 'em346urmkdfqqb4'),
(302, 'Cluster Description', 'Cluster Description', 37964, 10067, 2, 'cd', NULL, NULL, NULL, NULL, '2015-02-10 00:00:00', 'IRQ', 'f9zuaxtnfecjkaf'),
(303, 'Cluster Description', 'Cluster Description', 37964, 10075, 2, 'cd', NULL, NULL, NULL, NULL, '2015-08-31 00:00:00', 'TCD', 'fdskz463hdfka92'),
(304, 'Cluster Description', 'Cluster Description', 37964, 10100, 2, 'cd', NULL, NULL, NULL, NULL, '2014-03-24 00:00:00', 'SDN', 'fvt2rvcacr9zmt5'),
(305, 'Cluster Description', 'Cluster Description', 37964, 10082, 2, 'cd', NULL, NULL, NULL, 'Southern Darfur', '2015-08-18 00:00:00', 'SDN', 'fwdoiuhpiuhv239ksd'),
(306, 'Cluster Description', 'Cluster Description', 37964, 10086, 2, 'cd', NULL, NULL, NULL, 'Bor', '2014-09-01 00:00:00', 'SSD', 'ge4igr4z7fjvcz2'),
(307, 'Cluster Description', 'Cluster Description', 37964, 10067, 2, 'cd', NULL, NULL, NULL, NULL, '2015-04-07 00:00:00', 'AFG', 'gra7c3becevjsdq'),
(308, 'Cluster Description', 'Cluster Description', 37964, 10070, 2, 'cd', NULL, NULL, NULL, NULL, '2013-09-16 00:00:00', 'YEM', 'guwjux66c7c3ip6'),
(309, 'Cluster Description', 'Cluster Description', 37964, 10104, 2, 'cd', NULL, NULL, NULL, NULL, '2013-03-08 00:00:00', 'PSE', 'hb4j8bvjkd6mfuq'),
(310, 'Cluster Description', 'Cluster Description', 37964, 10101, 2, 'cd', NULL, NULL, NULL, 'Gaziantep', '2015-08-10 00:00:00', 'SYR', 'hfdsahjlljdL6644201'),
(311, 'Cluster Description', 'Cluster Description', 37964, 10092, 2, 'cd', NULL, NULL, NULL, 'Rakhine', '2015-07-01 00:00:00', 'MMR', 'hhhvd744n899clj'),
(312, 'Cluster Description', 'Cluster Description', 37964, 10093, 2, 'cd', NULL, NULL, NULL, 'Luansk', '2015-06-11 00:00:00', 'UKR', 'hif8734hfe7zfhqp'),
(313, 'Cluster Description', 'Cluster Description', 37964, 10083, 2, 'cd', NULL, NULL, NULL, NULL, '2014-04-17 00:00:00', 'PAK', 'k2zbs9998dpb7iz'),
(314, 'Cluster Description', 'Cluster Description', 37964, 10090, 2, 'cd', NULL, NULL, NULL, NULL, '2015-07-06 00:00:00', 'SOM', 'ldfsnlvsfdasvoh6691'),
(315, 'Cluster Description', 'Cluster Description', 37964, 10067, 2, 'cd', NULL, NULL, NULL, 'South Central Zone', '2012-12-02 00:00:00', 'SOM', 'm6nf6p3pkwdt2qc'),
(316, 'Cluster Description', 'Cluster Description', 37964, 10084, 2, 'cd', NULL, NULL, NULL, NULL, '2015-06-11 00:00:00', 'UKR', 'op9806ipozuzt'),
(317, 'Cluster Description', 'Cluster Description', 37964, 10099, 2, 'cd', NULL, NULL, NULL, 'Northern Darfur', '2015-08-18 00:00:00', 'SDN', 'rqb325gfd7888ffqwd'),
(318, 'Cluster Description', 'Cluster Description', 37964, 10078, 2, 'cd', NULL, NULL, NULL, 'Western Darfur', '2015-08-18 00:00:00', 'SDN', 'sfzdgvshjkl55443jw'),
(319, 'Cluster Description', 'Cluster Description', 37964, 10077, 2, 'cd', NULL, NULL, NULL, NULL, '2014-07-09 00:00:00', 'CAF', 't4dechn5wd9paun'),
(320, 'Cluster Description', 'Cluster Description', 37964, 10081, 2, 'cd', NULL, NULL, NULL, NULL, '2015-10-20 00:00:00', 'SYR', 'te0mb7jqvzwjfvsacdd'),
(321, 'Cluster Description', 'Cluster Description', 37964, 10100, 2, 'cd', NULL, NULL, NULL, NULL, '2015-08-18 00:00:00', 'SDN', 'uioz5467zzg4gh471'),
(322, 'Cluster Description', 'Cluster Description', 37964, 10085, 2, 'cd', NULL, NULL, '2016-06-02', 'Central Equatoria', '2013-03-15 00:00:00', 'SSD', 'ur8nks4mwn4nacd'),
(323, 'Cluster Description', 'Cluster Description', 37964, 10089, 2, 'cd', NULL, NULL, NULL, NULL, '2012-12-02 00:00:00', 'SOM', 'wmman886a9ci5sb'),
(324, 'Cluster Description', 'Cluster Description', 37964, 10069, 2, 'cd', NULL, NULL, NULL, NULL, '2013-09-13 00:00:00', 'HTI', 'x86re6htv5a3eai'),
(325, 'Cluster Description', 'Cluster Description', 37964, 10102, 2, 'cd', NULL, NULL, NULL, NULL, '2014-09-01 00:00:00', 'SSD', 'xmje69pmf2vta92'),
(326, 'Cluster Description', 'Cluster Description', 37964, 10095, 2, 'cd', NULL, NULL, NULL, NULL, '2015-05-26 00:00:00', 'LBR', 'yahr392j5dg35sc'),
(327, 'Cluster Description', 'Cluster Description', 37964, 10074, 2, 'cd', NULL, NULL, NULL, NULL, '2015-03-12 00:00:00', 'MLI', 'znugk4erszxikjj'),
(328, 'Test ITALY', '<p>Test ITALY</p>\r\n', 37964, 1, 2, 'cd', NULL, NULL, NULL, NULL, '2016-04-18 22:57:53', 'ITA', 'EEGWPZrRxHcqCnmZtwfZkNPhxvdOTT2lVlc'),
(329, 'Grade Monitoring - Country Status', '<p>Grade Monitoring - Country Status</p>\r\n', 211726, 1, 8, NULL, NULL, NULL, NULL, NULL, '2016-05-06 16:07:08', 'MAR', 'BNV8bbA4uC9GZfC'),
(330, 'OSCAR PAHO (Test / PAHO WDC)', '<p>OSCAR PAHO (Test / Robert Colombo) ane EOC members online reporting</p>\r\n', 338754, 10108, 6, 'oscar', 19.64258753, -85.78125000, NULL, NULL, '2016-05-12 14:51:29', 'ECU', '0D8Z2WcHS4KQMZqw-msb5AWZtH6xAIkSbRV'),
(331, 'OSCAR BAN Test', '<p>OSCAR BAN Test</p>\r\n', 338754, 10110, 6, 'oscar', NULL, NULL, NULL, NULL, '2016-05-30 09:38:44', 'BGD', 'mDnBO9Vtxayaij7Dv-xZGtInLwS39M5J6iU'),
(332, 'HeRAMS RCA - Test', '<p>HeRAMS RCA - Test</p>\r\n', 695195, 1, 10, 'empty', 0.00000000, 0.00000000, NULL, NULL, '2016-05-30 16:50:35', 'CAF', '3gvq6rndbpkjmds'),
(333, 'Test Project Display on Map', '<p>Test Project Display on Map</p>\r\n', 37964, 1, 2, 'cd', NULL, NULL, NULL, NULL, '2016-05-31 09:44:00', 'ZAF', 'MbrYIbPDflQVv0JxFEZZ9NLcz6jBIS1i5GT'),
(334, 'Test Project Creation', '<p>Test Project Creation</p>\r\n', 37964, 1, 2, 'cd', NULL, NULL, NULL, NULL, '2016-05-31 12:00:19', 'AND', 'Wu_BgRlZjc_dmT9_07zBax7RGnaGvO3yecX'),
(335, 'test', '<p>test</p>\r\n', 525573, 1, 1, NULL, 13.92340390, 1.40625000, NULL, NULL, '2016-06-01 16:38:17', 'ALB', 'pPMTwR18oC_cmL4UP9jKX-ShAjvrYZ_-wMS'),
(336, 'test', '<p>test</p>\r\n', 525573, 1, 1, NULL, 13.92340390, 1.40625000, NULL, NULL, '2016-06-01 16:39:26', 'ALB', 'TxEWU2wKeSzggvQfFGioYPPCSPr7HC0DUX-'),
(337, 'test', '<p>test</p>\r\n', 525573, 1, 1, NULL, 13.92340390, 1.40625000, NULL, NULL, '2016-06-01 16:39:31', 'ALB', '8xA6jhLfFbwDwQV177uF6ZdXpO79btTMyux'),
(338, 'test', '<p>test</p>\r\n', 525573, 1, 1, NULL, 13.92340390, 1.40625000, NULL, NULL, '2016-06-01 16:39:37', 'ALB', 'PGbrf5ASVZZQw6nEK4BKb6DzeS8Kd2YNwO3'),
(339, 'test', '<p>test</p>\r\n', 525573, 1, 1, NULL, 13.92340390, 1.40625000, NULL, NULL, '2016-06-01 16:39:44', 'ALB', 'AoPMszUW94w3kfGSq2eQl48MMGR23tZy47X'),
(340, 'test', '<p>test</p>\r\n', 525573, 1, 1, NULL, 13.92340390, 1.40625000, NULL, NULL, '2016-06-01 16:40:06', 'ALB', 'AEkESFNePwBclRoSTvQ8vRvJzonUAlgkIa6'),
(341, 'test', '<p>test</p>\r\n', 525573, 1, 1, NULL, 13.92340390, 1.40625000, NULL, NULL, '2016-06-01 16:41:04', 'ALB', 'D4r-Bunjjst8YephyR5eNVugo5h1CtrB_1z'),
(342, 'test', '<p>test</p>\r\n', 525573, 1, 1, NULL, 13.92340390, 1.40625000, NULL, NULL, '2016-06-01 16:41:53', 'ALB', 'C-wM2lK733vYqwUaPzh74xWgdJcM3R8FxNc'),
(343, 'test', '<p>;</p>\r\n', 22814, 1, 3, NULL, NULL, NULL, NULL, NULL, '2016-06-01 17:16:56', 'AGO', ''),
(345, 'test', '<p>;</p>\r\n', 22814, 1, 3, NULL, NULL, NULL, NULL, NULL, '2016-06-01 17:19:09', 'AGO', '7QB35kkVDkVVh71'),
(346, 'test123', '<p>tatae</p>\r\n', 473297, 1, 9, NULL, NULL, NULL, NULL, NULL, '2016-06-01 17:19:55', 'AGO', 'odF9rAxIfwRFLTH'),
(347, 'test123', '<p>tatae</p>\r\n', 473297, 1, 9, NULL, NULL, NULL, NULL, NULL, '2016-06-01 17:21:20', 'AGO', 'v4YK~JDwTfpaliV'),
(348, 'test', '<p>awffafawf</p>\r\n', 695195, 1, 10, NULL, NULL, NULL, NULL, NULL, '2016-06-01 17:24:43', 'DZA', 'fujxuWKaZ5bSnix'),
(349, 'Test Bug on project creation', '<p>Test Bug on project creation</p>\r\n', 37964, 1, 2, NULL, NULL, NULL, NULL, NULL, '2016-06-07 13:06:03', 'ALB', 'grmIi5Kcvrz7HY_'),
(350, 'test', '<p>test</p>\r\n', 525573, 1, 1, 'ccpm', NULL, NULL, NULL, NULL, '2016-06-07 15:38:16', 'DZA', 'wjqTlTueurHznK8'),
(351, 'Whole of Syria - Health Cluster Bulletin', '<p>Whole of Syria - Health Cluster Bulletin</p>\r\n', 338754, 1, 6, 'oscar', NULL, NULL, '2016-06-09', NULL, '2016-06-09 16:55:21', 'SYR', '9r7kUxWZCxvL7J5'),
(352, 'Whole of Syria - Health Cluster Bulletin', '<p>Whole of Syria - Health Cluster Bulletin</p>\r\n', 338754, 1, 6, NULL, NULL, NULL, NULL, NULL, '2016-06-09 16:59:00', 'SYR', 'fdasTYiag365dg6bd'),
(353, 'abc', '<p>abc</p>\r\n', 338754, 1, 6, 'oscar', 9.10209674, 1.05468750, NULL, NULL, '2016-06-13 11:38:28', 'AGO', 'pxwCChI~upjy65b'),
(354, 'abc2', '<p>abc2</p>\r\n', 338754, 1, 6, 'oscar', 6.66460756, 4.57031250, NULL, NULL, '2016-06-13 11:47:29', 'ALB', '5mlnCWw-6Kog7ipK44bJhhZVASqsdb-3'),
(355, 'abc', '<p>atestwt</p>\r\n', 695195, 2, 10, 'empty', 9.10209674, 23.20312500, NULL, NULL, '2016-06-16 16:11:52', 'ALB', 'NEiY3~C8Fp0QY83'),
(356, 'abc123123', '<p>abavad</p>\r\n', 22814, 1, 3, 'ccpm', NULL, NULL, NULL, NULL, '2016-06-16 16:13:55', 'AFG', 'BXsblGnCa9AJwXi'),
(357, 'abc1231231', '<p>abavad</p>\r\n', 22814, 1, 3, NULL, NULL, NULL, NULL, NULL, '2016-06-16 16:15:38', 'AFG', 'fXB44QGxO9z4bts'),
(358, 'abc12312313', '<p>abavad</p>\r\n', 22814, 1, 3, NULL, NULL, NULL, NULL, NULL, '2016-06-16 16:16:50', 'AFG', 'yu2y22e7ecsf7u4'),
(359, 'Proj Creation test', '<p>Proj Creation test</p>\r\n', 338754, 1, 6, NULL, NULL, NULL, NULL, NULL, '2016-06-17 09:40:56', 'SWE', 'q8z9NAN2z68yK7T'),
(360, 'Proj cresation old token', '<p>Proj cresation old token</p>\r\n', 338754, 1, 6, NULL, NULL, NULL, NULL, NULL, '2016-06-17 09:41:32', 'SWZ', 'bdtsexsn6qrnqm2'),
(361, 'test CD creation as normal user', '<p>test CD creation as normal user</p>\r\n', 37964, 2, 2, 'cd', NULL, NULL, NULL, NULL, '2016-06-22 15:23:02', 'ALB', 'JGOFALP7U1MFAVn'),
(362, 'hdx', '<p>nhdx</p>\r\n', 37964, 1, 2, 'cd', NULL, NULL, NULL, NULL, '2016-06-23 11:57:07', 'DEU', 'NaLMTNf3WzO2rpE'),
(363, 'Test Project creation as non admin', '<p>Test Project creation as non admin</p>\r\n', 37964, 10062, 2, 'cd', NULL, NULL, NULL, NULL, '2016-06-23 14:45:47', 'BRB', 'FX-ZDns4_2G1xAO'),
(364, 'CCPM TEst', '<p>CCPM TEst</p>\r\n', 37964, 1, 2, 'cd', NULL, NULL, NULL, NULL, '2016-06-24 11:39:21', 'AFG', 'k483yjytz2j3vri'),
(365, 'Test - nion admin proj creation', '<p>Test - nion admin proj creation</p>\r\n', 37964, 1, 2, 'cd', NULL, NULL, NULL, NULL, '2016-06-24 13:36:35', 'BGR', 'Ox3jkM8fanymQWY'),
(366, 'Proj Cre - non admin', '<p>Proj Cre - non admin</p>\r\n', 37964, 10062, 2, NULL, NULL, NULL, NULL, NULL, '2016-06-24 14:20:32', 'BHS', 'iMvyzcjsJb-Kcz8'),
(367, 'Am I on shares of a prj I create as non admin', '<p>Am I on shares of a prj I create as non admin</p>\r\n', 37964, 2, 2, NULL, NULL, NULL, NULL, NULL, '2016-06-27 15:46:20', 'CHL', 'evZbWRwF49uDQq1'),
(368, 'Proj creation as non admin', '<p>Proj creation as non admin</p>\r\n', 37964, 6, 2, NULL, NULL, NULL, NULL, NULL, '2016-06-27 15:57:14', 'AFG', 'gd64CYBx0IYB1Y_'),
(369, 'test123', '<p>test123</p>\r\n', 525573, 1, 1, NULL, NULL, NULL, NULL, NULL, '2016-06-27 15:59:56', 'AFG', 's-btf6c2S21_wEE'),
(370, 'test1234', '<p>test123</p>\r\n', 525573, 1, 1, NULL, NULL, NULL, NULL, NULL, '2016-06-27 16:02:50', 'AFG', '_bHez4nxBazh08E'),
(371, 'Proj creation non admin', '<p>Proj creation non admin</p>\r\n', 37964, 6, 2, NULL, NULL, NULL, NULL, NULL, '2016-06-27 21:13:03', 'PAK', '7d3ctwzz9scygcu'),
(372, 'Test PROJ CREAT', '<p>Test PROJ CREAT</p>\r\n', 37964, 6, 2, NULL, NULL, NULL, NULL, NULL, '2016-06-28 14:08:37', 'AUS', 'fBIrxup0soq8hAk'),
(373, 'KenyaHealth', '<p>Malaria situation</p>\r\n', 338754, 10113, 6, 'oscar', NULL, NULL, NULL, 'Nairobi', '2016-07-07 11:58:35', 'KEN', 'uoVJzA5zdIM7ohB'),
(374, 'California OSCAR', '<p>California OSCAR</p>\r\n', 338754, 10115, 6, 'oscar', NULL, NULL, '2016-07-07', NULL, '2016-07-07 12:02:57', 'BHS', 'N20ccH2jtjdtSGs'),
(375, 'Health Cluster Sitrep', '<p>Situation report for Ethiopia HC</p>\r\n', 338754, 1, 6, NULL, NULL, NULL, NULL, NULL, '2016-07-07 12:10:06', 'ETH', 'dFupdtfBplhmUAL'),
(376, 'Health', '<p>Health assessment status report</p>\r\n', 338754, 1, 6, 'oscar', NULL, NULL, NULL, 'Gaziantep', '2016-07-07 12:12:27', 'TUR', 'r2z6tb5dxatt3m8'),
(377, 'Health', '<p>Health assessment status report</p>\r\n', 338754, 1, 6, 'oscar', NULL, NULL, '2016-07-07', 'Gaziantep', '2016-07-07 12:14:29', 'TUR', 'fe2p72egbnwfd54'),
(378, 'OSCAR Afghanistan', '<p>OSCAR Afghanistan</p>\r\n', 338754, 10117, 6, 'oscar', NULL, NULL, NULL, NULL, '2016-07-07 12:23:57', 'AFG', 'Mq-SIHbCE8X7MpM'),
(379, 'California OSCAR', '<p>California OSCAR</p>\r\n', 338754, 10115, 6, NULL, NULL, NULL, NULL, NULL, '2016-07-07 12:29:44', 'BHS', 'ZZPo7OJg4iFj8WY'),
(382, 'Test sharing', 'test', 338754, 1, 6, NULL, NULL, NULL, NULL, NULL, '2016-08-18 17:07:18', 'NLD', 'bXMcZY5czysF_QD'),
(383, 'Test sharing', 'test', 338754, 1, 6, NULL, NULL, NULL, NULL, NULL, '2016-08-29 13:59:35', 'NLD', 'jNG2TGuBrqusX2B'),
(384, 'Test sharing', 'test', 338754, 1, 6, NULL, NULL, NULL, NULL, NULL, '2016-08-29 14:00:40', 'NLD', '0ONHfteWM0t8DJi'),
(385, 'Test sharing', 'test', 338754, 1, 6, NULL, NULL, NULL, NULL, NULL, '2016-08-29 15:32:36', 'NLD', 'mSUt4ZDbKfq27BZ'),
(386, 'test', '<p>test</p>\r\n', 695195, 1, 10, NULL, 0.00000000, 0.00000000, NULL, NULL, '2016-12-02 12:06:45', 'CAF', '8ZtBMRHx2UNOo4p');

-- --------------------------------------------------------

--
-- Table structure for table `prime2_report`
--

CREATE TABLE `prime2_report` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  `published` datetime NOT NULL,
  `user_data` text NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `generator` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_report`
--

INSERT INTO `prime2_report` (`id`, `email`, `user_id`, `name`, `time`, `published`, `user_data`, `project_id`, `generator`, `title`, `file_id`) VALUES
(1, 'petragallos@who.int', 1, 'Samuel Petragallo (petragallos@who.int)', '2016-03-10 13:58:28', '2016-03-10 13:58:28', '{}', 32, 'cd', 'CD Albania 2016-03-10', 1),
(2, 'petragallos@who.int', 1, 'Samuel Petragallo (petragallos@who.int)', '2016-04-05 12:48:02', '2016-04-05 12:48:02', '{\"functions_1_1_2\":\"\",\"functions_1_1_3\":\"\",\"functions_1_2_2\":\"\",\"functions_1_2_3\":\"\",\"functions_2_1_2\":\"\",\"functions_2_1_3\":\"\",\"functions_2_2_2\":\"\",\"functions_2_2_3\":\"\",\"functions_2_3_2\":\"\",\"functions_2_3_3\":\"\",\"functions_3_1_2\":\"\",\"functions_3_1_3\":\"\",\"functions_3_2_2\":\"\",\"functions_3_2_3\":\"\",\"functions_3_3_2\":\"\",\"functions_3_3_3\":\"\",\"functions_4_1_2\":\"\",\"functions_4_1_3\":\"\",\"functions_4_2_2\":\"\",\"functions_4_2_3\":\"\",\"functions_5_1_2\":\"\",\"functions_5_1_3\":\"\",\"functions_6_1_2\":\"\",\"functions_6_1_3\":\"\",\"functions_7_1_2\":\"\",\"functions_7_1_3\":\"\"}', 39, 'ccpm', 'CCPM South Sudan 2016-04-05', 2),
(3, 'sam@mousa.nl', 2, '  (sam@mousa.nl)', '2016-04-08 09:28:20', '2013-04-15 00:00:00', '{}', 286, 'cd', 'CD South Sudan (Western Equatoria) (2013)', 3),
(13, 'habshir99@yahoo.com', 10080, '  (habshir99@yahoo.com)', '2016-04-08 10:57:01', '2013-04-15 00:00:00', '{}', 241, 'ccpm', 'CCPM South Sudan (Western Equatoria) (2013)', 13),
(14, 'tanolij@who.int', 10100, '  (tanolij@who.int)', '2016-04-08 10:57:04', '2014-04-24 00:00:00', '{}', 242, 'ccpm', 'CCPM Sudan (2014)', 14),
(15, 'edabiree@who.int', 10077, '  (edabiree@who.int)', '2016-04-08 10:57:08', '2014-08-09 00:00:00', '{}', 243, 'ccpm', 'CCPM Central African Republic (2014)', 15),
(16, 'elaminz@who.int', 10078, '  (elaminz@who.int)', '2016-04-08 10:57:12', '2015-09-18 00:00:00', '{}', 244, 'ccpm', 'CCPM Sudan (Western Darfur) (2015)', 16),
(17, 'daizoa@who.int', 10075, '  (daizoa@who.int)', '2016-04-08 10:57:16', '2015-10-01 00:00:00', '{}', 245, 'ccpm', 'CCPM Chad (2015)', 17),
(18, 'gozalovo@who.int', 10079, '  (gozalovo@who.int)', '2016-04-08 10:57:23', '2015-07-11 00:00:00', '{}', 246, 'ccpm', 'CCPM Ukraine (Kharkiv) (2015)', 18),
(19, 'sanchezp@paho.org', 10097, '  (sanchezp@paho.org)', '2016-04-08 10:57:31', '2015-08-01 00:00:00', '{}', 247, 'ccpm', 'CCPM Colombia (2015)', 19),
(20, 'limon_rnp@yahoo.com', 10086, '  (limon_rnp@yahoo.com)', '2016-04-08 10:57:37', '2014-10-01 00:00:00', '{}', 248, 'ccpm', 'CCPM South Sudan (Bor) (2014)', 20),
(21, 'shariefa@who.int', 10099, '  (shariefa@who.int)', '2016-04-08 10:57:46', '2015-09-18 00:00:00', '{}', 249, 'ccpm', 'CCPM Sudan (Northern Darfur) (2015)', 21),
(22, 'yatambwede@gmail.com', 10103, '  (yatambwede@gmail.com)', '2016-04-08 10:57:55', '2013-12-22 00:00:00', '{}', 250, 'ccpm', 'CCPM Democratic Republic of the Congo (North Kivu) (2013)', 22),
(23, 'claire.who15@gmail.com', 10072, '  (claire.who15@gmail.com)', '2016-04-08 10:58:03', '2015-07-11 00:00:00', '{}', 251, 'ccpm', 'CCPM Ukraine (Donetsk) (2015)', 23),
(24, 'marschanga@who.int', 10088, '  (marschanga@who.int)', '2016-04-08 10:58:12', '2013-12-14 00:00:00', '{}', 252, 'ccpm', 'CCPM Democratic Republic of the Congo (2013)', 24),
(25, 'altafm@yem.emro.who.int', 10070, '  (altafm@yem.emro.who.int)', '2016-04-08 10:58:22', '2013-10-16 00:00:00', '{}', 253, 'ccpm', 'CCPM Yemen (2013)', 25),
(26, 'novelog@who.int', 10092, '  (novelog@who.int)', '2016-04-08 10:58:32', '2015-08-01 00:00:00', '{}', 254, 'ccpm', 'CCPM Myanmar (Rakhine) (2015)', 26),
(27, 'khanmu@pak.emro.who.int', 10083, '  (khanmu@pak.emro.who.int)', '2016-04-08 10:58:43', '2014-05-17 00:00:00', '{}', 255, 'ccpm', 'CCPM Pakistan (2014)', 27),
(28, 'wekesaj@who.int', 10102, '  (wekesaj@who.int)', '2016-04-08 10:58:56', '2014-10-01 00:00:00', '{}', 256, 'ccpm', 'CCPM South Sudan (2014)', 28),
(29, 'novelog@who.int', 10092, '  (novelog@who.int)', '2016-04-08 10:59:08', '2015-08-01 00:00:00', '{}', 257, 'ccpm', 'CCPM Myanmar (Kachin) (2015)', 29),
(30, 'ruhanam@who.int', 10095, '  (ruhanam@who.int)', '2016-04-08 10:59:21', '2015-06-26 00:00:00', '{}', 258, 'ccpm', 'CCPM Liberia (2015)', 30),
(31, 'calderom@paho.org', 10071, '  (calderom@paho.org)', '2016-04-08 10:59:35', '2014-09-20 00:00:00', '{}', 259, 'ccpm', 'CCPM Colombia (2014)', 31),
(32, 'ymu@who-health.org', 10104, '  (ymu@who-health.org)', '2016-04-08 10:59:50', '2013-04-08 00:00:00', '{}', 260, 'ccpm', 'CCPM West Bank and Gaza Strip (2013)', 32),
(33, 'mashhadik@nbo.emro.who.int', 10089, '  (mashhadik@nbo.emro.who.int)', '2016-04-08 11:00:04', '2012-03-12 00:00:00', '{}', 261, 'ccpm', 'CCPM Somalia (2012)', 33),
(34, 'kalmykova@who.int', 10081, '  (kalmykova@who.int)', '2016-04-08 11:00:19', '2015-11-20 00:00:00', '{}', 262, 'ccpm', 'CCPM Syrian Arab Republic (2015)', 34),
(35, 'davidmutonga@yahoo.com', 10076, '  (davidmutonga@yahoo.com)', '2016-04-08 11:00:34', '2013-04-15 00:00:00', '{}', 263, 'ccpm', 'CCPM South Sudan (Eastern Equatoria) (2013)', 35),
(36, 'valderramac@who.int', 10101, '  (valderramac@who.int)', '2016-04-08 11:00:50', '2015-09-10 00:00:00', '{}', 264, 'ccpm', 'CCPM Syrian Arab Republic (Gaziantep) (2015)', 36),
(37, 'sackom@ml.afro.who.int', 10096, '  (sackom@ml.afro.who.int)', '2016-04-08 11:01:06', '2013-08-30 00:00:00', '{}', 265, 'ccpm', 'CCPM Mali (2013)', 37),
(38, 'korpo304@gmail.com', 10085, '  (korpo304@gmail.com)', '2016-04-08 11:01:23', '2013-04-15 00:00:00', '{}', 266, 'ccpm', 'CCPM South Sudan (Central Equatoria) (2013)', 38),
(39, 'munima@who.int', 10090, '  (munima@who.int)', '2016-04-08 11:01:39', '2015-08-06 00:00:00', '{}', 267, 'ccpm', 'CCPM Somalia (2015)', 39),
(40, 'margata2001@gmail.com', 10087, '  (margata2001@gmail.com)', '2016-04-08 11:01:56', '2013-04-15 00:00:00', '{}', 268, 'ccpm', 'CCPM South Sudan (Northern Bahr el Ghazal) (2013)', 40),
(41, 'costamakakala@yahoo.fr', 10073, '  (costamakakala@yahoo.fr)', '2016-04-08 11:02:14', '2014-01-08 00:00:00', '{}', 269, 'ccpm', 'CCPM Democratic Republic of the Congo (South Kivu) (2013)', 41),
(42, 'tanolij@who.int', 10100, '  (tanolij@who.int)', '2016-04-08 11:02:32', '2015-09-18 00:00:00', '{}', 270, 'ccpm', 'CCPM Sudan (2015)', 42),
(43, 'alonsojc@paho.org', 10069, '  (alonsojc@paho.org)', '2016-04-08 11:02:50', '2013-10-13 00:00:00', '{}', 271, 'ccpm', 'CCPM Haiti (2013)', 43),
(44, 'abouzeida@who.int', 10067, '  (abouzeida@who.int)', '2016-04-08 11:03:08', '2015-03-10 00:00:00', '{}', 272, 'ccpm', 'CCPM Iraq (2015)', 44),
(45, 'abouzeida@who.int', 10067, '  (abouzeida@who.int)', '2016-04-08 11:03:28', '2012-03-12 00:00:00', '{}', 273, 'ccpm', 'CCPM Somalia (South Central Zone) (2012)', 45),
(46, 'coulibalyc@who.int', 10074, '  (coulibalyc@who.int)', '2016-04-08 11:03:48', '2015-04-12 00:00:00', '{}', 274, 'ccpm', 'CCPM Mali (2015)', 46),
(47, 'kormossp@who.int', 10084, '  (kormossp@who.int)', '2016-04-08 11:04:09', '2015-07-11 00:00:00', '{}', 275, 'ccpm', 'CCPM Ukraine (2015)', 47),
(48, 'yurkovai@rambler.ru', 10105, '  (yurkovai@rambler.ru)', '2016-04-08 11:04:30', '2015-07-11 00:00:00', '{}', 276, 'ccpm', 'CCPM Ukraine (Severodonetsk) (2015)', 48),
(49, 'peycheva.elena@mail.ru', 10093, '  (peycheva.elena@mail.ru)', '2016-04-08 11:04:51', '2015-07-11 00:00:00', '{}', 277, 'ccpm', 'CCPM Ukraine (Luansk) (2015)', 49),
(50, 'shankitii@afg.emro.who.int', 10098, '  (shankitii@afg.emro.who.int)', '2016-04-08 11:05:14', '2014-07-17 00:00:00', '{}', 278, 'ccpm', 'CCPM Afghanistan (2014)', 50),
(51, 'galerm@who.int', 10066, '  (galerm@who.int)', '2016-04-08 11:05:37', '2013-08-03 00:00:00', '{}', 279, 'ccpm', 'CCPM Afghanistan (2013)', 51),
(52, 'novelog@who.int', 10092, '  (novelog@who.int)', '2016-04-08 11:05:59', '2015-08-01 00:00:00', '{}', 280, 'ccpm', 'CCPM Myanmar (2015)', 52),
(53, 'khanm@who.int', 10082, '  (khanm@who.int)', '2016-04-08 11:06:23', '2015-09-18 00:00:00', '{}', 281, 'ccpm', 'CCPM Sudan (Southern Darfur) (2015)', 53),
(54, 'abouzeida@who.int', 10067, '  (abouzeida@who.int)', '2016-04-08 11:06:47', '2015-05-07 00:00:00', '{}', 282, 'ccpm', 'CCPM Afghanistan (2015)', 54),
(55, 'gozalovo@who.int', 10079, '  (gozalovo@who.int)', '2016-04-08 11:07:12', '2015-07-11 00:00:00', '{}', 283, 'cd', 'CD Ukraine (Kharkiv) (2015)', 55),
(56, 'shankitii@afg.emro.who.int', 10098, '  (shankitii@afg.emro.who.int)', '2016-04-08 11:07:14', '2014-07-17 00:00:00', '{}', 284, 'cd', 'CD Afghanistan (2014)', 56),
(57, 'claire.who15@gmail.com', 10072, '  (claire.who15@gmail.com)', '2016-04-08 11:07:15', '2015-07-11 00:00:00', '{}', 285, 'cd', 'CD Ukraine (Donetsk) (2015)', 57),
(58, 'habshir99@yahoo.com', 10080, '  (habshir99@yahoo.com)', '2016-04-08 11:07:16', '2013-04-15 00:00:00', '{}', 286, 'cd', 'CD South Sudan (Western Equatoria) (2013)', 58),
(59, 'rrbonifacio@hotmail.com', 10094, '  (rrbonifacio@hotmail.com)', '2016-04-08 11:07:17', '2013-04-15 00:00:00', '{}', 287, 'cd', 'CD South Sudan (Unity) (2013)', 59),
(60, 'davidmutonga@yahoo.com', 10076, '  (davidmutonga@yahoo.com)', '2016-04-08 11:07:18', '2013-04-15 00:00:00', '{}', 288, 'cd', 'CD South Sudan (Eastern Equatoria) (2013)', 60),
(61, 'yatambwede@gmail.com', 10103, '  (yatambwede@gmail.com)', '2016-04-08 11:07:20', '2013-12-22 00:00:00', '{}', 289, 'cd', 'CD Democratic Republic of the Congo (North Kivu) (2013)', 61),
(62, 'yurkovai@rambler.ru', 10105, '  (yurkovai@rambler.ru)', '2016-04-08 11:07:22', '2015-07-11 00:00:00', '{}', 290, 'cd', 'CD Ukraine (Severodonetsk) (2015)', 62),
(63, 'sanchezp@paho.org', 10097, '  (sanchezp@paho.org)', '2016-04-08 11:07:23', '2015-08-01 00:00:00', '{}', 291, 'cd', 'CD Colombia (2015)', 63),
(64, 'calderom@paho.org', 10071, '  (calderom@paho.org)', '2016-04-08 11:07:24', '2014-09-20 00:00:00', '{}', 292, 'cd', 'CD Colombia (2014)', 64),
(65, 'novelog@who.int', 10092, '  (novelog@who.int)', '2016-04-08 11:07:26', '2015-08-01 00:00:00', '{}', 293, 'cd', 'CD Myanmar (2015)', 65),
(66, 'njha@hotmail.com', 10091, '  (njha@hotmail.com)', '2016-04-08 11:07:27', '2013-04-15 00:00:00', '{}', 294, 'cd', 'CD South Sudan (Lakes) (2013)', 66),
(67, 'sackom@ml.afro.who.int', 10096, '  (sackom@ml.afro.who.int)', '2016-04-08 11:07:28', '2013-08-30 00:00:00', '{}', 295, 'cd', 'CD Mali (2013)', 67),
(68, 'novelog@who.int', 10092, '  (novelog@who.int)', '2016-04-08 11:07:29', '2015-08-01 00:00:00', '{}', 296, 'cd', 'CD Myanmar (Kachin) (2015)', 68),
(69, 'costamakakala@yahoo.fr', 10073, '  (costamakakala@yahoo.fr)', '2016-04-08 11:07:30', '2014-01-08 00:00:00', '{}', 297, 'cd', 'CD Democratic Republic of the Congo (South Kivu) (2013)', 69),
(70, 'galerm@who.int', 10066, '  (galerm@who.int)', '2016-04-08 11:07:32', '2013-08-03 00:00:00', '{}', 298, 'cd', 'CD Afghanistan (2013)', 70),
(71, 'limon_rnp@yahoo.com', 10086, '  (limon_rnp@yahoo.com)', '2016-04-08 11:07:33', '2013-04-15 00:00:00', '{}', 299, 'cd', 'CD South Sudan (Jonglei) (2013)', 71),
(72, 'margata2001@gmail.com', 10087, '  (margata2001@gmail.com)', '2016-04-08 11:07:34', '2013-04-15 00:00:00', '{}', 300, 'cd', 'CD South Sudan (Northern Bahr el Ghazal) (2013)', 72),
(73, 'marschanga@who.int', 10088, '  (marschanga@who.int)', '2016-04-08 11:07:36', '2013-12-14 00:00:00', '{}', 301, 'cd', 'CD Democratic Republic of the Congo (2013)', 73),
(74, 'abouzeida@who.int', 10067, '  (abouzeida@who.int)', '2016-04-08 11:07:37', '2015-03-10 00:00:00', '{}', 302, 'cd', 'CD Iraq (2015)', 74),
(75, 'daizoa@who.int', 10075, '  (daizoa@who.int)', '2016-04-08 11:07:38', '2015-10-01 00:00:00', '{}', 303, 'cd', 'CD Chad (2015)', 75),
(76, 'tanolij@who.int', 10100, '  (tanolij@who.int)', '2016-04-08 11:07:39', '2014-04-24 00:00:00', '{}', 304, 'cd', 'CD Sudan (2014)', 76),
(77, 'khanm@who.int', 10082, '  (khanm@who.int)', '2016-04-08 11:07:40', '2015-09-18 00:00:00', '{}', 305, 'cd', 'CD Sudan (Southern Darfur) (2015)', 77),
(78, 'limon_rnp@yahoo.com', 10086, '  (limon_rnp@yahoo.com)', '2016-04-08 11:07:42', '2014-10-01 00:00:00', '{}', 306, 'cd', 'CD South Sudan (Bor) (2014)', 78),
(79, 'abouzeida@who.int', 10067, '  (abouzeida@who.int)', '2016-04-08 11:07:43', '2015-05-07 00:00:00', '{}', 307, 'cd', 'CD Afghanistan (2015)', 79),
(80, 'altafm@yem.emro.who.int', 10070, '  (altafm@yem.emro.who.int)', '2016-04-08 11:07:44', '2013-10-16 00:00:00', '{}', 308, 'cd', 'CD Yemen (2013)', 80),
(81, 'ymu@who-health.org', 10104, '  (ymu@who-health.org)', '2016-04-08 11:07:45', '2013-04-08 00:00:00', '{}', 309, 'cd', 'CD West Bank and Gaza Strip (2013)', 81),
(82, 'valderramac@who.int', 10101, '  (valderramac@who.int)', '2016-04-08 11:07:47', '2015-09-10 00:00:00', '{}', 310, 'cd', 'CD Syrian Arab Republic (Gaziantep) (2015)', 82),
(83, 'novelog@who.int', 10092, '  (novelog@who.int)', '2016-04-08 11:07:48', '2015-08-01 00:00:00', '{}', 311, 'cd', 'CD Myanmar (Rakhine) (2015)', 83),
(84, 'peycheva.elena@mail.ru', 10093, '  (peycheva.elena@mail.ru)', '2016-04-08 11:07:49', '2015-07-11 00:00:00', '{}', 312, 'cd', 'CD Ukraine (Luansk) (2015)', 84),
(85, 'khanmu@pak.emro.who.int', 10083, '  (khanmu@pak.emro.who.int)', '2016-04-08 11:07:50', '2014-05-17 00:00:00', '{}', 313, 'cd', 'CD Pakistan (2014)', 85),
(86, 'munima@who.int', 10090, '  (munima@who.int)', '2016-04-08 11:07:52', '2015-08-06 00:00:00', '{}', 314, 'cd', 'CD Somalia (2015)', 86),
(87, 'abouzeida@who.int', 10067, '  (abouzeida@who.int)', '2016-04-08 11:07:53', '2013-01-02 00:00:00', '{}', 315, 'cd', 'CD Somalia (South Central Zone) (2012)', 87),
(88, 'kormossp@who.int', 10084, '  (kormossp@who.int)', '2016-04-08 11:07:54', '2015-07-11 00:00:00', '{}', 316, 'cd', 'CD Ukraine (2015)', 88),
(89, 'shariefa@who.int', 10099, '  (shariefa@who.int)', '2016-04-08 11:07:55', '2015-09-18 00:00:00', '{}', 317, 'cd', 'CD Sudan (Northern Darfur) (2015)', 89),
(90, 'elaminz@who.int', 10078, '  (elaminz@who.int)', '2016-04-08 11:07:58', '2015-09-18 00:00:00', '{}', 318, 'cd', 'CD Sudan (Western Darfur) (2015)', 90),
(91, 'edabiree@who.int', 10077, '  (edabiree@who.int)', '2016-04-08 11:08:00', '2014-08-09 00:00:00', '{}', 319, 'cd', 'CD Central African Republic (2014)', 91),
(92, 'kalmykova@who.int', 10081, '  (kalmykova@who.int)', '2016-04-08 11:08:01', '2015-11-20 00:00:00', '{}', 320, 'cd', 'CD Syrian Arab Republic (2015)', 92),
(93, 'tanolij@who.int', 10100, '  (tanolij@who.int)', '2016-04-08 11:08:02', '2015-09-18 00:00:00', '{}', 321, 'cd', 'CD Sudan (2015)', 93),
(94, 'korpo304@gmail.com', 10085, '  (korpo304@gmail.com)', '2016-04-08 11:08:03', '2013-04-15 00:00:00', '{}', 322, 'cd', 'CD South Sudan (Central Equatoria) (2013)', 94),
(95, 'mashhadik@nbo.emro.who.int', 10089, '  (mashhadik@nbo.emro.who.int)', '2016-04-08 11:08:04', '2013-01-02 00:00:00', '{}', 323, 'cd', 'CD Somalia (2012)', 95),
(96, 'alonsojc@paho.org', 10069, '  (alonsojc@paho.org)', '2016-04-08 11:08:06', '2013-10-13 00:00:00', '{}', 324, 'cd', 'CD Haiti (2013)', 96),
(97, 'wekesaj@who.int', 10102, '  (wekesaj@who.int)', '2016-04-08 11:08:07', '2014-10-01 00:00:00', '{}', 325, 'cd', 'CD South Sudan (2014)', 97),
(98, 'ruhanam@who.int', 10095, '  (ruhanam@who.int)', '2016-04-08 11:08:08', '2015-06-26 00:00:00', '{}', 326, 'cd', 'CD Liberia (2015)', 98),
(99, 'coulibalyc@who.int', 10074, '  (coulibalyc@who.int)', '2016-04-08 11:08:09', '2015-04-12 00:00:00', '{}', 327, 'cd', 'CD Mali (2015)', 99),
(100, 'robertcolombo@gmail.com', 10108, 'robert colombo (robertcolombo@gmail.com)', '2016-05-12 15:12:14', '2016-05-12 15:12:15', '{}', 330, 'prime\\reportGenerators\\oscar\\Generator', 'OSCAR Ecuador 2016-05-12', 100),
(101, 'robertcolombo@gmail.com', 10108, 'robert colombo (robertcolombo@gmail.com)', '2016-05-25 19:07:00', '2016-05-25 19:07:00', '{}', 330, 'prime\\reportGenerators\\oscar\\Generator', 'OSCAR Ecuador 2016-05-25', 101),
(102, 'robertcolombo@gmail.com', 10108, 'robert colombo (robertcolombo@gmail.com)', '2016-05-25 19:13:25', '2016-05-25 19:13:25', '{}', 330, 'prime\\reportGenerators\\oscar\\Generator', 'OSCAR Ecuador 2016-05-25', 102),
(103, 'sam@mousa.nl', 2, '  (sam@mousa.nl)', '2016-05-26 14:51:22', '2016-05-26 14:51:22', '{}', 330, 'prime\\reportGenerators\\oscar\\Generator', 'OSCAR Ecuador 2016-05-26', 103),
(104, 'petragallos@who.int', 1, 'Samuel Petragallo (petragallos@who.int)', '2016-05-27 11:57:57', '2016-05-27 11:57:57', '{\"functions_1_1_2\":\"Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\\r\\nEstablished, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\\r\\nEstablished, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\\r\\nEstablished, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\",\"functions_1_1_3\":\"Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\",\"functions_1_2_2\":\"Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\",\"functions_1_2_3\":\"\",\"functions_2_1_2\":\"\",\"functions_2_1_3\":\"\",\"functions_2_2_2\":\"\",\"functions_2_2_3\":\"\",\"functions_2_3_2\":\"\",\"functions_2_3_3\":\"\",\"functions_3_1_2\":\"\",\"functions_3_1_3\":\"\",\"functions_3_2_2\":\"\",\"functions_3_2_3\":\"\",\"functions_3_3_2\":\"\",\"functions_3_3_3\":\"\",\"functions_4_1_2\":\"\",\"functions_4_1_3\":\"\",\"functions_4_2_2\":\"\",\"functions_4_2_3\":\"\",\"functions_5_1_2\":\"\",\"functions_5_1_3\":\"\",\"functions_6_1_2\":\"\",\"functions_6_1_3\":\"\",\"functions_7_1_2\":\"\",\"functions_7_1_3\":\"\"}', 245, 'prime\\reportGenerators\\ccpm\\Generator', 'CCPM Chad 2016-05-27', 104),
(105, 'petragallos@who.int', 1, 'Samuel Petragallo (petragallos@who.int)', '2016-05-27 12:16:29', '2016-05-27 12:16:29', '{}', 17, 'prime\\reportGenerators\\oscar\\Generator', 'OSCAR South Sudan 2016-05-27', 105),
(106, 'petragallos@who.int', 1, 'Samuel Petragallo (petragallos@who.int)', '2016-06-20 13:39:29', '2016-06-20 13:39:29', '{}', 291, 'prime\\reportGenerators\\cd\\Generator', 'CD Colombia 2016-06-20', 106),
(107, 'petragallos@who.int', 1, 'Samuel Petragallo (petragallos@who.int)', '2016-08-09 13:49:23', '2016-08-09 13:49:23', '{}', 22, 'prime\\reportGenerators\\oscar\\Generator', 'OSCAR Afghanistan 2016-08-09', 107);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_response`
--

CREATE TABLE `prime2_response` (
  `id` varchar(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `survey_id` int(11) DEFAULT NULL,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prime2_setting`
--

CREATE TABLE `prime2_setting` (
  `key` varchar(32) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prime2_setting`
--

INSERT INTO `prime2_setting` (`key`, `value`) VALUES
('countryGradesSurvey', '\"486496\"'),
('countryPolygonsFile', '\"2016-04-08_16-22-00.json\"'),
('eventGradesSurvey', '\"473297\"'),
('healthClusterDashboardProject', '\"23\"'),
('healthClusterMappingSurvey', '\"259688\"'),
('icons.close', '\"stop\"'),
('icons.configuration', '\"wrench\"'),
('icons.download', '\"heart\"'),
('icons.explore', '\"search\"'),
('icons.globalMonitor', '\"globe\"'),
('icons.limeSurveyUpdate', '\"pencil\"'),
('icons.logIn', '\"log-in\"'),
('icons.logOut', '\"log-in\"'),
('icons.open', '\"play\"'),
('icons.preview', '\"file\"'),
('icons.print', '\"asterisk\"'),
('icons.proceed', '\"asterisk\"'),
('icons.projects', '\"tasks\"'),
('icons.publish', '\"asterisk\"'),
('icons.read', '\"eye-open\"'),
('icons.remove', '\"trash\"'),
('icons.reports', '\"file\"'),
('icons.request', '\"forward\"'),
('icons.requestAccess', '\"exclamation-sign\"'),
('icons.search', '\"search\"'),
('icons.share', '\"share\"'),
('icons.update', '\"cog\"'),
('icons.user', '\"user\"'),
('icons.userLists', '\"bullhorn\"'),
('limeSurvey.host', '\"https:\\/\\/ls.primewho.org\\/admin\\/remotecontrol\"'),
('limeSurvey.password', '\"H9y43n4X\"'),
('limeSurvey.username', '\"prime\"');

-- --------------------------------------------------------

--
-- Table structure for table `prime2_social_account`
--

CREATE TABLE `prime2_social_account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `data` text,
  `code` varchar(32) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_social_account`
--

INSERT INTO `prime2_social_account` (`id`, `user_id`, `provider`, `client_id`, `data`, `code`, `created_at`, `email`, `username`) VALUES
(1, 2, 'facebook', '885435041537467', '{\"name\":\"Sam Mousa\",\"email\":\"sam@mousa.nl\",\"id\":\"885435041537467\"}', NULL, NULL, 'sam@mousa.nl', NULL),
(3, 3, 'facebook', '10207798607353003', '{\"name\":\"Joey Claessen\",\"email\":\"joey_claessen@hotmail.com\",\"id\":\"10207798607353003\"}', NULL, NULL, 'joey_claessen@hotmail.com', NULL),
(4, NULL, 'google', '117237068283564619133', '{\"kind\":\"plus#person\",\"etag\":\"\\\"MrhFVuKLF7zHXL6gE2l7cEdzuiA\\/OwJJFy5RgrRszpL2xSQnC9pD2OY\\\"\",\"gender\":\"male\",\"emails\":[{\"value\":\"joey.claessen@gmail.com\",\"type\":\"account\"}],\"objectType\":\"person\",\"id\":\"117237068283564619133\",\"displayName\":\"Joey Claessen\",\"name\":{\"familyName\":\"Claessen\",\"givenName\":\"Joey\"},\"url\":\"https:\\/\\/plus.google.com\\/+JoeyClaessen\",\"image\":{\"url\":\"https:\\/\\/lh6.googleusercontent.com\\/-frFkHFInEUE\\/AAAAAAAAAAI\\/AAAAAAAAAVA\\/7q4S_0vxyww\\/photo.jpg?sz=50\",\"isDefault\":false},\"isPlusUser\":true,\"language\":\"nl\",\"circledByCount\":45,\"verified\":false}', '5989ec66757679f213eb9009b078f134', NULL, NULL, NULL),
(5, 2, 'google', '112960094649093957704', '{\"kind\":\"plus#person\",\"etag\":\"\\\"MrhFVuKLF7zHXL6gE2l7cEdzuiA\\/OkE6w-YfXAoAMpDdG2pztDWxcB0\\\"\",\"gender\":\"male\",\"emails\":[{\"value\":\"sammousa@gmail.com\",\"type\":\"account\"}],\"objectType\":\"person\",\"id\":\"112960094649093957704\",\"displayName\":\"Sam Mousa\",\"name\":{\"familyName\":\"Mousa\",\"givenName\":\"Sam\"},\"url\":\"https:\\/\\/plus.google.com\\/112960094649093957704\",\"image\":{\"url\":\"https:\\/\\/lh5.googleusercontent.com\\/--TKFKWJPqIs\\/AAAAAAAAAAI\\/AAAAAAAAJPI\\/mp17E2_p1lg\\/photo.jpg?sz=50\",\"isDefault\":false},\"isPlusUser\":true,\"language\":\"en\",\"circledByCount\":17,\"verified\":false}', '9d6f3395b9513745349218d211e126e3', NULL, NULL, NULL),
(6, 4, 'google', '103270239951845244372', '{\"kind\":\"plus#person\",\"etag\":\"\\\"4OZ_Kt6ujOh1jaML_U6RM6APqoE\\/N6BT81_hoESsH2aRb0KsTQzyA4U\\\"\",\"emails\":[{\"value\":\"mail@dirk-schumacher.net\",\"type\":\"account\"}],\"objectType\":\"person\",\"id\":\"103270239951845244372\",\"displayName\":\"Dirk Schumacher\",\"name\":{\"familyName\":\"Schumacher\",\"givenName\":\"Dirk\"},\"image\":{\"url\":\"https:\\/\\/lh3.googleusercontent.com\\/-XdUIqdMkCWA\\/AAAAAAAAAAI\\/AAAAAAAAAAA\\/4252rscbv5M\\/photo.jpg?sz=50\",\"isDefault\":true},\"isPlusUser\":false,\"language\":\"de\",\"verified\":false,\"domain\":\"dirk-schumacher.net\"}', NULL, NULL, 'mail@dirk-schumacher.net', NULL),
(7, 10112, 'google', '104655701268861915088', '{\"kind\":\"plus#person\",\"etag\":\"\\\"xw0en60W6-NurXn4VBU-CMjSPEw/DUA60b92d3i2aFtev9yckF6tLWg\\\"\",\"gender\":\"male\",\"emails\":[{\"value\":\"celso.bambaren@gmail.com\",\"type\":\"account\"}],\"objectType\":\"person\",\"id\":\"104655701268861915088\",\"displayName\":\"Celso Bambaren\",\"name\":{\"familyName\":\"Bambaren\",\"givenName\":\"Celso\"},\"url\":\"https://plus.google.com/104655701268861915088\",\"image\":{\"url\":\"https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50\",\"isDefault\":true},\"isPlusUser\":true,\"language\":\"es\",\"verified\":false}', NULL, NULL, 'celso.bambaren@gmail.com', NULL),
(8, 10113, 'google', '101161892409519134463', '{\"kind\":\"plus#person\",\"etag\":\"\\\"xw0en60W6-NurXn4VBU-CMjSPEw/DL4YkxbbbFHHY6yk7iehvX-M5JA\\\"\",\"gender\":\"male\",\"emails\":[{\"value\":\"benaoduor75@gmail.com\",\"type\":\"account\"}],\"objectType\":\"person\",\"id\":\"101161892409519134463\",\"displayName\":\"Bernard Oduor\",\"name\":{\"familyName\":\"Oduor\",\"givenName\":\"Bernard\"},\"url\":\"https://plus.google.com/101161892409519134463\",\"image\":{\"url\":\"https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50\",\"isDefault\":true},\"isPlusUser\":true,\"language\":\"en\",\"circledByCount\":0,\"verified\":false}', NULL, NULL, 'benaoduor75@gmail.com', NULL),
(9, 10119, 'google', '101592868926099311438', '{\"kind\":\"plus#person\",\"etag\":\"\\\"xw0en60W6-NurXn4VBU-CMjSPEw/bU1BdVNRXR0R2eO4EDpdu1dQvCE\\\"\",\"gender\":\"female\",\"emails\":[{\"value\":\"snit5tekeste@gmail.com\",\"type\":\"account\"}],\"objectType\":\"person\",\"id\":\"101592868926099311438\",\"displayName\":\"Senait Tekeste\",\"name\":{\"familyName\":\"Tekeste\",\"givenName\":\"Senait\"},\"url\":\"https://plus.google.com/101592868926099311438\",\"image\":{\"url\":\"https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50\",\"isDefault\":true},\"isPlusUser\":true,\"language\":\"en\",\"circledByCount\":0,\"verified\":false}', NULL, NULL, 'snit5tekeste@gmail.com', NULL),
(10, 10121, 'google', '104530481380720923806', '{\"kind\":\"plus#person\",\"etag\":\"\\\"xw0en60W6-NurXn4VBU-CMjSPEw/ABAd_QGkVBWtVUQP90s2-OZEJZE\\\"\",\"occupation\":\"Study\",\"gender\":\"male\",\"emails\":[{\"value\":\"brvdpu@gmail.com\",\"type\":\"account\"}],\"urls\":[{\"value\":\"http://picasaweb.google.com/brvdpu\",\"type\":\"otherProfile\",\"label\":\"Picasa Web Albums\"},{\"value\":\"http://brvdpu.hyves.nl\",\"type\":\"other\",\"label\":\"Hyves\"}],\"objectType\":\"person\",\"id\":\"104530481380720923806\",\"displayName\":\"Bram van der Putten\",\"name\":{\"familyName\":\"van der Putten\",\"givenName\":\"Bram\"},\"aboutMe\":\"Hi, contact me for more information, only for more information<br />\",\"url\":\"https://plus.google.com/104530481380720923806\",\"image\":{\"url\":\"https://lh5.googleusercontent.com/-bOphxlHY5fw/AAAAAAAAAAI/AAAAAAAAEWg/H_0eMpX29hQ/photo.jpg?sz=50\",\"isDefault\":false},\"placesLived\":[{\"value\":\"Eindhoven, Noord-Brabant\",\"primary\":true},{\"value\":\"Ermelo, Gelderland\"}],\"isPlusUser\":true,\"language\":\"en_GB\",\"circledByCount\":35,\"verified\":false}', NULL, NULL, 'brvdpu@gmail.com', NULL),
(11, 10123, 'google', '102881543013279498110', '{\"kind\":\"plus#person\",\"etag\":\"\\\"FT7X6cYw9BSnPtIywEFNNGVVdio/-x1jADYp-d5mMwI2L_AcesLVG2A\\\"\",\"gender\":\"male\",\"emails\":[{\"value\":\"karantili@gmail.com\",\"type\":\"account\"}],\"urls\":[{\"value\":\"http://www.youtube.com/user/Thekapoi\",\"type\":\"otherProfile\",\"label\":\"Karantili Kapoi\"}],\"objectType\":\"person\",\"id\":\"102881543013279498110\",\"displayName\":\"Karantili Kapoi\",\"name\":{\"familyName\":\"Kapoi\",\"givenName\":\"Karantili\"},\"url\":\"https://plus.google.com/102881543013279498110\",\"image\":{\"url\":\"https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50\",\"isDefault\":true},\"isPlusUser\":true,\"language\":\"en_GB\",\"circledByCount\":32,\"verified\":false}', NULL, NULL, 'karantili@gmail.com', NULL),
(12, 10111, 'google', '103359618585951601793', '{\"kind\":\"plus#person\",\"etag\":\"\\\"FT7X6cYw9BSnPtIywEFNNGVVdio/tnuyNNLLIRpXG4EwVpVj6zTQFPA\\\"\",\"gender\":\"male\",\"emails\":[{\"value\":\"travelkev@hotmail.com\",\"type\":\"account\"}],\"objectType\":\"person\",\"id\":\"103359618585951601793\",\"displayName\":\"Kevin Crampton\",\"name\":{\"familyName\":\"Crampton\",\"givenName\":\"Kevin\"},\"url\":\"https://plus.google.com/103359618585951601793\",\"image\":{\"url\":\"https://lh6.googleusercontent.com/-9uzXN49FhvE/AAAAAAAAAAI/AAAAAAAAACY/kYKEQm6xyvc/photo.jpg?sz=50\",\"isDefault\":false},\"organizations\":[{\"name\":\"University of Bristol Faculty of Science\",\"type\":\"school\",\"startDate\":\"1991\",\"endDate\":\"1994\",\"primary\":false},{\"name\":\"Avanade Schweiz GmbH\",\"type\":\"work\",\"startDate\":\"2013\",\"primary\":true}],\"placesLived\":[{\"value\":\"74370 Metz-Tessy, France\",\"primary\":true}],\"isPlusUser\":true,\"language\":\"en_GB\",\"circledByCount\":41,\"verified\":false,\"cover\":{\"layout\":\"banner\",\"coverPhoto\":{\"url\":\"https://lh3.googleusercontent.com/RhN99f0jyjLAATQh3BSVS3SSfQ5gE3UFBGrnKAWUowcN-eHePri-DW52zXKXLc5Vqi6NaGQ=s630-fcrop64=1,179a27dad79ab850\",\"height\":705,\"width\":940},\"coverInfo\":{\"topImageOffset\":0,\"leftImageOffset\":0}}}', NULL, NULL, NULL, NULL),
(13, 10127, 'facebook', '10155387061220353', '{\"name\":\"Kevin Crampton\",\"email\":\"travelkev@hotmail.com\",\"id\":\"10155387061220353\"}', NULL, NULL, 'travelkev@hotmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_source_message`
--

CREATE TABLE `prime2_source_message` (
  `id` int(11) NOT NULL,
  `category` varchar(32) DEFAULT NULL,
  `message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prime2_survey`
--

CREATE TABLE `prime2_survey` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prime2_token`
--

CREATE TABLE `prime2_token` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_token`
--

INSERT INTO `prime2_token` (`user_id`, `code`, `created_at`, `type`) VALUES
(2, 'DQzmNeBqc8lqacnszCEBHDrlY3AOY0lC', 1460460792, 1),
(3, 'aMcldSehP0e_feLKXtGvVbfZJcx25vGR', 1449759426, 1),
(6, '6LIrwoTq5mvSqqBHO1pZnU_uOtWF4kkQ', 1454593475, 2),
(6, 'rx8A8R1hYBd9PECEdeELfOrqllMq9YWi', 1454593131, 1),
(10062, '_kEHkLYqpWRR_-9LK5MEMMmMVPL0mmSj', 1460380234, 1),
(10114, 'i4l_iEWHuX5CROBfRvwXnHYnF1tBXg4Q', 1467812086, 0),
(10120, '3Uc88hVchb4mwq6Kr70EuA7gqMW-lxxl', 1472053458, 0),
(10122, 'fiVme4IBZ2-RFpDPUh6r9p_GE01CqUvp', 1473162572, 0),
(10124, 'KQU9CYyt-o5cp2d4ttQfUH73VFOymkik', 1479908614, 0),
(10125, 'Ju4hO63JCLGDNElr8ej4ndZJdYNMoYm6', 1479969008, 0),
(10126, 'DPRmR5e8OtIzRSCx31DUrD5nMBubDHii', 1479977883, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_tool`
--

CREATE TABLE `prime2_tool` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `intake_survey_eid` int(11) DEFAULT NULL,
  `base_survey_eid` int(11) DEFAULT NULL,
  `progress_type` varchar(50) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `generators` varchar(255) DEFAULT NULL,
  `acronym` varchar(255) NOT NULL,
  `hidden` tinyint(1) DEFAULT '0',
  `default_generator` varchar(255) DEFAULT NULL,
  `explorer_regex` varchar(255) DEFAULT NULL,
  `explorer_name` varchar(255) DEFAULT NULL,
  `explorer_geo_js_name` varchar(255) DEFAULT NULL,
  `explorer_geo_ls_name` varchar(255) DEFAULT NULL,
  `explorer_map` mediumblob,
  `explorer_show_services` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_tool`
--

INSERT INTO `prime2_tool` (`id`, `title`, `image`, `description`, `intake_survey_eid`, `base_survey_eid`, `progress_type`, `thumbnail`, `generators`, `acronym`, `hidden`, `default_generator`, `explorer_regex`, `explorer_name`, `explorer_geo_js_name`, `explorer_geo_ls_name`, `explorer_map`, `explorer_show_services`) VALUES
(1, 'Test tool', '1.jpg', '<p>Some tool,&nbsp;<strong>with some styling</strong>, and&nbsp;<em>some</em>&nbsp;<u>extra</u></p>\r\n', 318522, 525573, 'ccpmPercentage', NULL, '{\"0\":\"ccpm\"}', 'T', 0, '', 'HF.*', 'Health facilities', NULL, NULL, NULL, 0),
(2, 'Cluster Description', '2.jpg', '<p><span style=\"color:rgb(34, 34, 34)\">The Cluster Description is designed to provide a detailed insight into the cluster structure and governance mechanisms</span></p>\r\n', 137246, 37964, 'cdProgress', '2_thumbnail.jpg', '{\"0\":\"cd\"}', 'CD', 0, 'cd', NULL, NULL, NULL, NULL, NULL, 0),
(3, 'Cluster Coordination Performance Monitoring', '3.jpg', '<p>CCPM is a process...</p>\r\n', 41457, 22814, 'ccpmPercentage', '3_thumbnail.jpg', '{\"0\":\"ccpm\"}', 'CCPM', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(6, 'OSCAR', '6.jpg', '<p>OSCAR</p>\r\n', 318522, 338754, 'oscarProgress', '6_thumbnail.jpg', '{\"0\":\"oscar\"}', 'OSCAR', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(7, 'Health Cluster Mapping', '7.png', '<p>Health Cluster Mapping</p>\r\n', 137246, 259688, 'progress', '7_thumbnail.png', '{\"0\":\"hc\"}', 'HCM', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(8, 'Readiness Token_No_Attribute Test', '8.jpg', '<p>Test Token with no attributes</p>\r\n', 137246, 211726, 'empty', '8_thumbnail.jpg', '{\"0\":\"empty\"}', 'Ready', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(9, 'Grading Monitoring - CLE', '9.png', '<p>Grading Monitoring - CLE</p>\r\n', 137246, 473297, 'progress', '9_thumbnail.png', '{\"0\":\"empty\"}', 'GM-CLE', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(10, 'HeRAMS RCA', '10.png', '<p>HeRAMS RCA - Test</p>\r\n', 318522, 695195, 'progress', '10_thumbnail.png', '{\"0\":\"empty\"}', 'HeRAMS', 0, '', '', 'Infrastructure', '', '', NULL, 1),
(11, 'test', NULL, '<p>test</p>\r\n', 318522, 193311, 'cdProgress', NULL, '{\"0\":\"ccpm\",\"1\":\"cd\",\"2\":\"cdProgress\",\"3\":\"ccpmPercentage\",\"4\":\"oscar\",\"5\":\"oscarProgress\",\"6\":\"hc\",\"7\":\"empty\",\"8\":\"progress\"}', 'test', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(12, 'CoC', '12.jpg', '<p>CoC</p>\r\n', 137246, 939861, 'progress', '12_thumbnail.jpg', '{\"0\":\"progress\"}', 'CoC', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_user`
--

CREATE TABLE `prime2_user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(60) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(45) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  `username` varchar(1) DEFAULT NULL,
  `access_token` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_user`
--

INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `auth_key`, `confirmed_at`, `unconfirmed_email`, `blocked_at`, `registration_ip`, `created_at`, `updated_at`, `flags`, `username`, `access_token`) VALUES
(1, 'petragallos@who.int', '$2y$10$uUNBQHISEmchkCd6XbuaC.SmVD414q/xqQzae2jXLSHL3WUj17tjq', 'RQDuCiHaAT7713CtSeVeUEUZe-MNcqC9', 1443616645, NULL, NULL, '158.232.3.98', 1443616577, 1443616645, 0, NULL, 'eaf11db5f4e0bdea56113234fc44ec5d'),
(2, 'sam@mousa.nl', '$2y$10$sOIpKwIB33YKC8E/IBaskefGFHWdWM9ghTCs8hoAS5GlM8BvtVg82', '_ij2mT3W1tYeyrCzM-v5s2oOIEYtlqpn', 1444205654, NULL, NULL, '92.111.242.3', 1444205654, 1444205654, 0, NULL, '74ac247668a3ad5205f15754f18af7f1'),
(3, 'joey_claessen@hotmail.com', '$2y$10$BcRgNqmrNcPRL0aKMhcUeeGMgFgcQFjxJeUTkyD7McjCkuPiKdWDq', 'UDlrBFD0_HauHO0BS-TqGbkcf4YIgsxe', 1445437844, NULL, NULL, '92.111.242.3', 1445437845, 1445437845, 0, NULL, 'ddcc76aeb7f7afb5c20d1db2f928f1ee'),
(4, 'mail@dirk-schumacher.net', '$2y$10$he1wMrs/f1eRJmK9bGL.IOwTC63bz1xERZdmxYv6/eQUt679lCtK6', '3wCOgkzbLk_ASBzSd70zP0XBRBJ5hulv', 1447942953, NULL, NULL, '93.219.127.61', 1447942940, 1447942940, 0, NULL, 'da827e2981614df4818f3a5f5c474495'),
(5, 'polonskyj@who.int', '$2y$10$ItNlmxZ.KhDGJsXOS.rsY.qR.KhnBfT7br6hIyxdEyISwPkQojvbi', 'Cw4CPphXuxWtt3EllIaasU7uqR0q_P5Q', 1447945828, NULL, NULL, '158.232.3.98', 1447945819, 1447945819, 0, NULL, '6b09c188e48ce4d6c7576ddc95aa2126'),
(6, 'deradiguesx@who.int', '$2y$10$me0zBtKTiV0oFIJsLrGzceKvoBNqpLjNo7/FWVIOmWDF2f4D3Dqb.', 'gowU9udcSpd3-8PvSuBDXDv7Seq0Z-2t', 1447952959, NULL, NULL, '81.164.25.229', 1447952931, 1454593982, 0, NULL, '085e1f753eef2a2ebd11a6ba1f422568'),
(10002, 'aldlaimis@who.int', 'NOPASSWORD', 'la-vK7yLjgQzs_g_8dGJq80wh4zICM3x', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'ff487f29577ecee8759498aa91fbc075'),
(10004, 'g.alkema@savethechildren.org.uk', 'NOPASSWORD', 'k27OQVTaHJ9fxdhuuPsTJxRCY4W3Ft3v', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '358ba267a6d4d69e27ef0bae067ae47a'),
(10006, 'altafm@who.int', 'NOPASSWORD', '4-qjWzFgZ2vnmqay6ay1lMk_g0u3kHfp', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'ec5fa8c6a2202f12629cf583afca8eea'),
(10007, 'armahm@who.int', 'NOPASSWORD', 'ANnfHvYbEsG5QLKH_e5fXMB2radpuw-h', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '01ec6b793c0431575d91d0881924618c'),
(10009, 'bisalinkumie@who.int', 'NOPASSWORD', 'XT9vw2fxzfsK7eoanDL-BfKrKjN6iBRi', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '96ba04bd7eaeb01d721cd5b82f0410bd'),
(10010, 'babaevam@who.int', 'NOPASSWORD', 'iZiSCuFTl_hn-qLJuPxsDCKx0xnrS1jI', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '7a00c68d14497995f6c22bd3b604b0b3'),
(10012, 'charimaril@zw.afro.who.int', 'NOPASSWORD', 'R3e0Zz-5NsLHk54JalB41AGPD5REVkwh', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'c320c65f814ae12299dc442e1ff48f5e'),
(10013, 'daherm@who.int', 'NOPASSWORD', 'YpRY8Sd5o94G8MQHbLMmD8gJXBvEV1El', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '4cd5467ee07e0d4bea621c00d40ce67a'),
(10015, 'dabiree@who.int', 'NOPASSWORD', 'b2gbiVnsH8QkN1GHjcNOO_ajdtO5uKxA', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'bfb1ea2a4f64ca39216690d9915d49ae'),
(10016, 'dialloam@who.int', 'NOPASSWORD', 'tyd5b1GVhirtk13I0VVNsD1jmw4NsDf1', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '3b95adfccef12bfede77ba2c9802439c'),
(10017, 'laneb@wpro.who.int', 'NOPASSWORD', 'aJLbmAngkgCYAfqXtrTb71jjZx0qkrNa', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '8a7de9e3a37a8fd964a90429856c834f'),
(10018, 'dubeal@who.int', 'NOPASSWORD', 'Mudr7NjOhfiNHDcV2MMNAut5Hqjp1RzT', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'c0d604f386e970effe9f9cc10652ae0f'),
(10019, 'dulyxj@who.int', 'NOPASSWORD', '8gz8rS85M-BqlLWez_51WMgWrp4WiwQx', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'c08a1f73592ad4c3991774cf115f8eaf'),
(10020, 'elganainya@who.int', 'NOPASSWORD', 'Lwp7JH4r29J50oHTxln7ALK0MuU24E8s', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '2e168ac1e2f465fbf4b8ff3bceccd822'),
(10021, 'fotsingri@who.int', 'NOPASSWORD', 'Mmxgillhy1LsEDA2pINITWnBSbRgf8-3', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'c0d17ceca0d6d47c35dda46a1a7f98dc'),
(10022, 'guyoa@who.int', 'NOPASSWORD', 'xZrKePph9EDjZ5EdV1LyAcGgdFaYikFX', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'c65e978a4410a3006e8026fc22f30cd3'),
(10023, 'hamptonc@who.int', 'NOPASSWORD', '0777mLCPY4SJNbt2ZYaJjOabhTYnso1U', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '66a385a30f18a7639d4ef56f8ff13166'),
(10024, 'hoffe@who.int', 'NOPASSWORD', '5abjJAuB2SeaHeGCV44IO2hibO5jo7Qt', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'c02498397adab19ca7feb943e3950f74'),
(10025, 'jahangir_95@yahoo.com', 'NOPASSWORD', 'pKzs-tMx2Ni6va-qdqKdcF_lI6fw6GBD', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '53dde7d473bd7feb95931538dc045950'),
(10029, 'kimhy@who.int', 'NOPASSWORD', 'D4AklVO2w3oL0n95y6ZIoP6K2p_4mWaO', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '549503195e4905463da4ad6644c75b18'),
(10030, 'kimr@wpro.who.int', 'NOPASSWORD', 'q7ITnWh1uELU_oLwgfxblqGCc-mpXN84', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '748018b9f41aa21d3d246758e0bab55b'),
(10032, 'lombelelo@yahoo.ca', 'NOPASSWORD', '4xyfaMzXML2axw4fbiDCKE4HQ-ZN5BjY', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '319a7d769ed93d91d3dcb7bb0ecc1d95'),
(10033, 'lukwesamwatij@who.int', 'NOPASSWORD', '1zih3aJcgeairTa1z4mVf3oTqSwzAG_S', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'b4fa2d511d191cfb6bcacdb5c99dba75'),
(10034, 'makakalamuhululu@who.int', 'NOPASSWORD', 'kMLjNea3VDcXJTk37-mNkLaZOyYMRO0v', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '1935b2e38c41cc57505f18986e35fec9'),
(10036, 'hamasham@who.int', 'NOPASSWORD', 'su7ly6Z51A1Rh1DLzZxyDMSsGk8knZiV', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '825e21935aa2f1ae7c384546b802c83b'),
(10037, 'shariefa@sud.emro.who.int', 'NOPASSWORD', 'lxhVSCfmRNRQNMxl75TqjMZ3q-waDr1a', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '5173439456714e7b667cecb25a26b012'),
(10039, 'mucipayndumbia@who.int', 'NOPASSWORD', 'OqM3IaQao9NwoA4ec6pIGKrLpIWAe9gU', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'c62d5a92d474328eae04e7ebb05a0ac0'),
(10040, 'dnahaabi@yahoo.co.uk', 'NOPASSWORD', '_JUZLGhczmBqif9sTuyK4o-zKDZAjsYf', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'baded8b6558b63a94c072d503be07e6d'),
(10041, 'ngobilaco@who.int', 'NOPASSWORD', 'jHWWEwPbwkLx3uEYi5QoSGyq1IYKMaFr', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '96507c96ad3653113659386ace062fbb'),
(10042, 'niangs@who.int', 'NOPASSWORD', 'QbSZpa6TzzzTLYQKoHMS0bGKeG-g6Fyy', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '9103ef69021529da7a9f8b8b03b6a19d'),
(10043, 'don@euro.who.int', 'NOPASSWORD', 'AKURwxj07NOtYdArsLNHxgbY0eBfit9g', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'f88c56b6a02e60b0990f8416e7706ed9'),
(10044, 'novelog@searo.who.int', 'NOPASSWORD', 'W9e1F9djM4t3fNcrsWYx9KpbZjr5FoI2', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'fa9adc13e3e67cb3e0d40d62618972f5'),
(10045, 'nzeyimanai@who.int', 'NOPASSWORD', '_z4isGSOrI5gIqmfXX5IOWZsLuBQH3Z3', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'ddfd55ebe145062e54a886aea8534c82'),
(10046, 'ahmedouy@mr.afro.who.int', 'NOPASSWORD', 'e8u0zw0_kj2XNa29hk_vb449lFAF_Lml', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '1426d0178bcb468d7ba3862f29af3542'),
(10047, 'pannella@who.int', 'NOPASSWORD', '7CTVfRDg_TIxx8jDPlIcZOLLB4ItQuYc', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '149b36dbd9faa4b51954113335b79393'),
(10049, 'sackom@who.int', 'NOPASSWORD', 'GBY69S25L_m_HJS1MR3iB2zkGOQcvgaW', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '9ed3e3429f9b1b6114874c9fb0b6ea39'),
(10050, 'salvadore@searo.who.int', 'NOPASSWORD', 'jhemxltj2oEThyCDIf3Gd4zP-zKlesVC', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '1f20ac12c8cbdbb87ddce423fbe04b09'),
(10051, 'samak@who.int', 'NOPASSWORD', 'fpNle3whP1xERJ3K9iigVZyd2H_-JPC_', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'ce3ef4d3d88da0b975210b411eb81305'),
(10053, 'soboha@who.int', 'NOPASSWORD', 'eZN0uHmuPBk418NvaP6Q0Bn-S18ReQB4', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '9677ae7d960f90bc8ce7c5834ab12e47'),
(10054, 'tanolij@sud.emro.who.int', 'NOPASSWORD', '4HLeVFNAensOOccyow-XaxM51jaoC6DE', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, 'b135d4a6c4c4730be8cd52d71619b9e4'),
(10055, 'yaot@who.int', 'NOPASSWORD', 'VJ4REf3xR9-lbH17JYAOv3ty0VzwKoPu', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '1fc87f1d3064dfc2800e854e03e155b0'),
(10058, 'woldegebrieltede@who.int', 'NOPASSWORD', '5A32K8IAbT6iaQ083wIvVFI8cVSWcNGK', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '0f67c5a08bcd3277d148aa66de5b7a24'),
(10059, 'adandjiyaohloua@who.int', 'NOPASSWORD', 'tikvQy5SsHM6pZelrEEKdIB6jGw5OSYx', 1452776926, NULL, NULL, NULL, 1452776926, 1452776926, 0, NULL, '643cdc47978b3d9f2862c135d1420701'),
(10062, 'petragallosam@linuxmail.org', '$2y$10$oGydmByRIu2vj.FFNVmIv.bLQ51RK2LX/xTW7ki6rmG3l5fUw/66a', 'NfWYV-8dl3hOxBBaeS9VqQQ11GLPdieZ', 1457442849, NULL, NULL, '158.232.3.98', 1457442752, 1457442752, 0, NULL, NULL),
(10066, 'galerm@who.int', 'NOPASSWORD', '2dykvpzgyUfQt0o0Afq2Tbsc3flRt_qv', 1460104724, NULL, NULL, NULL, 1460104724, 1460104724, 0, NULL, NULL),
(10067, 'abouzeida@who.int', 'NOPASSWORD', 'zuavkMRNMHGiUHaym8_SlmjTPPS7BjoG', 1460104724, NULL, NULL, NULL, 1460104724, 1460104724, 0, NULL, NULL),
(10068, 'abouzeidhcc@gmail.com', 'NOPASSWORD', '9Bn7L_YrQbpYGuNfX5bsK2mthzqfU5D6', 1460104724, NULL, NULL, NULL, 1460104724, 1460104724, 0, NULL, NULL),
(10069, 'alonsojc@paho.org', 'NOPASSWORD', 'AH342AjDVsIbVnvSEQKDkEazmF9bDVmj', 1460104724, NULL, NULL, NULL, 1460104724, 1460104724, 0, NULL, NULL),
(10070, 'altafm@yem.emro.who.int', 'NOPASSWORD', 'aNIQTpeNgu_OeUhJCN-cvpAgefmSGH2U', 1460104724, NULL, NULL, NULL, 1460104724, 1460104724, 0, NULL, NULL),
(10071, 'calderom@paho.org', 'NOPASSWORD', 'kw7qdCVozzzp6_gR12wADLAr0jccRu4P', 1460104724, NULL, NULL, NULL, 1460104724, 1460104724, 0, NULL, NULL),
(10072, 'claire.who15@gmail.com', 'NOPASSWORD', '_FX3lFKVhwTb8QpHbQLnKo0sak5XPEar', 1460104724, NULL, NULL, NULL, 1460104724, 1460104724, 0, NULL, NULL),
(10073, 'costamakakala@yahoo.fr', 'NOPASSWORD', 'DDVbRsVFsihu__OplkDWPOMDYvz-QDO9', 1460104724, NULL, NULL, NULL, 1460104724, 1460104724, 0, NULL, NULL),
(10074, 'coulibalyc@who.int', 'NOPASSWORD', 'J4_zbyxCPECxRwiG_0o8MGhQMB0RvIsH', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10075, 'daizoa@who.int', 'NOPASSWORD', 'Dvg7NtAW-E5XXHUYYn777uBYOaTctu6B', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10076, 'davidmutonga@yahoo.com', 'NOPASSWORD', 'kjwGRQ5qHLy4qZlAoPwlE4-vf3kipPB-', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10077, 'edabiree@who.int', 'NOPASSWORD', 'T0MPH5bqJaJJxJ-AlhnpMEpZm7rYuLHr', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10078, 'elaminz@who.int', 'NOPASSWORD', 'Q6Xp-W1UP8Z_TCi6G6wF13791KLCgzC4', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10079, 'gozalovo@who.int', 'NOPASSWORD', 'JX7y5AZypsu9sc_fl_FLiJ1WHtJYT8xO', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10080, 'habshir99@yahoo.com', 'NOPASSWORD', 'RV6TSp8F25DkjHHcMsYXrS_1LvQ7tSYq', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10081, 'kalmykova@who.int', 'NOPASSWORD', 'KV38x2uiq9XcxF26tVCW1kGMQGQtPcL0', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10082, 'khanm@who.int', 'NOPASSWORD', 'hpI99j6MlhdSDIE89R7nVT7nJRQ7NJDN', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10083, 'khanmu@pak.emro.who.int', 'NOPASSWORD', '-AzvsGHVCU8pfa1I1RnG6-n6DKTKksK8', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10084, 'kormossp@who.int', 'NOPASSWORD', 'k_9Ldo32RAEFyV_4d-3tFF4cXy2fjhCY', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10085, 'korpo304@gmail.com', 'NOPASSWORD', 'oU49FvTsGfP5bHEh1t2zpyQ2tEk1G3J6', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10086, 'limon_rnp@yahoo.com', 'NOPASSWORD', '3VqVGEwKs5d2WlMAb_qNOOUx9Usp5oci', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10087, 'margata2001@gmail.com', 'NOPASSWORD', 'yjebLaR9AALWISFSTTtTTOtWhbYzuWNK', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10088, 'marschanga@who.int', 'NOPASSWORD', '0tDjiEec6scD2fjMFOlV7mGUFTBls_Jj', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10089, 'mashhadik@nbo.emro.who.int', 'NOPASSWORD', 'Xk5dDcWT-JzQsY1s3Agmoc_bV6fj3HIw', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10090, 'munima@who.int', 'NOPASSWORD', 'oaDOMv6r3MC0BHPOkicTGsL1NpihMkFm', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10091, 'njha@hotmail.com', 'NOPASSWORD', 'YzCdgUnLo6kryzH2sbobjWGwjvgJRGxL', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10092, 'novelog@who.int', 'NOPASSWORD', '9Tcer1agN9loBIqE4XRXNHoc_bFV3_8i', 1460104725, NULL, NULL, NULL, 1460104725, 1460104725, 0, NULL, NULL),
(10093, 'peycheva.elena@mail.ru', 'NOPASSWORD', '7Rmcs0rh797dN8W08L7FER_97EztJcxK', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10094, 'rrbonifacio@hotmail.com', 'NOPASSWORD', 'A9Wo3Ifo_-fg8jhNaQ-dYNuqw8zIq2m4', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10095, 'ruhanam@who.int', 'NOPASSWORD', 'BiV1aJxmzYirdlwHoeQCoKAhJdW4NGTD', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10096, 'sackom@ml.afro.who.int', 'NOPASSWORD', 'qUzvAVX5CKrYrftpes_rlxgOpGIKU87o', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10097, 'sanchezp@paho.org', 'NOPASSWORD', 'zZgS1QY7PzlOx43vhlHGyVUPB4oCrHQr', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10098, 'shankitii@afg.emro.who.int', 'NOPASSWORD', 'IvzsYAB2eFMj26eE57xshpVVnCWPhDic', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10099, 'shariefa@who.int', 'NOPASSWORD', 'JWPY3rQuApsryxBXY9LIPomlt-VXpMEH', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10100, 'tanolij@who.int', 'NOPASSWORD', 'KOWr1NZZ8hcv3XnS4fePvLRqO_w4bCbO', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10101, 'valderramac@who.int', 'NOPASSWORD', '13h7BsRO1amOaIgz9uKn5qL2OW-Cn3d8', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10102, 'wekesaj@who.int', 'NOPASSWORD', 'Z7-7gxttuqwN4AQZti8jjKox8xQ-0QsM', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10103, 'yatambwede@gmail.com', 'NOPASSWORD', 'k7swddgY9YQmPBcsNNXGO5NwOtMoGAaT', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10104, 'ymu@who-health.org', 'NOPASSWORD', 'UcUcd7dSx31kOUC_Zmi_BXNqXe_POtnO', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10105, 'yurkovai@rambler.ru', 'NOPASSWORD', 'IdzOZhTBZ8jZ3SV-0MHRXm3RlTcbU4Ue', 1460104726, NULL, NULL, NULL, 1460104726, 1460104726, 0, NULL, NULL),
(10107, 'samuel.petragallo@gmail.com', '$2y$10$FWCCTdUMb4BpIATOzv8loe74MqV/E9dRHb.J90zYDiZaiNGejeCam', 'iRQQ34Aktj4GkA-_X_ssN_Cwiw8ey40W', 1460462020, NULL, NULL, '158.232.3.98', 1460461992, 1460461992, 0, NULL, NULL),
(10108, 'robertcolombo@gmail.com', '$2y$10$ft9i9gWjSi/Iqa5MW8q4ee8Zab.G2cTmjoDq5ugQOYwekEM5pygty', 'f9-bUB2G3cGzeCw1QdByhaHaU6nBzIrM', 1463056994, NULL, NULL, '38.100.10.130', 1463056970, 1463056970, 0, NULL, NULL),
(10109, 'sowmya.adibhatla@gmail.com', '$2y$10$TcR63CIsQhOX8S4GammCvuXr7k6DsRX3PWfCVxtL.Au/scVUmdS02', 'xkJ4-M_iHzf_cuuDIw7Ls90flPNVHEaD', 1463058768, NULL, NULL, '38.100.10.130', 1463057261, 1463057261, 0, NULL, NULL),
(10110, 'lohsei@who.int', '$2y$10$1IcTUnqA9eU3Mrz8pTOqDeABLfC50FTRk5cDJFz9KqxmJZQ4.tTIe', 'aaqpGLkpv5kyfO-0YflEANhylnPus8BA', 1464588401, NULL, NULL, '202.126.123.101', 1464588359, 1464588359, 0, NULL, NULL),
(10111, 'cramptonk@who.int', '$2y$10$2gSpB4HV01vOGVDpxxy2Je4gHjx4.ypSUPjZTmb7rufKCajr1on/.', 'BzNo4XnQ8l5ev17g8nkMzQGcGdESxSnJ', 1464621918, NULL, NULL, '158.232.3.98', 1464621747, 1464621747, 0, NULL, NULL),
(10112, 'celso.bambaren@gmail.com', '$2y$10$V8mCkddtmq4iYMlkGUTmkO.ufFB0cEz4dOcUCKAvRQEf5JdzdrJ/y', 'jtavAItR3XCkeS-YfzYIYcnmjOrK6DuN', 1467298044, NULL, NULL, '187.189.186.3', 1467298044, 1467298044, 0, NULL, NULL),
(10113, 'benaoduor75@gmail.com', '$2y$10$rQ9LXb/cy9a3hfRX6ZqXjutzQwhBnIpiJ4MOptYy4i63abJ3oxNt.', 'aB2QAcmJ3DyjLCNjNr44C3j6887LqaO_', 1467811956, NULL, NULL, '195.190.29.128', 1467811956, 1467811956, 0, NULL, NULL),
(10114, 'jessicadell3@gmail.com', '$2y$10$I.kpSe6igXRn2ZutlQxANOdBuK7M9uaA30nsYbjxJlQqvfs17epji', '_av_N9PHCMyWLXPrVJiyrDwf_2hRKgZ3', NULL, NULL, NULL, '195.190.29.128', 1467812086, 1467812086, 0, NULL, NULL),
(10115, 'jdell@immap.org', '$2y$10$kptts2PJMxJ5qsZQttP8i.dWxgBdi3F/xrJeUvPOI9d5LgikJBWY2', 'ZtFRS-9kyVjI7mzvTvUie7om1iyDKF5M', 1467885221, NULL, NULL, '195.190.29.128', 1467885184, 1467885184, 0, NULL, NULL),
(10116, 'seth.annuh@malteser-international.org', '$2y$10$oIltOo9uulHiQzmffblkee4wHvrP602Bnk/eyvQfHctNMrPUd.xju', 'zeeFnQybA47WMkimA8qI8whMGmmFWfbg', 1467885398, NULL, NULL, '195.190.29.128', 1467885380, 1467885380, 0, NULL, NULL),
(10117, 'pfitzgerald@immap.org', '$2y$10$aMKWAY23TmH60630f8wgzOxyhqQbH.58MV2R7iYCtXMr30xj0iIg2', 'OmLrGiosRldnV_lWPEdVR3MvmJvkGZ0u', 1467885465, NULL, NULL, '195.190.29.128', 1467885413, 1467885413, 0, NULL, NULL),
(10118, 'john_kipterer@wvi.org', '$2y$10$p83UAvfoO3E6COffUWP37uIhQug0X5VSL09SkNiiUU2po.GMRTHuW', 'OD4lDGUDUJsdfhU9q4_Us1ATxSN3lsS5', 1467885965, NULL, NULL, '195.190.29.128', 1467885943, 1467885943, 0, NULL, NULL),
(10119, 'snit5tekeste@gmail.com', '$2y$10$ZX51gEG3ki.j8acSzCVa9Oe0cPQsauITvPJrBn5z8Dy.IY5yE43zO', '-1tlsBxcCirlQF7MznWhdZe9r6W3RAln', 1472051553, NULL, NULL, '197.149.138.226', 1472051553, 1472051553, 0, NULL, NULL),
(10120, 'tekestes@who.int', '$2y$10$y6t6Xn4kGDA7.iBNhb.PE.MyySWMozQfx.HW0Wlc3ZIg.U8jnOgD2', 'OU8L8HTyFBosbSzostB4xUcPuxiR6Ua8', NULL, NULL, NULL, '197.149.138.226', 1472053458, 1472053458, 0, NULL, NULL),
(10121, 'brvdpu@gmail.com', '$2y$10$544nJNQZeIRED8vGDD7Ke.XXZBDeqdd7GS5/9DNz0TyzEupQv8ol6', '6EOHjpfOJZhdpSCynKFfZRyLtDVvBD3a', 1472498065, NULL, NULL, '213.127.111.245', 1472498065, 1472498065, 0, NULL, 'brvdpu'),
(10122, 'john.doe@test.com', '$2y$10$tX7LUbcEchxpEzY8yXZ1juibr6T4ay.hNVhp0VEq/7t7BAa.fJqcO', 'wUyh1cm5B3-L56KnokjE-ChLIp2DW7Iq', NULL, NULL, NULL, '192.168.37.1', 1473162572, 1473162572, 0, NULL, NULL),
(10123, 'karantili@gmail.com', '$2y$10$8d.uLx62y/s4/PGrhwb7gOUv.zUOiP.kO89VQM9ONq46XU/eP6r8a', 'tzf8dWz3cOPPSUZj8ECaANYxioho7zf6', 1479907352, NULL, NULL, '196.13.173.3', 1479907353, 1479907353, 0, NULL, NULL),
(10124, 'gaim@who.int', '$2y$10$Niy8yxUNfsI0nmN/X0t7POdRnIRbNq/1bL2PRA53juWandAfuEwnq', 'H5-7ecT8muXIlG_WuDob_nhQTzq5fW57', 1479909037, NULL, NULL, '193.220.114.211', 1479908614, 1479908614, 0, NULL, NULL),
(10125, 'elameinm@who.int', '$2y$10$2FNH.PtXDLIMUvSxDuQujuqaphYxh81NZzIOYAp/e2/TtZAn2NvP2', 'UFxT1drW3nZMLeGDwjakvpezlrAMMbE7', 1479993236, NULL, NULL, '213.74.29.18', 1479969008, 1479969008, 0, NULL, NULL),
(10126, 'dawran.safi@gmail.com', '$2y$10$t3G0Ws/GT3kVSOfzzQMwn.uL20cHp8AV0cQHkJxUmVQSa4wUhwgJe', 'aatOX7bszQy-13svg-EZKoFE0Zs2jwEP', 1479993238, NULL, NULL, '180.94.82.123', 1479977883, 1479977883, 0, NULL, NULL),
(10127, 'travelkev@hotmail.com', '$2y$10$rIUExbUd6vlWlLfSmHdtQuEsw1guuhc/39Klxxjc6Etqc..Y1XpyS', 'arX49XpVDyYoGkuiDMVqO71mAw18S7nQ', 1498463520, NULL, NULL, '77.72.149.198', 1498463520, 1498463520, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prime2_user_data`
--

CREATE TABLE `prime2_user_data` (
  `project_id` int(11) NOT NULL,
  `generator` varchar(255) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_user_data`
--

INSERT INTO `prime2_user_data` (`project_id`, `generator`, `data`) VALUES
(1, 'test', '{\"test\":{\"title\":\"Test Report\",\"description\":\"Test\\r\\n\\r\\nhiuhpiyh\",\"options\":{\"0\":\"option1\",\"1\":\"option2\"}}}'),
(12, 'ccpm', '{}'),
(15, 'cd', '{}'),
(17, 'oscar', '{}'),
(20, 'oscar', '{}'),
(21, 'cd', '{}'),
(22, 'oscar', '{}'),
(32, 'cd', '{}'),
(37, 'oscar', '{}'),
(39, 'ccpm', '{\"functions_1_1_2\":\"\",\"functions_1_1_3\":\"\",\"functions_1_2_2\":\"\",\"functions_1_2_3\":\"\",\"functions_2_1_2\":\"\",\"functions_2_1_3\":\"\",\"functions_2_2_2\":\"\",\"functions_2_2_3\":\"\",\"functions_2_3_2\":\"\",\"functions_2_3_3\":\"\",\"functions_3_1_2\":\"\",\"functions_3_1_3\":\"\",\"functions_3_2_2\":\"\",\"functions_3_2_3\":\"\",\"functions_3_3_2\":\"\",\"functions_3_3_3\":\"\",\"functions_4_1_2\":\"\",\"functions_4_1_3\":\"\",\"functions_4_2_2\":\"\",\"functions_4_2_3\":\"\",\"functions_5_1_2\":\"\",\"functions_5_1_3\":\"\",\"functions_6_1_2\":\"\",\"functions_6_1_3\":\"\",\"functions_7_1_2\":\"\",\"functions_7_1_3\":\"\"}'),
(241, 'ccpm', '{\"functions_1_1_2\":\"\",\"functions_1_1_3\":\"\",\"functions_1_2_2\":\"\",\"functions_1_2_3\":\"\",\"functions_2_1_2\":\"\",\"functions_2_1_3\":\"\",\"functions_2_2_2\":\"\",\"functions_2_2_3\":\"\",\"functions_2_3_2\":\"\",\"functions_2_3_3\":\"\",\"functions_3_1_2\":\"\",\"functions_3_1_3\":\"\",\"functions_3_2_2\":\"\",\"functions_3_2_3\":\"\",\"functions_3_3_2\":\"\",\"functions_3_3_3\":\"\",\"functions_4_1_2\":\"\",\"functions_4_1_3\":\"\",\"functions_4_2_2\":\"\",\"functions_4_2_3\":\"\",\"functions_5_1_2\":\"\",\"functions_5_1_3\":\"\",\"functions_6_1_2\":\"\",\"functions_6_1_3\":\"\",\"functions_7_1_2\":\"\",\"functions_7_1_3\":\"\"}'),
(242, 'ccpm', '{\"functions_1_1_2\":\"\",\"functions_1_1_3\":\"\",\"functions_1_2_2\":\"\",\"functions_1_2_3\":\"\",\"functions_2_1_2\":\"\",\"functions_2_1_3\":\"\",\"functions_2_2_2\":\"\",\"functions_2_2_3\":\"\",\"functions_2_3_2\":\"\",\"functions_2_3_3\":\"\",\"functions_3_1_2\":\"\",\"functions_3_1_3\":\"\",\"functions_3_2_2\":\"\",\"functions_3_2_3\":\"\",\"functions_3_3_2\":\"\",\"functions_3_3_3\":\"\",\"functions_4_1_2\":\"\",\"functions_4_1_3\":\"\",\"functions_4_2_2\":\"\",\"functions_4_2_3\":\"\",\"functions_5_1_2\":\"\",\"functions_5_1_3\":\"\",\"functions_6_1_2\":\"\",\"functions_6_1_3\":\"\",\"functions_7_1_2\":\"\",\"functions_7_1_3\":\"\"}'),
(245, 'ccpm', '{\"functions_1_1_2\":\"Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\\r\\nEstablished, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\\r\\nEstablished, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\\r\\nEstablished, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\",\"functions_1_1_3\":\"Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\",\"functions_1_2_2\":\"Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.\",\"functions_1_2_3\":\"\",\"functions_2_1_2\":\"\",\"functions_2_1_3\":\"\",\"functions_2_2_2\":\"\",\"functions_2_2_3\":\"\",\"functions_2_3_2\":\"\",\"functions_2_3_3\":\"\",\"functions_3_1_2\":\"\",\"functions_3_1_3\":\"\",\"functions_3_2_2\":\"\",\"functions_3_2_3\":\"\",\"functions_3_3_2\":\"\",\"functions_3_3_3\":\"\",\"functions_4_1_2\":\"\",\"functions_4_1_3\":\"\",\"functions_4_2_2\":\"\",\"functions_4_2_3\":\"\",\"functions_5_1_2\":\"\",\"functions_5_1_3\":\"\",\"functions_6_1_2\":\"\",\"functions_6_1_3\":\"\",\"functions_7_1_2\":\"\",\"functions_7_1_3\":\"\"}'),
(251, 'ccpm', '{\"functions_1_1_2\":\"\",\"functions_1_1_3\":\"\",\"functions_1_2_2\":\"\",\"functions_1_2_3\":\"\",\"functions_2_1_2\":\"\",\"functions_2_1_3\":\"\",\"functions_2_2_2\":\"\",\"functions_2_2_3\":\"\",\"functions_2_3_2\":\"\",\"functions_2_3_3\":\"\",\"functions_3_1_2\":\"\",\"functions_3_1_3\":\"\",\"functions_3_2_2\":\"\",\"functions_3_2_3\":\"\",\"functions_3_3_2\":\"\",\"functions_3_3_3\":\"\",\"functions_4_1_2\":\"\",\"functions_4_1_3\":\"\",\"functions_4_2_2\":\"\",\"functions_4_2_3\":\"\",\"functions_5_1_2\":\"\",\"functions_5_1_3\":\"\",\"functions_6_1_2\":\"\",\"functions_6_1_3\":\"\",\"functions_7_1_2\":\"\",\"functions_7_1_3\":\"\"}'),
(260, 'ccpm', '{\"functions_1_1_2\":\"Test\",\"functions_1_1_3\":\"Test data entry\",\"functions_1_2_2\":\"\",\"functions_1_2_3\":\"\",\"functions_2_1_2\":\"\",\"functions_2_1_3\":\"\",\"functions_2_2_2\":\"\",\"functions_2_2_3\":\"\",\"functions_2_3_2\":\"\",\"functions_2_3_3\":\"\",\"functions_3_1_2\":\"\",\"functions_3_1_3\":\"\",\"functions_3_2_2\":\"\",\"functions_3_2_3\":\"\",\"functions_3_3_2\":\"\",\"functions_3_3_3\":\"\",\"functions_4_1_2\":\"\",\"functions_4_1_3\":\"\",\"functions_4_2_2\":\"\",\"functions_4_2_3\":\"\",\"functions_5_1_2\":\"\",\"functions_5_1_3\":\"\",\"functions_6_1_2\":\"\",\"functions_6_1_3\":\"\",\"functions_7_1_2\":\"\",\"functions_7_1_3\":\"\"}'),
(286, 'cd', '{}'),
(330, 'oscar', '{}');

-- --------------------------------------------------------

--
-- Table structure for table `prime2_user_list`
--

CREATE TABLE `prime2_user_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_user_list`
--

INSERT INTO `prime2_user_list` (`id`, `user_id`, `name`) VALUES
(1, 2, 'My cool project'),
(3, 1, 'CCPM'),
(4, 1, 'CD'),
(5, 1, 'Health Cluster Gaziantep'),
(6, 10060, 'Test list for random user'),
(7, 10060, 'Try out 2'),
(8, 10108, 'PAHO EOC');

-- --------------------------------------------------------

--
-- Table structure for table `prime2_user_list_user`
--

CREATE TABLE `prime2_user_list_user` (
  `user_list_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prime2_user_list_user`
--

INSERT INTO `prime2_user_list_user` (`user_list_id`, `user_id`) VALUES
(1, 3),
(1, 4),
(1, 10062),
(3, 2),
(3, 3),
(4, 2),
(4, 3),
(5, 4),
(5, 5),
(5, 10),
(6, 2),
(6, 3),
(7, 2),
(7, 3),
(8, 10109);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prime2_auth_assignment`
--
ALTER TABLE `prime2_auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`);

--
-- Indexes for table `prime2_auth_item`
--
ALTER TABLE `prime2_auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `prime2_auth_item_child`
--
ALTER TABLE `prime2_auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `prime2_auth_rule`
--
ALTER TABLE `prime2_auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `prime2_file`
--
ALTER TABLE `prime2_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prime2_migration`
--
ALTER TABLE `prime2_migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `prime2_permission`
--
ALTER TABLE `prime2_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prime2_profile`
--
ALTER TABLE `prime2_profile`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `prime2_project`
--
ALTER TABLE `prime2_project`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `prime2_report`
--
ALTER TABLE `prime2_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`);

--
-- Indexes for table `prime2_response`
--
ALTER TABLE `prime2_response`
  ADD PRIMARY KEY (`id`),
  ADD KEY `response_survey` (`survey_id`),
  ADD KEY `response_project` (`user_id`);

--
-- Indexes for table `prime2_setting`
--
ALTER TABLE `prime2_setting`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `prime2_social_account`
--
ALTER TABLE `prime2_social_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_unique` (`provider`,`client_id`),
  ADD UNIQUE KEY `account_unique_code` (`code`),
  ADD KEY `fk_user_account` (`user_id`);

--
-- Indexes for table `prime2_source_message`
--
ALTER TABLE `prime2_source_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prime2_survey`
--
ALTER TABLE `prime2_survey`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_parent` (`parent_id`);

--
-- Indexes for table `prime2_token`
--
ALTER TABLE `prime2_token`
  ADD UNIQUE KEY `token_unique` (`user_id`,`code`,`type`);

--
-- Indexes for table `prime2_tool`
--
ALTER TABLE `prime2_tool`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `prime2_user`
--
ALTER TABLE `prime2_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_unique_email` (`email`);

--
-- Indexes for table `prime2_user_data`
--
ALTER TABLE `prime2_user_data`
  ADD UNIQUE KEY `key` (`project_id`,`generator`);

--
-- Indexes for table `prime2_user_list`
--
ALTER TABLE `prime2_user_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prime2_user_list_user`
--
ALTER TABLE `prime2_user_list_user`
  ADD UNIQUE KEY `user_list_user` (`user_list_id`,`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prime2_file`
--
ALTER TABLE `prime2_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;
--
-- AUTO_INCREMENT for table `prime2_permission`
--
ALTER TABLE `prime2_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
--
-- AUTO_INCREMENT for table `prime2_project`
--
ALTER TABLE `prime2_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=387;
--
-- AUTO_INCREMENT for table `prime2_report`
--
ALTER TABLE `prime2_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;
--
-- AUTO_INCREMENT for table `prime2_social_account`
--
ALTER TABLE `prime2_social_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `prime2_source_message`
--
ALTER TABLE `prime2_source_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prime2_survey`
--
ALTER TABLE `prime2_survey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prime2_tool`
--
ALTER TABLE `prime2_tool`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `prime2_user`
--
ALTER TABLE `prime2_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10128;
--
-- AUTO_INCREMENT for table `prime2_user_list`
--
ALTER TABLE `prime2_user_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `prime2_auth_assignment`
--
ALTER TABLE `prime2_auth_assignment`
  ADD CONSTRAINT `prime2_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `prime2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prime2_auth_item`
--
ALTER TABLE `prime2_auth_item`
  ADD CONSTRAINT `prime2_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `prime2_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `prime2_auth_item_child`
--
ALTER TABLE `prime2_auth_item_child`
  ADD CONSTRAINT `prime2_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `prime2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prime2_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `prime2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prime2_profile`
--
ALTER TABLE `prime2_profile`
  ADD CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `prime2_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prime2_report`
--
ALTER TABLE `prime2_report`
  ADD CONSTRAINT `file_id` FOREIGN KEY (`file_id`) REFERENCES `prime2_file` (`id`);

--
-- Constraints for table `prime2_response`
--
ALTER TABLE `prime2_response`
  ADD CONSTRAINT `response_project` FOREIGN KEY (`user_id`) REFERENCES `prime2_project` (`id`),
  ADD CONSTRAINT `response_survey` FOREIGN KEY (`survey_id`) REFERENCES `prime2_survey` (`id`),
  ADD CONSTRAINT `response_user` FOREIGN KEY (`user_id`) REFERENCES `prime2_user` (`id`);

--
-- Constraints for table `prime2_social_account`
--
ALTER TABLE `prime2_social_account`
  ADD CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `prime2_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prime2_survey`
--
ALTER TABLE `prime2_survey`
  ADD CONSTRAINT `survey_parent` FOREIGN KEY (`parent_id`) REFERENCES `prime2_survey` (`id`);

--
-- Constraints for table `prime2_token`
--
ALTER TABLE `prime2_token`
  ADD CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `prime2_user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
