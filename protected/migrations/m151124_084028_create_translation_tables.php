<?php

use yii\db\Migration;

class m151124_084028_create_translation_tables extends Migration
{
    public function up()
    {
        $this->createTable('{{%source_message}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(32),
            'message' => $this->text(),
        ]);

        $this->createTable('{{%message}}', [
            'id' => $this->integer(),
            'language' => $this->string(16),
            'translation' => $this->text(),
        ]);

        $this->addPrimaryKey('primaryKey', '{{%message}}', ['id', 'language']);
        $this->addForeignKey('fk_message_source_message', '{{%message}}', ['id'], '{{%source_message}}', ['id'], 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        echo "m151124_084028_create_translation_tables cannot be reverted.\n";

        return false;
    }
}
