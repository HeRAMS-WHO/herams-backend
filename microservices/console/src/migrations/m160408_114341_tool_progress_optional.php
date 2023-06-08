<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m160408_114341_tool_progress_optional extends Migration
{
    public function up()
    {
        // Allow null.
        $this->alterColumn('{{%tool}}', 'progress_type', $this->string(50)->defaultValue(null));
    }

    public function down()
    {
        echo "m160408_114341_tool_progress_optional cannot be reverted.\n";

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
