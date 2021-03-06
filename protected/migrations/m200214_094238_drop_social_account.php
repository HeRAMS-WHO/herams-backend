<?php

use yii\db\Migration;

/**
 * Class m200214_094238_drop_social_account
 */
class m200214_094238_drop_social_account extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%social_account}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200214_094238_drop_social_account cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200214_094238_drop_social_account cannot be reverted.\n";

        return false;
    }
    */
}
