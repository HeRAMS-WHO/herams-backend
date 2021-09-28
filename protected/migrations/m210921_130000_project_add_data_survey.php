<?php
declare(strict_types=1);

use prime\models\ar\Survey;
use yii\db\Migration;

/**
 * Class m210921_130000_project_add_data_survey
 */
class m210921_130000_project_add_data_survey extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'data_survey_id', $this->integer()->null()->after('admin_survey_id'));
        $this->addForeignKey('project_data_survey', '{{%project}}', ['data_survey_id'], '{{%survey}}', ['id']);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('project_data_survey', '{{%project}}');
        $this->dropColumn('{{%project}}', 'data_survey_id');
        return true;
    }
}
