<?php

use yii\db\Migration;

/**
 * Class m200120_142141_permission_id_string
 */
class m200120_142141_permission_id_string extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%permission}}', 'source_id', $this->string()->notNull());
        $this->alterColumn('{{%permission}}', 'target_id', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200120_142141_permission_id_string cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200120_142141_permission_id_string cannot be reverted.\n";

        return false;
    }
    */
}
