<?php
declare(strict_types=1);
use yii\db\Migration;

/**
 * Class m220704_070133_drop_auth_tables
 */
final class m220704_070133_drop_auth_tables extends Migration
{
    public function up(): bool
    {
        foreach(['auth_assignment', 'auth_item_child', 'auth_item', 'auth_rule'] as $table) {
            if ($this->db->getTableSchema("{{%$table}}")) {
                $this->dropTable("{{%$table}}");
            }
        }

        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
