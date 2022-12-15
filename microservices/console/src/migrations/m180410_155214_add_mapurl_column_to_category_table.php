<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles adding mapurl to table `category`.
 */
class m180410_155214_add_mapurl_column_to_category_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%category}}', 'ws_map_url', $this->text());
    }

    public function down()
    {
        $this->dropColumn('{{%category}}', 'ws_map_url');
    }
}
