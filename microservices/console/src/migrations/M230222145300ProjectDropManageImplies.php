<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

final class M230222145300ProjectDropManageImplies extends Migration
{
    public function up(): bool
    {
        $this->dropColumn('{{%project}}', 'manage_implies_create_hf');
        return true;
    }

    public function down(): bool
    {
        return false;
    }
}
