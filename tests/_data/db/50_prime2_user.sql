SET FOREIGN_KEY_CHECKS=0;
SET NAMES 'utf8';
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login_at`, `name`, `language`, `newsletter_subscription`) VALUES (1, 'test-admin@user.com', 'INVALIDHASH', 1, 1, 1, 'test-admin', NULL, 1);
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login_at`, `name`, `language`, `newsletter_subscription`) VALUES (2, 'test-user@user.com', 'INVALIDHASH', 1, 1, 1, 'test-user', NULL, 1);
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login_at`, `name`, `language`, `newsletter_subscription`) VALUES (3, 'test-user2@user.com', 'INVALIDHASH', 1, 1, 1, 'test-user2', NULL, 1);
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login_at`, `name`, `language`, `newsletter_subscription`) VALUES (4, 'admin@user.com', '$2y$13$sPDjKPQhfkqfNGD1vkuJtuedaBHOJkTQJsf5DEG49TG.HU8T6Fwua', 1, 1, 1, 'Admin', NULL, 1);
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login_at`, `name`, `language`, `newsletter_subscription`) VALUES (5, 'user@user.com', '$2y$13$sPDjKPQhfkqfNGD1vkuJtuedaBHOJkTQJsf5DEG49TG.HU8T6Fwua', 1, 1, 1, 'User', NULL, 1);
SET FOREIGN_KEY_CHECKS=1;
