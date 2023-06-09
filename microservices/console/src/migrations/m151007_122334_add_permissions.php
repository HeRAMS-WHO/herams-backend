<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;
use yii\db\mysql\Schema;

class m151007_122334_add_permissions extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%permission}}',
            [
                'id' => Schema::TYPE_PK,
                'source' => Schema::TYPE_STRING . ' NOT NULL',
                'source_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'target' => Schema::TYPE_STRING . ' NOT NULL',
                'target_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'permission' => Schema::TYPE_STRING . ' NOT NULL',
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%permission}}');
    }
}
