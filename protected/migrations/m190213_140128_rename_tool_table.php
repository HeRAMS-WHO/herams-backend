<?php

use yii\db\Migration;

/**
 * Class m190213_140128_rename_tool_table
 */
class m190213_140128_rename_tool_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('{{%tool}}', '{{%project}}');
    }

    /**
     * {@inheritdoc}
     */
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
