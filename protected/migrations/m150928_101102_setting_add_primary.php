<?php

use yii\db\Migration;

class m150928_101102_setting_add_primary extends Migration
{
    public function up()
    {
        $this->addPrimaryKey('key', '{{%setting}}', ['key']);
    }

    public function down()
    {
        echo "m150928_101102_settign_add_primary cannot be reverted.\n";

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
