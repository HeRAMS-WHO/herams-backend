<?php

use yii\db\Migration;

/**
 * Class m191114_143505_page_rename_project
 */
class m191114_143505_page_rename_project extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn(\prime\models\ar\Page::tableName(), 'tool_id', 'project_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn(\prime\models\ar\Page::tableName(), 'project_id', 'tool_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191114_143505_page_rename_project cannot be reverted.\n";

        return false;
    }
    */
}
