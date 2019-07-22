<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%response}}`.
 */
class m190722_084803_create_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%response}}', [
            'id' => $this->integer()->notNull(),
            'survey_id' => $this->integer()->notNull(),
            'token' => $this->char(16)->append('COLLATE ascii_bin')->notNull(),
            'data' => $this->json(),
            'last_updated' => $this->dateTime()->defaultExpression('NOW()')
        ]);
        $this->addPrimaryKey('response', '{{%response}}', ['id', 'survey_id']);
        $this->createIndex('token', '{{%response}}', ['survey_id', 'token']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%response}}');
    }
}
