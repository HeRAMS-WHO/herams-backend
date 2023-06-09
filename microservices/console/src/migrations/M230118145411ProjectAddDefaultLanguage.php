<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;


final class M230118145411ProjectAddDefaultLanguage extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up(): bool
    {
        $this->addColumn('{{%project}}', 'primary_language', $this->string(10)->append('collate ascii_bin')->defaultValue('en')->notNull());
        return true;
    }

    public function down(): bool
    {
        $this->dropColumn('{{%project', 'primary_language');
        return false;
    }
}
