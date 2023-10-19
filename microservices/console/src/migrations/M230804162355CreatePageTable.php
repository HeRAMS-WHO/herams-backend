<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page}}`.
 */
class M230804162355CreatePageTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'project_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'sort' => $this->integer()->notNull(),
        ], 'charset = latin1');
        $this->addForeignKey('page_project', '{{%page}}', ['project_id'], '{{%project}}', ['id']);
        $this->addForeignKey('page_page', '{{%page}}', ['parent_id'], '{{%page}}', ['id']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('page_page', '{{%page}}');
        $this->dropForeignKey('page_project', '{{%page}}');
        $this->dropTable('{{%page}}');
    }
}
