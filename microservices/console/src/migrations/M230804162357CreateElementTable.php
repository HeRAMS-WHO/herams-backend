<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%element}}`.
 */
class M230804162357CreateElementTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%element}}', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'type' => $this->string(25),
            'config' => $this->json(),
            'sort' => $this->integer()->notNull(),
            'transpose' => $this->boolean()->notNull()->defaultValue(false),
            'width' => $this->tinyInteger()->unsigned()->notNull()->defaultValue('1'),
            'height' => $this->tinyInteger()->unsigned()->notNull()->defaultValue('1'),
        ], 'charset = latin1');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%element}}');
    }
}
