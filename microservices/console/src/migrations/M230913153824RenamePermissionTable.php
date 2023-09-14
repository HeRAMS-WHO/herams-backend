<?php
namespace herams\console\migrations;
use yii\db\Migration;

/**
 * Class mYYYYMMDD_HHMMSS_rename_permission_table
 */
class M230913153824RenamePermissionTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Rename the 'permission' table to 'permission_old'
        $this->renameTable('{{%permission}}', '{{%permission_old}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Rename the 'permission_old' table back to 'permission' if needed
        $this->renameTable('{{%permission_old}}', '{{%permission}}');
    }
}