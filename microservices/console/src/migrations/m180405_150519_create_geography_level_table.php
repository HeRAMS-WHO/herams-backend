<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `geography_level`.
 */
class m180405_150519_create_geography_level_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%geography_level}}', [
            'geol_id' => $this->primaryKey(),
            'project_id' => $this->integer(),
            'geol_name' => $this->string(100),
            'geo_level' => $this->integer(),
            'geol_official_name' => $this->string(100),
            'cr_date' => $this->dateTime(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%geography_level}}');
    }
}
