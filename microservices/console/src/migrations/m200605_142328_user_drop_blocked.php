<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m200605_142328_user_drop_blocked extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%user}}', 'blocked_at');
    }

    public function safeDown()
    {
        echo "m200605_142328_user_drop_blocked cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200605_142328_user_drop_blocked cannot be reverted.\n";

        return false;
    }
    */
}
