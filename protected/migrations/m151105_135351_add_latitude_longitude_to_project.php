<?php

use yii\db\Migration;

class m151105_135351_add_latitude_longitude_to_project extends Migration
{
    public function up()
    {
        $this->addColumn(\prime\models\Project::tableName(), 'latitude', $this->decimal(12,8));
        $this->addColumn(\prime\models\Project::tableName(), 'longitude', $this->decimal(12,8));
    }

    public function down()
    {
        $this->dropColumn(\prime\models\Project::tableName(), 'latitude');
        $this->dropColumn(\prime\models\Project::tableName(), 'longitude');
    }
}
