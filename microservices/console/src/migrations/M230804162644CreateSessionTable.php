<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%session}}`.
 */
class M230804162644CreateSessionTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%session}}', [
            'id' => $this->char(32)->append('collate ASCII_BIN PRIMARY KEY')->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'expire' => $this->integer()->notNull(),
            'data' => $this->binary(),
            'user_id' => $this->integer(),
        ]);
    }


    public function safeDown()
    {
        $this->dropTable('{{%session}}');
    }
}
