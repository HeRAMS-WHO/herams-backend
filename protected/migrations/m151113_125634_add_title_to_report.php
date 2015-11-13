<?php

use yii\db\Migration;

class m151113_125634_add_title_to_report extends Migration
{
    public function up()
    {
        $this->addColumn('{{%report}}', 'title', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%report}}', 'title');
    }
}
