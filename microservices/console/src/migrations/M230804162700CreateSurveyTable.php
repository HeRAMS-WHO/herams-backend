<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%survey}}`.
 */
class M230804162700CreateSurveyTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%survey}}', [
            'id' => $this->primaryKey(),
            'config' => $this->json()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%survey}}');
    }
}
