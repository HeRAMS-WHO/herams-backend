<?php

use yii\db\Migration;

class m151105_135351_add_latitude_longitude_to_project extends Migration
{
    public function up()
    {
        $this->addColumn('{{%project}}', 'latitude', $this->decimal(12, 8));
        $this->addColumn('{{%project}}', 'longitude', $this->decimal(12, 8));
    }

    public function down()
    {
        $this->dropColumn('{{%project}}', 'latitude');
        $this->dropColumn('{{%project}}', 'longitude');
    }
}
