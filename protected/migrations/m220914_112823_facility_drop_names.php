<?php

declare(strict_types=1);

use yii\db\Migration;

final class m220914_112823_facility_drop_names extends Migration
{
    public function up(): bool
    {
        $this->dropColumn('{{%facility}}', 'name');
        $this->dropColumn('{{%facility}}', 'alternative_name');
        $this->dropColumn('{{%facility}}', 'i18n');
        return true;
    }

    public function down(): bool
    {
        $this->addColumn('{{%facility}}', 'name', $this->string());
        $this->addColumn('{{%facility}}', 'alternative_name', $this->string());
        $this->addColumn('{{%facility}}', 'i18n', $this->json());
        return true;
    }
}
