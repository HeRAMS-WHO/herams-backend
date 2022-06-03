<?php

use yii\db\Migration;

/**
 * Handles the creation of table `response_data`.
 */
class m180405_145759_create_response_data_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%response_data}}', [
            'id' => $this->primaryKey(),
            'response_id' => $this->integer(),
            'question_code' => $this->string(100),
            'response_value' => $this->string(200),
        ]);

        $this->createIndex(
            'idx-response-data-response_id',
            '{{%response_data}}',
            'response_id'
        );

        $this->createIndex(
            'idx-response-data-question-code',
            '{{%response_data}}',
            'question_code'
        );

        $this->addForeignKey(
            'fk-response_data-response_id',
            '{{%response_data}}',
            'response_id',
            '{{%response_master}}',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%response_data}}');
    }
}
