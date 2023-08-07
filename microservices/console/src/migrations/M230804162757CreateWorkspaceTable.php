<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%workspace}}`.
 */
class M230804162757CreateWorkspaceTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%workspace}}', [
            'id' => $this->primaryKey(),
            'i18n' => $this->json(),
            'project_id' => $this->integer(),
            'status' => $this->string(100),
            'date_of_update' => $this->date(),
            'created_date' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(),
            'last_modified_date' => $this->dateTime(),
            'last_modified_by' => $this->integer(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ], 'charset = utf8mb3');

        $this->createIndex('project_id',
            '{{%workspace}}', ['project_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%workspace}}');
    }
}
