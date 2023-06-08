<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m151105_145625_add_closed_to_project extends Migration
{
    public function up()
    {
        $this->addColumn('{{%project}}', 'closed', $this->date());
    }

    public function down()
    {
        $this->dropColumn('{{%project}}', 'closed');
    }
}
