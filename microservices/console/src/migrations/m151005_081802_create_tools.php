<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;
use yii\db\mysql\Schema;

class m151005_081802_create_tools extends Migration
{
    public function up()
    {
        $this->createTable('{{%tool}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING,
            'image' => Schema::TYPE_STRING,
            'description' => Schema::TYPE_TEXT,
            'intake_survey_eid' => Schema::TYPE_INTEGER,
            'base_survey_eid' => Schema::TYPE_INTEGER,
        ]);
        $this->createIndex('title', '{{%tool}}', ['title'], true);
    }

    public function down()
    {
        $this->dropTable('{{%tool}}');
    }
}
