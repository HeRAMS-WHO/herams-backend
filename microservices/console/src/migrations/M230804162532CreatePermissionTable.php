<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%permission}}`.
 */
class M230804162532CreatePermissionTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%permission}}', [
            'id' => $this->primaryKey(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
            'target' => $this->string()->notNull(),
            'target_id' => $this->string()->notNull(),
            'permission' => $this->string()->notNull(),
        ], 'charset = utf8mb3');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%permission}}');
    }
}