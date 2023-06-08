<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m151026_152935_add_generators_to_tool extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tool}}', 'generators', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%tool}}', 'generators');
    }
}
