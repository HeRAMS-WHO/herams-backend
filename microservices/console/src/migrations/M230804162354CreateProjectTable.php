<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project}}`.
 */
class M230804162354CreateProjectTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'latitude' => $this->float(),
            'longitude' => $this->float(),
            'visibility' => "ENUM('Hidden', 'Public', 'Private')",
            'country' => $this->char(3)->append('COLLATE ascii_bin'),
            'i18n' => $this->json(),
            'languages' => $this->json(),
            'admin_survey_id' => $this->integer()->null(),
            'data_survey_id' => $this->integer()->null(),
            'primary_language' => $this->string(10)->notNull()->append('COLLATE ascii_bin')->defaultValue('en'),
            'dashboard_url' => $this->string()->append('COLLATE utf8mb4_bin'),
            'status' => "ENUM('Active', 'Deleted') DEFAULT 'ACTIVE'",
            'date_of_update' => $this->date()->null(),
            'created_date' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'created_by' => $this->integer()->notNull(),
            'last_modified_date' => $this->dateTime()->notNull(),
            'last_modified_by' => $this->integer()->notNull()
        ], 'charset = utf8mb3');
        $this->addForeignKey('project_admin_survey', '{{%project}}', ['admin_survey_id'], '{{%survey}}', ['id']);
        $this->addForeignKey('project_data_survey', '{{%project}}', ['data_survey_id'], '{{%survey}}', ['id']);
        $this->addForeignKey('fk-project-created_by-user-id',
            '{{%project}}', ['created_by'], '{{%user}}', ['id']);
        $this->addForeignKey('fk-project-last_modified_by-user-id',
            '{{%project}}', ['last_modified_by'], '{{%user}}', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('project_admin_survey', '{{%project}}');
        $this->dropForeignKey('project_data_survey', '{{%project}}');
        $this->dropForeignKey('fk-project-created_by-user-id','{{%project}}');
        $this->dropForeignKey('fk-project-last_modified_by-user-id','{{%project}}');
        $this->dropTable('{{%project}}');
    }
}
