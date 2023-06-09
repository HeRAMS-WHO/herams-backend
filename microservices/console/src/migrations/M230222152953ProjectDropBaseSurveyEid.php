<?php

declare(strict_types=1);
namespace herams\console\migrations;

use yii\db\Migration;

final class M230222152953ProjectDropBaseSurveyEid extends Migration
{
    public function up(): bool
    {
        $this->dropColumn('{{%project}}', 'base_survey_eid');
        return true;
    }

    public function down(): bool
    {
        return false;
    }
}
