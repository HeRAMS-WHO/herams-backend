<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m211005_093054_add_i18n_to_workspace_and_rename_tool_id extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%workspace}}', 'i18n', $this->json()->after('title'));
        $this->renameColumn('{{%workspace}}', 'tool_id', 'project_id');
        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%workspace}}', 'i18n');
        $this->renameColumn('{{%workspace}}', 'project_id', 'tool_id');
    }
}
