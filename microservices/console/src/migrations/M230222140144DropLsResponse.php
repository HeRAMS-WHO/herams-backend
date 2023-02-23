<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

final class M230222140144DropLsResponse extends Migration
{
    public function up(): bool
    {
        $this->dropTable('{{%response_for_limesurvey}}');
        return true;
    }

    public function down(): bool
    {
        return false;
    }

}
