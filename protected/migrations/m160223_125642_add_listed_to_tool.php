<?php

use yii\db\Migration;

class m160223_125642_add_listed_to_tool extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tool}}', 'hidden', $this->boolean()->defaultValue(0));
    }

    public function down()
    {
        return false;
    }
}
