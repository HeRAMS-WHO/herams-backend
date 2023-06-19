ALTER TABLE prime2_survey_response CHANGE survey_date date_of_update DATE NULL DEFAULT NULL;
ALTER TABLE prime2_survey_response CHANGE latest_update_date last_modified_date DATETIME;
ALTER TABLE prime2_survey_response CHANGE latest_update_by last_modified_by INT;
ALTER TABLE prime2_survey_response CHANGE survey_date date_of_update DATE NULL DEFAULT NULL;
ALTER TABLE prime2_workspace CHANGE latest_update_date last_modified_date DATETIME, CHANGE latest_update_by last_modified_by INT, CHANGE latest_update_by last_modified_by INT, CHANGE latest_survey_date date_of_update DATE;
ALTER TABLE `prime2_workspace`
    CHANGE COLUMN `latest_survey_date` `date_of_update` DATE NULL DEFAULT NULL AFTER `status`,
    CHANGE COLUMN `latest_update_date` `last_modified_date` DATETIME NULL DEFAULT NULL AFTER `created_by`,
    CHANGE COLUMN `latest_update_by` `last_modified_by` INT(10) NULL DEFAULT NULL AFTER `last_modified_date`;
ALTER TABLE `prime2_facility`
    CHANGE COLUMN `latest_date` `date_of_update` DATE NULL DEFAULT NULL AFTER `use_in_dashboarding`;
