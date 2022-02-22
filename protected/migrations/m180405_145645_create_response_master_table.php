<?php

use yii\db\Migration;

/**
 * Handles the creation of table `response_master`.
 */
class m180405_145645_create_response_master_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%response_master}}', [
            'id' => $this->primaryKey(),
            'workspace_id' => $this->integer(),
            'ls_response_id' => $this->integer(),
            'submit_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'uoid' => $this->string(20),
            'token' => $this->string(40),
            'hf_type' => $this->string(40),
            'lga' => $this->string(200),
            'latitude' => $this->decimal(9, 6),
            'longitude' => $this->decimal(9, 6)
        ]);

        $this->createIndex(
            'idx-response-master-hf-type',
            '{{%response_master}}',
            'hf_type'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%response_master}}');
    }
}
