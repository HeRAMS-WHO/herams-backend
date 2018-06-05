<?php

use yii\db\Migration;

/**
 * Handles adding mapurl to table `category`.
 */
class m180410_155214_add_mapurl_column_to_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%category}}', 'ws_map_url', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%category}}', 'ws_map_url');
    }
}
