<?php

use yii\db\Migration;

/**
 * Class m210719_133325_project_add_admin_survey
 */
class m210719_133325_project_add_admin_survey extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create a default survey.
        $default = new \prime\models\ar\Survey();
        $default->config = [
            'pages' => [
            ]
        ];
        if (!$default->save()) {
            throw new \Exception('Failed to create default admin survey');
        }
        $this->addColumn('{{%project}}', 'admin_survey_id', $this->integer()->notNull()->defaultValue($default->id));
        $this->addForeignKey('project_admin_survey', '{{%project}}', ['admin_survey_id'], '{{%survey}}', ['id']);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('project_admin_survey', '{{%project}}');
        $this->dropColumn('{{%project}}', 'admin_survey_id');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210719_133325_project_add_admin_survey cannot be reverted.\n";

        return false;
    }
    */
}
