<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%survey}}`.
 */
class M230804162353CreateSurveyTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%survey}}', [
            'id' => $this->primaryKey(),
            'config' => $this->json()->notNull(),
        ]);
    }


    public function safeDown()
    {
        $this->dropTable('{{%survey}}');
    }
}
