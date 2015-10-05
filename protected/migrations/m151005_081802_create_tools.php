<?php

use yii\db\Migration;

class m151005_081802_create_tools extends Migration
{
    public function up()
    {
        $this->createTable('tool', [
            'id' => \yii\db\mysql\Schema::TYPE_PK,
            'title' => \yii\db\mysql\Schema::TYPE_STRING,
            'image' => \yii\db\mysql\Schema::TYPE_STRING,
            'description' => \yii\db\mysql\Schema::TYPE_TEXT,
            'intake_survey_eid' => \yii\db\mysql\Schema::TYPE_INTEGER,
            'base_survey_eid' => \yii\db\mysql\Schema::TYPE_INTEGER
        ]);
        $this->createIndex('title', 'tool', ['title'], true);
    }

    public function down()
    {
        $this->dropTable('tool');
    }
}
