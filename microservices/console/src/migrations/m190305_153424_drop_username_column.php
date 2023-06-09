<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m190305_153424_drop_username_column extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%user}}', 'username');
    }

    public function safeDown()
    {
        echo "m190305_153424_drop_username_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190305_153424_drop_username_column cannot be reverted.\n";

        return false;
    }
    */
}
