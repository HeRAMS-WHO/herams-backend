<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m180319_094924_create_category_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer(),
            'parent_id' => $this->integer(),
            'name' => $this->string(255),
            'json_template' => $this->text(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%category}}');
    }
}
