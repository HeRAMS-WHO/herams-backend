<?php
declare(strict_types=1);

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%old_setting}}`.
 */
final class m220704_064630_drop_old_setting_table extends Migration
{
    public function up(): bool
    {
        $this->dropTable('{{%setting}}');
        return true;
    }

    public function down(): bool
    {
        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey(),
        ]);
        return true;
    }
}
