<?php

use yii\db\Migration;

/**
 * Class m260603_081527_add_is_complete_to_response
 */
class m260603_081527_add_is_complete_to_response extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%response}}', 'is_complete', $this->boolean()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%response}}', 'is_complete');
    }
}
