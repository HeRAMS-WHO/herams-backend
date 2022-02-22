<?php

use yii\db\Migration;

class m151026_112253_create_user_lists extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user_list}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull()
        ]);

        $this->createTable('{{%user_list_user}}', [
            'user_list_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull()
        ]);

        $this->createIndex(
            'user_list_user',
            '{{%user_list_user}}',
            [
                'user_list_id',
                'user_id'
            ],
            true
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_list}}');
        $this->dropTable('{{%user_list_user}}');
    }
}
