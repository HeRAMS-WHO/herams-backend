<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%survey_response}}`.
 */
class M230804162719CreateSurveyResponseTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%survey_response}}', [
            'id' => $this->primaryKey(),
            'response_type' => $this->string(100),
            'survey_id' => $this->integer()->notNull(),
            'date_of_update' => $this->date(),
            'facility_id' => $this->integer()->notNull(),
            'data' => $this->json()->notNull(),
            'status' => "ENUM('Draft', 'Validated', 'Deleted') default 'Validated'",
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'last_modified_date' => $this->dateTime(),
            'last_modified_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%survey_response}}');
    }
}
