<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `geography`.
 */
class m180405_150531_create_geography_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%geography}}', [
            'geo_id' => $this->primaryKey(),
            'geo_name' => $this->string(255),
            'geo_level' => $this->integer(),
            'parent_id' => $this->integer(),
            'locale_code' => $this->string(255),
            'cr_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%geography}}');
    }
}