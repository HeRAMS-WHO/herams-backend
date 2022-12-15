<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m190415_135506_drop_user_list extends Migration
{
    public function safeUp()
    {
        $this->dropTable('{{%user_list_user}}');
        $this->dropTable('{{%user_list}}');
        $this->dropTable('{{%user_data}}');
    }

    public function safeDown()
    {
        echo "m190415_135506_drop_user_list cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190415_135506_drop_user_list cannot be reverted.\n";

        return false;
    }
    */
}
