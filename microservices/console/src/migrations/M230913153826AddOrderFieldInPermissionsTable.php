<?php

namespace herams\console\migrations;

use yii\db\Migration;

class M230913153826AddOrderFieldInPermissionsTable extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            '{{%permissions}}',
            'order',
            $this->integer()->notNull()->defaultValue(0)->after('name')
        );
    }

    public function down()
    {
        $this->dropColumn('{{%permissions}}', 'order');
    }
}
