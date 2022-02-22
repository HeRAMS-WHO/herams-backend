<?php

declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m211012_145235_workspace_rename_timestamps
 */
class m211012_145235_workspace_rename_timestamps extends Migration
{
    public function safeUp(): bool
    {
        $this->renameColumn('{{%workspace}}', 'created', 'created_at');
        $this->renameColumn('{{%workspace}}', 'closed', 'closed_at');
        return true;
    }

    public function safeDown(): bool
    {
        $this->renameColumn('{{%workspace}}', 'created_at', 'created');
        $this->renameColumn('{{%workspace}}', 'closed_at', 'closed');
        return false;
    }
}
