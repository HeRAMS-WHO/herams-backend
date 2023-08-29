<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class M230804162220CreateUserTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->unique()->notNull(),
            'password_hash' => $this->string(60)->notNull(),
            'name' => $this->string()->append('collate utf8mb4_unicode_ci'),
            'language' => $this->string(10)->append('collate ascii_bin'),
            'newsletter_subscription' => $this->tinyInteger(1)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], 'charset = utf8mb3');
    }


    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
