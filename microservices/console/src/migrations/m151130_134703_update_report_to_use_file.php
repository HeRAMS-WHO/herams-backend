<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m151130_134703_update_report_to_use_file extends Migration
{
    public function up()
    {
        $this->truncateTable('{{%report}}');
        $this->dropColumn('{{%report}}', 'data');
        $this->dropColumn('{{%report}}', 'mime_type');
        $this->addColumn('{{%report}}', 'file_id', $this->integer()->notNull());

        $this->addForeignKey('file_id', '{{%report}}', ['file_id'], '{{%file}}', ['id']);
    }

    public function down()
    {
        echo "m151130_134703_update_report_to_use_file cannot be reverted.\n";

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
