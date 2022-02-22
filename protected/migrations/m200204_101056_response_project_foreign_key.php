<?php

use yii\db\Migration;

/**
 * Class m200204_101056_response_project_foreign_key
 */
class m200204_101056_response_project_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('project', '{{%response}}');
        $this->addForeignKey('project', '{{%response}}', ['survey_id'], '{{%project}}', ['base_survey_eid'], 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200204_101056_response_project_foreign_key cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200204_101056_response_project_foreign_key cannot be reverted.\n";

        return false;
    }
    */
}
