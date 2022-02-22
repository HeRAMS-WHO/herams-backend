<?php

use yii\db\Migration;

/**
 * Handles adding charturl to table `category`.
 */
class m180410_155147_add_charturl_column_to_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%category}}', 'ws_chart_url', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%category}}', 'ws_chart_url');
    }
}
