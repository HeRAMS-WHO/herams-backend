<?php

declare(strict_types=1);

namespace herams\console\migrations;

use herams\common\helpers\ConfigurationProvider;
use yii\db\Migration;

final class M230222152715ProjectCountryNotNull extends Migration
{
    public function safeUp(): bool
    {
        $this->update('{{%project}}', [
            'country' => ConfigurationProvider::COUNTRY_UNSPECIFIED,
        ], [
            'country' => null,
        ]);
        return true;
    }

    public function safeDown(): bool
    {
        return false;
    }
}
