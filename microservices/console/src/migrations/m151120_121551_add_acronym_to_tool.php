<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m151120_121551_add_acronym_to_tool extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tool}}', 'acronym', $this->string()->notNull());
    }

    public function down()
    {
        echo "m151120_121551_add_acronym_to_tool cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
