<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

final class M230222145333ProjectDropStatus extends Migration
{
    public function up(): bool
    {
        $this->dropColumn('{{%project}}', 'status');
        return true;
    }

    public function down(): bool
    {
        return false;
    }
}
