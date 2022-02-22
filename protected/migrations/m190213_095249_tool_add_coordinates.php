<?php

use yii\db\Migration;

/**
 * Class m190213_095249_tool_add_coordinates
 */
class m190213_095249_tool_add_coordinates extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tool}}', 'latitude', $this->decimal(9, 6));
        $this->addColumn('{{%tool}}', 'longitude', $this->decimal(9, 6));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190213_095249_tool_add_coordinates cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190213_095249_tool_add_coordinates cannot be reverted.\n";

        return false;
    }
    */
}
