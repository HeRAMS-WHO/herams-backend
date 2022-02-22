<?php

use yii\db\Migration;

class m170913_152617_add_favorite_project_to_profile extends Migration
{
    public function up()
    {
        $this->addColumn('prime2_profile', 'favorite_project_id', $this->integer());
    }

    public function down()
    {
        echo "m170913_152617_add_favorite_project_to_profile cannot be reverted.\n";

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
