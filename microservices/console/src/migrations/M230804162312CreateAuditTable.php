<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%audit}}`.
 */
class M230804162312CreateAuditTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%audit}}', [
            'id' => $this->primaryKey(),
            'subject_name' => $this->string()->notNull()->append('COLLATE ascii_bin'),
            'subject_id' => $this->integer()->notNull(),
            'event' => $this->string(30)->notNull()->append('COLLATE ascii_bin'),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%audit}}');
    }
}
