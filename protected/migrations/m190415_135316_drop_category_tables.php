<?php

use yii\db\Migration;

/**
 * Class m190415_135316_drop_category_tables
 */
class m190415_135316_drop_category_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%category_chart}}');
        $this->dropTable('{{%category}}');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190415_135316_drop_category_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190415_135316_drop_category_tables cannot be reverted.\n";

        return false;
    }
    */
}
