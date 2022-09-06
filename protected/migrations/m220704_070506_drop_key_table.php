<?php

declare(strict_types=1);
use yii\db\Migration;

/**
 * Handles the dropping of table `{{%key}}`.
 */
final class m220704_070506_drop_key_table extends Migration
{
    public function up(): bool
    {
        if ($this->db->getTableSchema('{{%key}}')) {
            $this->dropTable('{{%key}}');
        }
        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
