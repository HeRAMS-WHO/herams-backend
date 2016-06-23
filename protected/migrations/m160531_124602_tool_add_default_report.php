<?php

use yii\db\Migration;

class m160531_124602_tool_add_default_report extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tool}}', 'default_generator', $this->string());
    }

    public function down()
    {
        echo "m160531_124602_tool_add_default_report cannot be reverted.\n";

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
