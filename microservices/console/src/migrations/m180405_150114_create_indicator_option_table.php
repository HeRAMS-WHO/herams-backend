<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `indicator_option`.
 */
class m180405_150114_create_indicator_option_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%indicator_option}}', [
            'id' => $this->primaryKey(),
            'indicator_id' => $this->integer(),
            'option_code' => $this->string(20),
            'option_label' => $this->string(200),
            'option_color' => $this->string(40),
        ]);

        $this->createIndex(
            'idx-indicator-option-indicator-id',
            '{{%indicator_option}}',
            'indicator_id'
        );

        $this->createIndex(
            'idx-indicator-option-option-code',
            '{{%indicator_option}}',
            'option_code'
        );
    }

    public function down()
    {
        $this->dropTable('{{%indicator_option}}');
    }
}
