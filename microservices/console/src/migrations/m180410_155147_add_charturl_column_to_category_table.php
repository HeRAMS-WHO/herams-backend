<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles adding charturl to table `category`.
 */
class m180410_155147_add_charturl_column_to_category_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%category}}', 'ws_chart_url', $this->text());
    }

    public function down()
    {
        $this->dropColumn('{{%category}}', 'ws_chart_url');
    }
}
