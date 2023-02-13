<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Expression;
use yii\db\Migration;

/**
 * Class M230207085428WorkspaceDropTitle
 */
final class M230207085428WorkspaceDropTitle extends Migration
{
    public function safeUp(): bool
    {
        // Inject title into english.
        $this->update('{{%workspace}}', [
            'i18n' => new Expression("JSON_MERGE_PATCH(JSON_SET('{\"title\": {}}', '$.title.en', [[title]]), COALESCE([[i18n]], '{}'))")
        ]);
        $this->dropColumn('{{%workspace}}', 'title');
        return true;
    }

    public function safeDown(): bool
    {
        return false;
    }
}
