<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m211019_113352_rename_response_to_response_for_limesurvey extends Migration
{
    public function safeUp(): bool
    {
        $this->renameTable('{{%response}}', '{{%response_for_limesurvey}}');
        return true;
    }

    public function safeDown()
    {
        $this->renameTable('{{%response_for_limesurvey}}', '{{%response}}');
        return true;
    }
}
