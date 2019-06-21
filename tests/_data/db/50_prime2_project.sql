SET FOREIGN_KEY_CHECKS=0;
SET NAMES 'utf8';
INSERT INTO `prime2_project` (`id`, `title`, `base_survey_eid`, `hidden`, `latitude`, `longitude`, `status`, `typemap`, `overrides`) VALUES (1, 'Nigeria', 742358, 0, NULL, NULL, 0, NULL, NULL);
INSERT INTO `prime2_project` (`id`, `title`, `base_survey_eid`, `hidden`, `latitude`, `longitude`, `status`, `typemap`, `overrides`) VALUES (2, 'Mozambique', 831874, 0, NULL, NULL, 0, CAST('{\"\":\"Other\",\"A1\":\"Primary\",\"A2\":\"Primary\",\"A3\":\"Secondary\",\"A4\":\"Secondary\",\"A5\":\"Tertiary\",\"A6\":\"Tertiary\"}' AS JSON), CAST('{\"typeCounts\":null,\"facilityCount\":null,\"contributorCount\":null}' AS JSON));
SET FOREIGN_KEY_CHECKS=1;
