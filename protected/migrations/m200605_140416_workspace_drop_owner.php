<?php

use yii\db\Migration;

/**
 * Class m200605_140416_workspace_drop_owner
 */
class m200605_140416_workspace_drop_owner extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%workspace}}', 'owner_id');
    }

    /**
     * {@inheritdoc}
     */
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
