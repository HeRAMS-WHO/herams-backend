<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%workspace}}`.
 */
class M230804162427CreateWorkspaceTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%workspace}}', [
            'id' => $this->primaryKey(),
            'i18n' => $this->json()->null(),
            'project_id' => $this->integer()->notNull(),
            'status' => "ENUM('Active', 'Deleted') NOT NULL",
            'date_of_update' => $this->date()->null(),
            'created_date' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'created_by' => $this->integer()->notNull(),
            'last_modified_date' => $this->dateTime()->notNull(),
            'last_modified_by' => $this->integer()->notNull(),
        ], 'charset = utf8mb3');

        $this->createIndex('project_id',
            '{{%workspace}}', ['project_id']);
        $this->addForeignKey('project_workspace', '{{%workspace}}', ['project_id'], '{{%project}}', ['id'], 'CASCADE');
        $this->addForeignKey('fk-project_workspace-created_by-user-id',
            '{{%workspace}}', ['created_by'], '{{%user}}', ['id']);
        $this->addForeignKey('fk-project_workspace-last_modified_by-user-id',
            '{{%workspace}}', ['last_modified_by'], '{{%user}}', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('project_workspace', '{{%workspace}}');
        $this->dropForeignKey('fk-project_workspace-created_by-user-id','{{%workspace}}');
        $this->dropForeignKey('fk-project_workspace-last_modified_by-user-id','{{%workspace}}');
        $this->dropTable('{{%workspace}}');
    }
}
