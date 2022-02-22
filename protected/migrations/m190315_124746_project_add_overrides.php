<?php

use yii\db\Migration;

/**
 * Class m190315_124746_project_add_overrides
 */
class m190315_124746_project_add_overrides extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'overrides', $this->json()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190315_124746_project_add_overrides cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190315_124746_project_add_overrides cannot be reverted.\n";

        return false;
    }
    */
}
