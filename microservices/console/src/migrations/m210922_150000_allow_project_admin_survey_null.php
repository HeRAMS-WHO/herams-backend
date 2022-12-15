<?php

declare(strict_types=1);

namespace herams\console\migrations;

use herams\common\domain\survey\Survey;
use yii\db\Migration;

class m210922_150000_allow_project_admin_survey_null extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%project}}', 'admin_survey_id', $this->integer()->null());
        $this->update('{{%project}}', [
            'admin_survey_id' => null,
        ]);
        return true;
    }

    public function safeDown()
    {
        $default = Survey::find()->one();
        $this->update('{{%project}}', [
            'admin_survey_id' => $default->id,
        ]);
        $this->alterColumn('{{%project}}', 'admin_survey_id', $this->integer()->notNull()->defaultValue($default->id));
        return true;
    }
}
