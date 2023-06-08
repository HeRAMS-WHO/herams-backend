<?php

declare(strict_types=1);

namespace herams\console\migrations;

use herams\common\models\Page;
use yii\db\Migration;

class m191114_143505_page_rename_project extends Migration
{
    public function safeUp()
    {
        $this->renameColumn(Page::tableName(), 'tool_id', 'project_id');
    }

    public function safeDown()
    {
        $this->renameColumn(Page::tableName(), 'project_id', 'tool_id');
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
