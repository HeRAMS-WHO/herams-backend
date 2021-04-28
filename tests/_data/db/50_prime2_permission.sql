SET FOREIGN_KEY_CHECKS=0;
SET NAMES 'utf8';
INSERT INTO `prime2_permission` (`id`, `source`, `source_id`, `target`, `target_id`, `permission`, `created_at`, `created_by`) VALUES (330, 'prime\\models\\ar\\User', '1', '{builtin}', '__global__', 'admin', NULL, NULL);
INSERT INTO `prime2_permission` (`id`, `source`, `source_id`, `target`, `target_id`, `permission`, `created_at`, `created_by`) VALUES (331, 'prime\\models\\ar\\User', '5', '{builtin}', '__global__', 'admin', NULL, NULL);
SET FOREIGN_KEY_CHECKS=1;
