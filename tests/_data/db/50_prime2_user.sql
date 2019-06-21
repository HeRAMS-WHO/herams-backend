SET FOREIGN_KEY_CHECKS=0;
SET NAMES 'utf8';
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `auth_key`, `confirmed_at`, `unconfirmed_email`, `blocked_at`, `registration_ip`, `created_at`, `updated_at`, `flags`, `last_login_at`, `access_token`) VALUES (1, 'admin@user.com', 'INVALIDHASH', 'TOKEN1', 1, NULL, NULL, '127.0.0.1', 1542270514, 1542270514, 0, NULL, NULL);
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `auth_key`, `confirmed_at`, `unconfirmed_email`, `blocked_at`, `registration_ip`, `created_at`, `updated_at`, `flags`, `last_login_at`, `access_token`) VALUES (2, 'user@user.com', 'INVALIDHASH', 'TOKEN2', 1, NULL, NULL, '127.0.0.1', 1542270514, 1542270514, 0, NULL, NULL);
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `auth_key`, `confirmed_at`, `unconfirmed_email`, `blocked_at`, `registration_ip`, `created_at`, `updated_at`, `flags`, `last_login_at`, `access_token`) VALUES (3, 'root@user.com', '$2y$10$VIG.EaTKD0iis7WQxeprIub/4bh13hnb0rxs7zNV6hZMMS4G7uqNm', 'TOKEN3', 1, NULL, NULL, '127.0.0.1', 1542270514, 1542270514, 0, NULL, NULL);
SET FOREIGN_KEY_CHECKS=1;
