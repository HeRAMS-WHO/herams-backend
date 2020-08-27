SET FOREIGN_KEY_CHECKS=0;
SET NAMES 'utf8';
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login_at`, `name`, `language`) VALUES (1, 'admin@user.com', 'INVALIDHASH', 1, 1, 1, 'admin', NULL);
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login_at`, `name`, `language`) VALUES (2, 'user@user.com', 'INVALIDHASH', 1, 1, 1, 'user', NULL);
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login_at`, `name`, `language`) VALUES (3, 'user2@user.com', 'INVALIDHASH', 1, 1, 1, 'user2', NULL);
SET FOREIGN_KEY_CHECKS=1;
