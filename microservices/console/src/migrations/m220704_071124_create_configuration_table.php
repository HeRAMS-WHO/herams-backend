<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%configuration}}`.
 */
final class m220704_071124_create_configuration_table extends Migration
{
    public function up(): bool
    {
        $this->createTable('{{%configuration}}', [
            'key' => $this->string(100)->append('collate ascii_bin primary key')->notNull(),
            'value' => $this->json(),
        ]);
        return true;
    }

    public function down(): bool
    {
        $this->dropTable('{{%configuration}}');
        return true;
    }
}
