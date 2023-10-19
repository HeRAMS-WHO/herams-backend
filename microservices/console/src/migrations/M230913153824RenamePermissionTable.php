<?php

namespace herams\console\migrations;

use yii\db\Migration;


class M230913153824RenamePermissionTable extends Migration
{
    public function safeUp()
    {
        // Rename the 'permission' table to 'permission_old'
        $this->renameTable('{{%permission}}', '{{%permission_old}}');
    }


    public function safeDown()
    {
        // Rename the 'permission_old' table back to 'permission' if needed
        $this->renameTable('{{%permission_old}}', '{{%permission}}');
    }
}
