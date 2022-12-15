<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m200523_093050_user_add_language extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'language', $this->string(10)->append('COLLATE ascii_bin'));
    }

    public function safeDown()
    {
        echo "m200523_093050_user_add_language cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200523_093050_user_add_language cannot be reverted.\n";

        return false;
    }
    */
}
