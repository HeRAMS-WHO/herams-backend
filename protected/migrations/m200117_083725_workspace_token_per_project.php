<?php

use yii\db\Migration;

/**
 * Class m200117_083725_workspace_token_per_project
 */
class m200117_083725_workspace_token_per_project extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('token', '{{%workspace}}');
        $this->createIndex('token', '{{%workspace}}', ['tool_id', 'token'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('token', '{{%workspace}}');
        $this->createIndex('token', '{{%workspace}}', ['token'], true);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200117_083725_workspace_token_per_project cannot be reverted.\n";

        return false;
    }
    */
}
