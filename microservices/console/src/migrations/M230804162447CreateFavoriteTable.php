<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favorite}}`.
 */
class M230804162447CreateFavoriteTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%favorite}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'target_class' => $this->string()->notNull(),
            'target_id' => $this->integer()->notNull(),
        ], 'charset = latin1');
    }


    public function safeDown()
    {
        $this->dropTable('{{%favorite}}');
    }
}
