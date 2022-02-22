<?php

declare(strict_types=1);

use prime\models\ar\Survey;
use yii\db\Migration;

/**
 * Class m210922_150000_allow_project_admin_survey_null
 */
class m210922_150000_allow_project_admin_survey_null extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%project}}', 'admin_survey_id', $this->integer()->null());
        $this->update('{{%project}}', ['admin_survey_id' => null]);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $default = Survey::find()->one();
        $this->update('{{%project}}', ['admin_survey_id' => $default->id]);
        $this->alterColumn('{{%project}}', 'admin_survey_id', $this->integer()->notNull()->defaultValue($default->id));
        return true;
    }
}
