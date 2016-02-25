<?php

use yii\db\Migration;

class m160225_142422_update_percentage_progress_to_CCPM_progress extends Migration
{
    public function up()
    {
        $this->update('{{%tool}}', ['progress_type' => 'ccpmPercentage'], ['progress_type' => 'percentage']);
    }

    public function down()
    {
        echo "m160225_142422_update_percentage_progress_to_CCPM_progress cannot be reverted.\n";

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
