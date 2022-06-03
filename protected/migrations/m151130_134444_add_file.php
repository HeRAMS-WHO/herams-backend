<?php

use yii\db\Migration;

class m151130_134444_add_file extends Migration
{
    public function up()
    {
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'mime_type' => $this->string()->notNull(),
            'data' => 'longblob NOT NULL',
        ]);
    }

    public function down()
    {
        echo "m151130_134444_add_file cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
