<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m200117_083725_workspace_token_per_project extends Migration
{
    public function safeUp()
    {
        $this->dropIndex('token', '{{%workspace}}');
        $this->createIndex('token', '{{%workspace}}', ['tool_id', 'token'], true);
    }

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
