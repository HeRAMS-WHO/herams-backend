<?php

use yii\db\Migration;

/**
 * Class m200429_082155_project_use_float
 */
class m200429_082155_project_use_float extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%project}}', 'latitude', $this->float()->null()->defaultValue(null));
        $this->alterColumn('{{%project}}', 'longitude', $this->float()->null()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200429_082155_project_use_float cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200429_082155_project_use_float cannot be reverted.\n";

        return false;
    }
    */
}
