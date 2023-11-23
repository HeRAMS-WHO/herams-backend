<?php

namespace herams\console\migrations;

use yii\db\Migration;

class M230913153825RenameAndAddFieldsInUserTable extends Migration
{
    public function safeUp()
    {
        $this->renameColumn('{{%user}}', 'created_at', 'created_date');
        $this->renameColumn('{{%user}}', 'updated_at', 'last_modified_date');
        $this->addColumn('{{%user}}', 'created_by', $this->integer());
        $this->addColumn('{{%user}}', 'last_modified_by', $this->integer());
        $this->addColumn('{{%user}}', 'last_login_date', $this->dateTime()->null());
        $this->addForeignKey(
            'fk-user-created_by',
            '{{%user}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-user-last_modified_by',
            '{{%user}}',
            'last_modified_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        // Revert foreign key constraints
        $this->dropForeignKey('fk-user-created_by', '{{%user}}');
        $this->dropForeignKey('fk-user-last_modified_by', '{{%user}}');

        $this->renameColumn('{{%user}}', 'created_date', 'created_at');
        $this->renameColumn('{{%user}}', 'last_modified_date', 'updated_at');

        // Revert added columns
        $this->dropColumn('{{%user}}', 'created_by');
        $this->dropColumn('{{%user}}', 'last_modified_by');
        $this->dropColumn('{{%user}}', 'last_login_date');
    }
}
