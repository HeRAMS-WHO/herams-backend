<?php

use prime\models\ar\Project;
use yii\db\Migration;

class m190221_083716_project_status extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'status', $this->integer()->defaultValue(Project::STATUS_ONGOING)->notNull());
    }

    public function safeDown()
    {
        echo "m190221_083716_project_status cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_083716_project_status cannot be reverted.\n";

        return false;
    }
    */
}
