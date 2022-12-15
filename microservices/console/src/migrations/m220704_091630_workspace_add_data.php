<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

final class m220704_091630_workspace_add_data extends Migration
{
    public function up(): bool
    {
        $this->addColumn('{{%workspace}}', 'data', $this->json());
        return true;
    }

    public function down(): bool
    {
        $this->dropColumn('{{%workspace}}', 'data');
        return true;
    }
}
