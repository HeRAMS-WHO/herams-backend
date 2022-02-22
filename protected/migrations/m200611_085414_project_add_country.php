<?php

use yii\db\Migration;

/**
 * Class m200611_085414_project_add_country
 */
class m200611_085414_project_add_country extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'country', $this->char(3)->append('COLLATE ascii_bin'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200611_085414_project_add_country cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200611_085414_project_add_country cannot be reverted.\n";

        return false;
    }
    */
}
