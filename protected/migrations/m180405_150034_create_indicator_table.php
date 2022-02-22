<?php

use yii\db\Migration;

/**
 * Handles the creation of table `indicator`.
 */
class m180405_150034_create_indicator_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%indicator}}', [
            'id' => $this->primaryKey(),
            'rendering_type' => $this->string(20),
            'indicator_name' => $this->string(80),
            'descr' => $this->string(400),
            'query' => $this->text(),
            'cr_date' => $this->dateTime(),
            'up_date' => $this->dateTime(),
            'end_date' => $this->dateTime()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%indicator}}');
    }
}
