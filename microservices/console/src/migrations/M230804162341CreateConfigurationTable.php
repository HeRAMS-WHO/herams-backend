<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%configuration}}`.
 */
class M230804162341CreateConfigurationTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%configuration}}', [
            'key' => $this->string(100)->append('collate ascii_bin primary key')->notNull(),
            'value' => $this->json(),
        ]);
    }


    public function safeDown()
    {
        $this->dropTable('{{%configuration}}');
    }
}
