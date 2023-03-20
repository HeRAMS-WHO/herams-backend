<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m200429_070511_add_favorites_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%favorite}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'target_class' => $this->string()->notNull(),
            'target_id' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        echo "m200429_070511_add_favorites_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200429_070511_add_favorites_table cannot be reverted.\n";

        return false;
    }
    */
}
