<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m150921_110227_create_settings_table extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%setting}}',
            [
                'key' => 'string(32) NOT NULL',
                'value' => 'TEXT NOT NULL',
            ]
        );
    }

    public function down()
    {
        echo "m150921_110227_create_settings_table cannot be reverted.\n";

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
