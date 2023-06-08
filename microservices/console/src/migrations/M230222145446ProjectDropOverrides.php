<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class M230222145446ProjectDropOverrides extends Migration
{
    public function up(): bool
    {
        $this->dropColumn('{{%project}}', 'overrides');
        return true;
    }

    public function down(): bool
    {
        return false;
    }
}
