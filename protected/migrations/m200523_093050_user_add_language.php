<?php

use yii\db\Migration;

/**
 * Class m200523_093050_user_add_language
 */
class m200523_093050_user_add_language extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'language', $this->string(10)->append('COLLATE ascii_bin'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200523_093050_user_add_language cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200523_093050_user_add_language cannot be reverted.\n";

        return false;
    }
    */
}
