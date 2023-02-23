<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Class M230223141639ProjectDropHidden
 */
class M230223141639ProjectDropHidden extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M230223141639ProjectDropHidden cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M230223141639ProjectDropHidden cannot be reverted.\n";

        return false;
    }
    */
}
