<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m190213_140128_rename_tool_table extends Migration
{
    public function safeUp()
    {
        $this->renameTable('{{%tool}}', '{{%project}}');
    }

    public function safeDown()
    {
        $this->renameTable('{{%project}}', '{{%tool}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190213_140128_rename_tool_table cannot be reverted.\n";

        return false;
    }
    */
}
