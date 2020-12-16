<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%session}}`.
 */
class m201216_120738_create_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%session}}', [
            'id' => $this->char(32)->append('collate ASCII_BIN PRIMARY KEY')->notNull(),
            'created' => $this->dateTime()->notNull(),
            'updated' => $this->dateTime()->notNull(),
            'expire' => $this->integer()->notNull(),
            'data' => $this->binary(),
            'user_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%session}}');
    }
}
