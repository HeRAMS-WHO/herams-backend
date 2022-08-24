<?php
declare(strict_types=1);
use yii\db\Migration;

/**
 * Handles the dropping of table `{{%country_status}}`.
 */
final class m220704_065209_drop_country_status_table extends Migration
{
    public function up(): bool
    {
        if ($this->db->getTableSchema('{{%country_status}}')) {
            $this->dropTable('{{%country_status}}');
        }
        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
