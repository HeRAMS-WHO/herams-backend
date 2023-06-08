<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

final class m221018_123050_page_drop_add_services extends Migration
{
    public function up(): bool
    {
        $this->dropColumn('{{%page}}', 'add_services');
        return true;
    }

    public function down()
    {
        $this->addColumn('{{%page}}', 'add_services', $this->boolean()->defaultValue(false)->notNull());

        return true;
    }
}
