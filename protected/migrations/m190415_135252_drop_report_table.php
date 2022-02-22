<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%report}}`.
 */
class m190415_135252_drop_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%report}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
