<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%report}}`.
 */
class m190415_135252_drop_report_table extends Migration
{
    public function safeUp()
    {
        $this->dropTable('{{%report}}');
    }

    public function safeDown()
    {
        return false;
    }
}
