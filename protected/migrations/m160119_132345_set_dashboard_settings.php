<?php

use yii\db\Migration;

class m160119_132345_set_dashboard_settings extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        return (\prime\models\ar\Setting::set('countryGradesSurvey', 486496)
            && \prime\models\ar\Setting::set('eventGradesSurvey', 473297)
            && \prime\models\ar\Setting::set('healthClusterMappingSurvey', 259688)
        );
    }

    public function safeDown()
    {
    }

}
