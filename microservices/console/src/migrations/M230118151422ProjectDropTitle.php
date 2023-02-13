<?php
declare(strict_types=1);
namespace herams\console\migrations;

use yii\db\Expression;
use yii\db\Migration;

/**
 * Class M230118151422ProjectDropTitle
 */
final class M230118151422ProjectDropTitle extends Migration
{
    public function safeUp(): bool
    {
        // Inject title into english.
        $this->update('{{%project}}', [
            'i18n' => new Expression("JSON_MERGE_PATCH(JSON_SET('{\"title\": {}}', '$.title.en', [[title]]), COALESCE([[i18n]], '{}'))")
        ]);
        $this->dropColumn('{{%project}}', 'title');
        return true;
    }

    public function safeDown(): bool
    {
        $this->addColumn('{{%project}}', 'title', $this->string());
        // On downgrade we dont bother removing english from i18n json field.
        $this->update('{{%project}}', ['title' => new Expression("JSON_VALUE([[i18n]], '$.title.en')")]);
        return true;
    }

}
