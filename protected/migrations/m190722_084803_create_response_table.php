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
            'workspace_id' => $this->integer()->notNull(),
            'data' => $this->json(),
            'date' => $this->date()->notNull(),
            'hf_id' => $this->string(20)->append('COLLATE ascii_bin')->notNull(),
            'last_updated' => $this->dateTime()->defaultExpression('NOW()')
        ]);

        $this->addPrimaryKey('response', '{{%response}}', ['id', 'survey_id']);
        $this->createIndex('survey', '{{%project}}', ['base_survey_eid'], true);
        $this->addForeignKey('project', '{{%response}}', ['survey_id'], '{{%project}}', ['base_survey_eid']);
        $this->addForeignKey('workspace', '{{%response}}', ['workspace_id'], '{{%workspace}}', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%response}}');
    }
}
