<?php
declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m211012_135333_permission_use_auditlog
 */
class m211012_135333_permission_use_auditlog extends Migration
{
    public function safeUp(): bool
    {
        $this->dropForeignKey('fk-permission-created_by-user-id', '{{%permission}}');
        $this->dropColumn('{{%permission}}', 'created_by');
        $this->dropColumn('{{%permission}}', 'created_at');
        return true;
    }

    public function safeDown(): bool
    {
        $this->addColumn('{{%permission}}', 'created_by', $this->integer());
        $this->addColumn('{{%permission}}', 'created_at', $this->integer());
        return true;
    }
}
