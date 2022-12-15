<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%event_log}}`.
 */
final class m220704_070642_drop_event_log_table extends Migration
{
    public function up(): bool
    {
        if ($this->db->getTableSchema('{{%event_log}}')) {
            $this->dropTable('{{%event_log}}');
        }
        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
