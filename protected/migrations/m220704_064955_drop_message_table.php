<?php
declare(strict_types=1);
use yii\db\Migration;

/**
 * Handles the dropping of table `{{%message}}`.
 */
final class m220704_064955_drop_message_table extends Migration
{
    public function up(): bool
    {
        if ($this->db->getTableSchema('{{%message}}')) {
            $this->dropTable('{{%message}}');
        }
        if ($this->db->getTableSchema('{{%source_message}}')) {
            $this->dropTable('{{%source_message}}');
        }
        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
