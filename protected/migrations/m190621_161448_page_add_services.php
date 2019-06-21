<?php

use yii\db\Migration;

/**
 * Class m190621_161448_page_add_services
 */
class m190621_161448_page_add_services extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%page}}', 'add_services', $this->boolean()->defaultValue(false)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190621_161448_page_add_services cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190621_161448_page_add_services cannot be reverted.\n";

        return false;
    }
    */
}
