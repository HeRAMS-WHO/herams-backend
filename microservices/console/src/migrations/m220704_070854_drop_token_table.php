<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%token}}`.
 */
final class m220704_070854_drop_token_table extends Migration
{
    public function up(): bool
    {
        if ($this->db->getTableSchema('{{%token}}')) {
            $this->dropTable('{{%token}}');
        }
        return true;
    }

    public function down(): bool
    {
        return true;
    }
}
