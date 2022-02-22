<?php

use yii\db\Migration;

class m161026_083647_tool_add_explorer_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tool}}', 'explorer_regex', $this->string());
        $this->addColumn('{{%tool}}', 'explorer_name', $this->string());
    }

    public function down()
    {
        echo "m161026_083647_tool_add_explorer_fields cannot be reverted.\n";
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
