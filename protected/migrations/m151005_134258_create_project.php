<?php

use yii\db\Migration;

class m151005_134258_create_project extends Migration
{
    public function up()
    {
        $this->createTable('{{%project}}', [
            'id' => \yii\db\mysql\Schema::TYPE_PK,
            'title' => \yii\db\mysql\Schema::TYPE_STRING,
            'description' => \yii\db\mysql\Schema::TYPE_TEXT,
            'data_survey_eid' => \yii\db\mysql\Schema::TYPE_INTEGER,
            'owner_id' => \yii\db\mysql\Schema::TYPE_INTEGER,
            'tool_id' => \yii\db\mysql\Schema::TYPE_INTEGER
        ]);
    }

    public function down()
    {
       $this->dropTable('project');
    }
}
