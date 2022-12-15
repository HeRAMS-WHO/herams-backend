<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m190415_135402_drop_geography_tables extends Migration
{
    public function safeUp()
    {
        $this->dropTable('{{%geography_level}}');
        $this->dropTable('{{%geography}}');
    }

    public function safeDown()
    {
        echo "m190415_135402_drop_geography_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190415_135402_drop_geography_tables cannot be reverted.\n";

        return false;
    }
    */
}
