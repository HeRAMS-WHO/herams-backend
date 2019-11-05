SET FOREIGN_KEY_CHECKS=0;
SET NAMES 'utf8';
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `blocked_at`, `created_at`, `updated_at`, `last_login_at`, `name`) VALUES (1, 'admin@user.com', 'INVALIDHASH', NULL, 1542270514, 1571998475, NULL, 'Test Admin');
INSERT INTO `prime2_user` (`id`, `email`, `password_hash`, `blocked_at`, `created_at`, `updated_at`, `last_login_at`, `name`) VALUES (2, 'root@user.com', '$2y$10$cPDmIPGvVSAS2xSj8zpNQesCtZUDkHuvK5uAS3azvtv8vnvI/q2oK', NULL, 1542270514, 1571998475, NULL, 'Root Iser');
SET FOREIGN_KEY_CHECKS=1;
