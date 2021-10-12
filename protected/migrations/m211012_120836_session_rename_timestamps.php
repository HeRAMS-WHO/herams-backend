<?php
declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m211012_120836_session_rename_timestamps
 */
class m211012_120836_session_rename_timestamps extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%session}}', 'created', 'created_at');
        $this->renameColumn('{{%session}}', 'updated', 'updated_at');
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%session}}', 'created_at', 'created');
        $this->renameColumn('{{%session}}', 'updated_at', 'updated');
        return false;
    }
}
