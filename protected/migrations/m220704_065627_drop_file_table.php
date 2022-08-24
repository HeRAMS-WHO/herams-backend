<?php
declare(strict_types=1);
use yii\db\Migration;

/**
 * Handles the dropping of table `{{%file}}`.
 */
final class m220704_065627_drop_file_table extends Migration
{
    public function up(): bool
    {
        if ($this->db->getTableSchema('{{%file}}')) {
            $this->dropTable('{{%file}}');
        }
        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
