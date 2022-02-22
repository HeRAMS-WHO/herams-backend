<?php

use yii\db\Migration;

/**
 * Class m190213_140119_rename_project_table
 */
class m190213_140119_rename_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('{{%project}}', '{{%workspace}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('{{%workspace}}', '{{%project}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190213_140119_rename_project_table cannot be reverted.\n";

        return false;
    }
    */
}
