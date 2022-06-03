<?php

use yii\db\Migration;

class m200605_140416_workspace_drop_owner extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%workspace}}', 'owner_id');
    }

    public function safeDown()
    {
        echo "m200605_140416_workspace_drop_owner cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200605_140416_workspace_drop_owner cannot be reverted.\n";

        return false;
    }
    */
}
