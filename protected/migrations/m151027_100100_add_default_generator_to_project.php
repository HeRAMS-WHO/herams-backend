<?php

use yii\db\Migration;

class m151027_100100_add_default_generator_to_project extends Migration
{
    public function up()
    {
        $this->addColumn('{{%project}}', 'default_generator', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%project}}', 'default_generator');
    }
}
