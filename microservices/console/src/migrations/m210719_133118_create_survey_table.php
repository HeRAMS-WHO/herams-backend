<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%survey}}`.
 */
class m210719_133118_create_survey_table extends Migration
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
