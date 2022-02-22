<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

class m151021_114420_create_userData extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user_data}}', [
            'project_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'generator' => Schema::TYPE_STRING . ' NOT NULL',
            'data' => Schema::TYPE_TEXT . ' NOT NULL'
        ]);

        $this->createIndex('key', '{{%user_data}}', ['project_id', 'generator'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_data}}');
    }
}
