<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles adding layout to table `category`.
 */
class m180410_155232_add_layout_column_to_category_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%category}}', 'layout', $this->string(255));
    }

    public function down()
    {
        $this->dropColumn('{{%category}}', 'layout');
    }
}
