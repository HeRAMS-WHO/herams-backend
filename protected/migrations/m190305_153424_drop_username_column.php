<?php

use yii\db\Migration;

/**
 * Class m190305_153424_drop_username_column
 */
class m190305_153424_drop_username_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%user}}', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190305_153424_drop_username_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190305_153424_drop_username_column cannot be reverted.\n";

        return false;
    }
    */
}
