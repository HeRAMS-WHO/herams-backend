<?php

namespace herams\console\migrations;


use yii\db\Migration;

/**
 * Handles the creation of table `{{%permission}}`.
 */
class M230913153819CreatePermissionsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%permissions}}', [
            'code' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull()->unique(),
            'parent' => $this->string()->check("parent IN ('Admin', 'Homepage', 'Global', 'Project', 'Workspace', 'HSDU', 'HSDU responses')"),
            'created_date' => $this->dateTime(),
            'created_by' => $this->integer(),
            'last_modified_date' => $this->dateTime(),
            'last_modified_by' => $this->integer(),
        ]);

        // Add foreign key constraint to the created_by column
        $this->addForeignKey(
            'fk-permissions-created_by',
            '{{%permissions}}',
            'created_by',
            '{{%user}}',
            'id'
        );

        // Add foreign key constraint to the last_modified_by column
        $this->addForeignKey(
            'fk-permissions-last_modified_by',
            '{{%permissions}}',
            'last_modified_by',
            '{{%user}}',
            'id'
        );
        $this->addPrimaryKey('pk-permissions-code', '{{%permissions}}', 'code');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key constraints first
        $this->dropForeignKey('fk-permissions-created_by', '{{%permissions}}');
        $this->dropForeignKey('fk-permissions-last_modified_by', '{{%permissions}}');

        // Then, drop the table
        $this->dropTable('{{%permissions}}');
    }
}
