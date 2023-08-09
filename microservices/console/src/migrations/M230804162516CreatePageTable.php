<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page}}`.
 */
class M230804162516CreatePageTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'project_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'sort' => $this->integer()->notNull(),
        ],'charset = latin1');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page}}');
    }
}
