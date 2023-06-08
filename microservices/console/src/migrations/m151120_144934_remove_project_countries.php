<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m151120_144934_remove_project_countries extends Migration
{
    public function up()
    {
        $this->dropTable('{{%project_country}}');
        $this->addColumn('{{%project}}', 'country_iso_3', $this->string(3)->notNull());
    }

    public function down()
    {
        echo "m151120_144934_remove_project_countries cannot be reverted.\n";

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
