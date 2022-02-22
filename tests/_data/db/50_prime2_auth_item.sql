SET FOREIGN_KEY_CHECKS=0;
SET NAMES 'utf8';
INSERT INTO `prime2_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('admin', 1, 'Default admin role, users with this role can do anything.', NULL, NULL, 1542277720, 1542277720);
INSERT INTO `prime2_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('tools', 2, 'Allow a user to manage tool configuration / creation.', NULL, NULL, 1542277720, 1542277720);
SET FOREIGN_KEY_CHECKS=1;
