<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%key}}`.
 */
class m191203_140540_CreateKeyTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%key}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'hash' => $this->string(255)->notNull()->append('COLLATE ascii_bin'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%key}}');
    }
}
