<?php

declare(strict_types=1);

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%indicator}}`.
 */
final class m220704_064802_drop_indicator_table extends Migration
{
    public function up(): bool
    {
        if ($this->db->getTableSchema('{{%indicator}}')) {
            $this->dropTable('{{%indicator}}');
        }
        if ($this->db->getTableSchema('{{%indicator_option}}')) {
            $this->dropTable('{{%indicator_option}}');
        }
        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
