<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;
use yii\db\mysql\Schema;

class m151005_134258_create_project extends Migration
{
    public function up()
    {
        $this->createTable('{{%project}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING,
            'description' => Schema::TYPE_TEXT,
            'data_survey_eid' => Schema::TYPE_INTEGER,
            'owner_id' => Schema::TYPE_INTEGER,
            'tool_id' => Schema::TYPE_INTEGER,
        ]);
    }

    public function down()
    {
        $this->dropTable('project');
    }
}
