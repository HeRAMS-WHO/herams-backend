<?php

use yii\db\Migration;

/**
 * Handles adding layout to table `category`.
 */
class m180410_155232_add_layout_column_to_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%category}}', 'layout', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%category}}', 'layout');
    }
}
