<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m210921_130000_project_add_data_survey extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'data_survey_id', $this->integer()->null()->after('admin_survey_id'));
        $this->addForeignKey('project_data_survey', '{{%project}}', ['data_survey_id'], '{{%survey}}', ['id']);
        return true;
    }

    public function safeDown()
    {
        $this->dropForeignKey('project_data_survey', '{{%project}}');
        $this->dropColumn('{{%project}}', 'data_survey_id');
        return true;
    }
}