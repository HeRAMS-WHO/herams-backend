SET FOREIGN_KEY_CHECKS=0;
SET NAMES 'utf8';
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login_at`) VALUES (1, 'admin@user.com', 'INVALIDHASH', 1, 1, 1);
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login_at`) VALUES (2, 'user@user.com', 'INVALIDHASH', 1, 1, 1);
SET FOREIGN_KEY_CHECKS=1;
