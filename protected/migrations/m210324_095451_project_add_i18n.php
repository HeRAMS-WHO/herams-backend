<?php

declare(strict_types=1);

use yii\db\Migration;

class m210324_095451_project_add_i18n extends Migration
{
    public function safeUp(): bool
    {
        $this->addColumn('{{%project}}', 'i18n', $this->json());
        $this->addColumn('{{%project}}', 'languages', $this->json());
        return true;
    }

    public function safeDown(): bool
    {
        $this->dropColumn('{{%project}}', 'languages');
        $this->dropColumn('{{%project}}', 'i18n');
        return true;
    }
}
