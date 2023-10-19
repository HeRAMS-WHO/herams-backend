<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%role}}`.
 */
class M230913153820CreateRoleTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%role}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'scope' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'project_id' => $this->integer(),
            'created_date' => $this->dateTime(),
            'created_by' => $this->integer(),
            'last_modified_date' => $this->dateTime(),
            'last_modified_by' => $this->integer(),
        ]);

        // Add foreign key constraint to the project_id column
        $this->addForeignKey(
            'fk-role-project_id',
            '{{%role}}',
            'project_id',
            '{{%project}}', // Replace with the correct table name
            'ID'
        );

        // Add foreign key constraint to the created_by column
        $this->addForeignKey(
            'fk-role-created_by',
            '{{%role}}',
            'created_by',
            '{{%user}}',
            'ID'
        );

        // Add foreign key constraint to the last_modified_by column
        $this->addForeignKey(
            'fk-role-last_modified_by',
            '{{%role}}',
            'last_modified_by',
            '{{%user}}',
            'ID'
        );
    }


    public function safeDown()
    {
        // Drop foreign key constraints first
        $this->dropForeignKey('fk-role-project_id', '{{%role}}');
        $this->dropForeignKey('fk-role-created_by', '{{%role}}');
        $this->dropForeignKey('fk-role-last_modified_by', '{{%role}}');

        // Then, drop the table
        $this->dropTable('{{%role}}');
    }
}
