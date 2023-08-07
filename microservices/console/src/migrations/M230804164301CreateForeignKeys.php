<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Class M230804164301CreateForeignKeys
 */
class M230804164301CreateForeignKeys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-access_request-responded_by-user-id',
            '{{%access_request}}', ['responded_by'], '{{%user}}',
            ['id'], 'SET NULL', 'CASCADE');
        $this->addForeignKey('element_page', '{{%element}}', ['page_id'], '{{%page}}', ['id']);
        $this->addForeignKey('workspace_id', '{{%facility}}', ['workspace_id'], '{{%workspace}}', ['id']);
        $this->addForeignKey('page_project', '{{%page}}', ['project_id'], '{{%project}}', ['id']);
        $this->addForeignKey('page_page', '{{%page}}', ['parent_id'], '{{%page}}', ['id']);
        $this->addForeignKey('project_admin_survey', '{{%project}}', ['admin_survey_id'], '{{%survey}}', ['id']);
        $this->addForeignKey('project_data_survey', '{{%project}}', ['data_survey_id'], '{{%survey}}', ['id']);
        $this->addForeignKey('fk-survey_response-facility_id-facility-id', '{{%survey_response}}', ['facility_id'], '{{%facility}}', ['id']);
        $this->addForeignKey('fk-survey_response-survey_id-survey-id', '{{%survey_response}}', ['survey_id'], '{{%survey}}', ['id']);
        $this->addForeignKey('project_workspace', '{{%workspace}}', ['project_id'], '{{%project}}', ['id'], 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-access_request-responded_by-user-id', '{{%access_request}}');
        $this->dropForeignKey('element_page', '{{%element}}');
        $this->dropForeignKey('page_project', '{{%facility}}');
        $this->dropForeignKey('page_page', '{{%page}}');
        $this->dropForeignKey('project_admin_survey', '{{%project}}');
        $this->dropForeignKey('project_data_survey', '{{%project}}');
        $this->dropForeignKey('fk-survey_response-facility_id-facility-id', '{{%survey_response}}');
        $this->dropForeignKey('fk-survey_response-survey_id-survey-id', '{{%survey_response}}');
        $this->dropForeignKey('project_workspace', '{{%workspace}}');
    }
}
