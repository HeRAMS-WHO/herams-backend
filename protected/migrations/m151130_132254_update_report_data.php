<?php

use yii\db\Migration;

class m151130_132254_update_report_data extends Migration
{
    public function up(): bool
    {
        $this->alterColumn('{{%report}}', 'data', 'longblob NOT NULL');
        return true;
    }

    public function down(): bool
    {
        echo "m151130_132254_update_report_data cannot be reverted.\n";
        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
