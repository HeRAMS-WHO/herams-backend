<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%facility}}`.
 */
class M230804162428CreateFacilityTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%facility}}', [
            'id' => $this->primaryKey(),
            'workspace_id' => $this->integer()->notNull(),
            'situation_data' => $this->json(),
            'admin_data' => $this->json(),
            'latitude' => $this->decimal(10, 8),
            'longitude' => $this->decimal(11, 8),
            'can_receive_situation_update' => $this->boolean()->notNull()->defaultValue(true),
            'status' => "ENUM('Active', 'Closed', 'Deleted') DEFAULT 'Active' NOT NULL",
            'date_of_update' => $this->date(),
            'created_date' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'created_by' => $this->integer()->notNull(),
            'last_modified_date' => $this->dateTime()->notNull(),
            'last_modified_by' => $this->integer()->notNull(),
            'tier' => "ENUM('Primary', 'Secondary', 'Tertiary', 'Other')",
        ]);
        $this->addForeignKey('workspace_id', '{{%facility}}', ['workspace_id'], '{{%workspace}}', ['id']);
        $this->addForeignKey(
            'fk-facility-created_by-user-id',
            '{{%facility}}',
            ['created_by'],
            '{{%user}}',
            ['id']
        );
        $this->addForeignKey(
            'fk-facility-last_modified_by-user-id',
            '{{%facility}}',
            ['last_modified_by'],
            '{{%user}}',
            ['id']
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('workspace_id', '{{%facility}}');
        $this->dropForeignKey('fk-facility-created_by-user-id', '{{%facility}}');
        $this->dropForeignKey('fk-facility-last_modified_by-user-id', '{{%facility}}');
        $this->dropTable('{{%facility}}');
    }
}
