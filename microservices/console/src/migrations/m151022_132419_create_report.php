<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m151022_132419_create_report extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%report}}',
            [
                'id' => $this->primaryKey(),
                'data' => $this->text(),
                'mime_type' => $this->string(),
                'email' => $this->string()->notNull(),
                'user_id' => $this->integer()->notNull(),
                'name' => $this->string()->notNull(),
                'time' => $this->dateTime()->notNull(),
                'published' => $this->dateTime()->notNull(),
                'user_data' => $this->text()->notNull(),
                'project_id' => $this->integer(),
                'generator' => $this->string(),
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%report}}');
    }
}
