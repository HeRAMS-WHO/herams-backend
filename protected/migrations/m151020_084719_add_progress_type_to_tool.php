<?php

use yii\db\Migration;

class m151020_084719_add_progress_type_to_tool extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tool}}', 'progress_type', \yii\db\mysql\Schema::TYPE_STRING . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%tool}}', 'progress_type');
    }
}
