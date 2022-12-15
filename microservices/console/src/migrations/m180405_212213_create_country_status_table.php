<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `country_status`.
 */
class m180405_212213_create_country_status_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%country_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(400),
            'status_id' => $this->integer(),
            'geodata' => $this->text(),
            'stats' => $this->text(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%country_status}}');
    }
}
