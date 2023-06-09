<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

final class m221006_091619_project_drop_typemap extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up(): bool
    {
        $this->dropColumn('{{%project}}', 'typemap');
        return true;
    }

    public function down()
    {
        $this->addColumn('{{%project}}', 'typemap', $this->json()->notNull());
        return true;
    }
}
