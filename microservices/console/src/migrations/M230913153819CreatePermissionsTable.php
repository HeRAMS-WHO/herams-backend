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
        $this->execute("CREATE TABLE `prime2_permissions` (
                `code` VARCHAR(255) NOT NULL UNIQUE,
                `name` VARCHAR(255) NOT NULL UNIQUE,
                `parent` VARCHAR(255) CHECK (parent IN ('Admin', 'Homepage', 'Global', 'Project', 'Workspace', 'HSDU', 'HSDU responses')),
                `created_date` DATETIME,
                `created_by` INT(11),
                `last_modified_date` DATETIME,
                `last_modified_by` INT(11),
                PRIMARY KEY (`code`)
            )
        ");

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
