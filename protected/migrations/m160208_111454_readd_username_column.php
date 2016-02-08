<?php

use yii\db\Migration;

class m160208_111454_readd_username_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'username', $this->string(1));
    }

    public function down()
    {
        echo "m160208_111454_readd_username_column cannot be reverted.\n";

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
