<?php

use yii\db\Migration;

/**
 * Class m200429_070511_add_favorites_table
 */
class m200429_070511_add_favorites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favorite}}', [
            'user_id' => $this->integer()->notNull(),
            'target_class' => $this->string()->notNull(),
            'target_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('primary', '{{%favorite}}', [
            'user_id',
            'target_class',
            'target_id'
        ]);
    }

    /**
     * {@inheritdoc}
     */
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
