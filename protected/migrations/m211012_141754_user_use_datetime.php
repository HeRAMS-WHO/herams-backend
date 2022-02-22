<?php

declare(strict_types=1);

use prime\helpers\MigrationHelper;
use yii\db\Migration;

/**
 * Class m211012_141754_user_use_datetime
 */
class m211012_141754_user_use_datetime extends Migration
{
    public function safeUp(): bool
    {
        $helper = new MigrationHelper($this);
        $helper->changeColumnFromIntToDatetime('{{%user}}', 'created_at');
        $helper->changeColumnFromIntToDatetime('{{%user}}', 'updated_at');

        return true;
    }

    public function safeDown(): bool
    {
        $helper = new MigrationHelper($this);
        $helper->changeColumnFromDatetimeToInt('{{%user}}', 'created_at');
        $helper->changeColumnFromDatetimeToInt('{{%user}}', 'updated_at');

        return true;
    }
}
