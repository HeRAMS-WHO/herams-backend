<?php

use yii\db\Migration;

class m160114_120158_profile_defaults extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%profile}}', 'first_name', $this->string()->notNull()->defaultValue(''));
        $this->alterColumn('{{%profile}}', 'last_name', $this->string()->notNull()->defaultValue(''));
        $this->alterColumn('{{%profile}}', 'organization', $this->string()->notNull()->defaultValue(''));
        $this->alterColumn('{{%profile}}', 'office', $this->string()->notNull()->defaultValue(''));
        $this->alterColumn('{{%profile}}', 'country', $this->string()->notNull()->defaultValue(''));
    }

    public function down()
    {
        echo "m160114_130158_profile_defaults cannot be reverted.\n";

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
