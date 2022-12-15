<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;
use yii\db\mysql\Schema;

class m151020_084719_add_progress_type_to_tool extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tool}}', 'progress_type', Schema::TYPE_STRING . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%tool}}', 'progress_type');
    }
}
