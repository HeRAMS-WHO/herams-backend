<?php

use yii\db\Migration;

/**
 * Class m200205_123138_project_visibility
 */
class m200205_123138_project_visibility extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'visibility', $this->string(10)->append('COLLATE ascii_bin')->notNull()->defaultValue('public'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200205_123138_project_visibility cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200205_123138_project_visibility cannot be reverted.\n";

        return false;
    }
    */
}
