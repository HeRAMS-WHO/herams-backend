<?php

use yii\db\Migration;

class m180605_220711_add_aggregated_to_category_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%category}}', 'aggregated', $this->smallInteger());
    }

    public function down()
    {
        echo "m180605_220711_add_aggregated_to_category_table cannot be reverted.\n";

        return false;
    }

}
