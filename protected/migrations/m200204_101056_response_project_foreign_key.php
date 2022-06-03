<?php

use yii\db\Migration;

class m200204_101056_response_project_foreign_key extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('project', '{{%response}}');
        $this->addForeignKey('project', '{{%response}}', ['survey_id'], '{{%project}}', ['base_survey_eid'], 'CASCADE', 'RESTRICT');
    }

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
