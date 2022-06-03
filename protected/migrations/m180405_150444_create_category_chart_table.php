<?php

use yii\db\Migration;

/**
 * Handles the creation of table `indicator_scope`.
 */
class m180405_150444_create_category_chart_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%category_chart}}', [
            'category_id' => $this->integer(),
            'indicator_id' => $this->integer(),
            'display_order' => $this->integer(),
        ]);

        $this->createIndex(
            'idx_category_indicator',
            '{{%category_chart}}',
            'category_id,indicator_id',
            true
        );
    }

    public function down()
    {
        $this->dropTable('{{%category_chart}}');
    }
}
