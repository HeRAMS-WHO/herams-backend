<?php

use yii\db\Migration;

/**
 * Class m231019_133128_workspace_table_add_fields_for_cronjob
 */
class m231019_133128_workspace_table_add_fields_for_cronjob extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%workspace}}', 'last_sync_date', $this->dateTime());
        $this->addColumn('{{%workspace}}', 'last_sync_by', $this->string());
        $this->addColumn('{{%workspace}}', 'sync_status', $this->string());
        $this->addColumn('{{%workspace}}', 'sync_error', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%workspace}}', 'last_sync_date');
        $this->dropColumn('{{%workspace}}', 'last_sync_by');
        $this->dropColumn('{{%workspace}}', 'sync_status');
        $this->dropColumn('{{%workspace}}', 'sync_error');
    }
}
