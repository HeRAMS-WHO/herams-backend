<?php

namespace herams\console\migrations;

use yii\db\Migration;

final class M230222145439ProjectAddDashboard extends Migration
{
    public function up(): bool
    {
        $this->addColumn('{{%project}}', 'dashboard_url', $this->string()->append('COLLATE utf8mb4_bin')->defaultValue(null));
        return true;
    }

    public function down(): bool
    {
        return false;
    }

}
