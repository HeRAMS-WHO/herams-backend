<?php

use yii\db\Migration;

class m200429_070511_add_favorites_table extends Migration
{
    public function safeUp()
    {
        if ($this->db->getTableSchema('{{%favorite}}') === null) {
            $this->createTable('{{%favorite}}', [
                'user_id' => $this->integer()->notNull(),
                'target_class' => $this->string()->notNull(),
                'target_id' => $this->integer()->notNull(),
            ]);
        }

        $this->addPrimaryKey('favorite_primary', '{{%favorite}}', [
            'user_id',
            'target_class',
            'target_id',
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
