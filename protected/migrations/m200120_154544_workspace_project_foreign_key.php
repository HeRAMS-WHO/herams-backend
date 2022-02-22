<?php

use yii\db\Migration;

/**
 * Class m200120_154544_workspace_project_foreign_key
 */
class m200120_154544_workspace_project_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('project_workspace', '{{%workspace}}', ['tool_id'], '{{%project}}', ['id'], 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('project_workspace', '{{%workspace}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200120_154544_workspace_project_foreign_key cannot be reverted.\n";

        return false;
    }
    */
}
