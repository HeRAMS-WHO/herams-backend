<?php

use yii\db\Migration;

class m151026_090031_add_thumbnail_to_tool extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tool}}', 'thumbnail', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%tool}}', 'thumbnail');
    }
}
