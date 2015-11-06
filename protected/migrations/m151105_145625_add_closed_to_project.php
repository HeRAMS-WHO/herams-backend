<?php

use yii\db\Migration;

class m151105_145625_add_closed_to_project extends Migration
{
    public function up()
    {
        $this->addColumn(\prime\models\Project::tableName(), 'closed', $this->date());
    }

    public function down()
    {
        $this->dropColumn(\prime\models\Project::tableName(), 'closed');
    }
}
