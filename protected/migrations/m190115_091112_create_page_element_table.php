<?php

use yii\db\Migration;

/**
 * Handles the creation of table `page_element`.
 */
class m190115_091112_create_page_element_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'tool_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'sort' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('page_project', '{{%page}}', ['tool_id'], '{{%tool}}', ['id']);
        $this->addForeignKey('page_page', '{{%page}}', ['parent_id'], '{{%page}}', ['id']);


        $this->createTable('{{%element}}', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull(),
            'type' => $this->string(25),
            'config' => $this->json(),
            'sort' => $this->integer()->notNull(),
            'transpose' => $this->boolean()->notNull()->defaultValue(false)
        ]);
        $this->addForeignKey('element_page', '{{%element}}', ['page_id'], '{{%page}}', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%element}}');
        $this->dropTable('{{%page}}');
    }
}
