<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%survey_response}}`.
 */
class M230804162719CreateSurveyResponseTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%survey_response}}', [
            'id' => $this->primaryKey(),
            'response_type' => $this->string(100),
            'survey_id' => $this->integer()->notNull(),
            'facility_id' => $this->integer()->notNull(),
            'date_of_update' => $this->date(),
            'data' => $this->json()->notNull(),
            'status' => "ENUM('Draft', 'Validated', 'Deleted') default 'Validated'",
            'created_date' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'last_modified_date' => $this->dateTime(),
            'last_modified_by' => $this->integer(),
        ]);
        $this->addForeignKey('fk-survey_response-facility_id-facility-id', '{{%survey_response}}', ['facility_id'], '{{%facility}}', ['id']);
        $this->addForeignKey('fk-survey_response-survey_id-survey-id', '{{%survey_response}}', ['survey_id'], '{{%survey}}', ['id']);
        $this->addForeignKey(
            'fk-survey_response-created_by-user-id',
            '{{%survey_response}}',
            ['created_by'],
            '{{%user}}',
            ['id']
        );
        $this->addForeignKey(
            'fk-survey_response-last_modified_by-user-id',
            '{{%survey_response}}',
            ['last_modified_by'],
            '{{%user}}',
            ['id']
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-survey_response-facility_id-facility-id', '{{%survey_response}}');
        $this->dropForeignKey('fk-survey_response-survey_id-survey-id', '{{%survey_response}}');
        $this->dropForeignKey('fk-survey_response-created_by-user-id', '{{%survey_response}}');
        $this->dropForeignKey('fk-survey_response-last_modified_by-user-id', '{{%survey_response}}');
        $this->dropTable('{{%survey_response}}');
    }
}
