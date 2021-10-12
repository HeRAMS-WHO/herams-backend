<?php
declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m211012_150130_facility_rename_timestamps
 */
class m211012_150130_facility_rename_timestamps extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%facility}}', 'deleted', 'deleted_at');
        $this->renameColumn('{{%facility}}', 'deactivated', 'deactivated_at');
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%facility}}', 'deleted_at', 'deleted');
        $this->renameColumn('{{%facility}}', 'deactivated_at', 'deactived');
        return false;
    }
}
