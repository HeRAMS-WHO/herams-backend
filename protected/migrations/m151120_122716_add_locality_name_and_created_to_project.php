<?php

use yii\db\Migration;

class m151120_122716_add_locality_name_and_created_to_project extends Migration
{
    public function up()
    {
        $this->addColumn('{{%project}}', 'locality_name', $this->string());
        $this->addColumn('{{%project}}', 'created', $this->dateTime()->notNull() . ' DEFAULT CURRENT_TIMESTAMP');
    }

    public function down()
    {
        echo "m151120_122716_add_locality_name_to_project cannot be reverted.\n";
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
