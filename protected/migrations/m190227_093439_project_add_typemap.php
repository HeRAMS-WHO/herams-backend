<?php

use yii\db\Migration;

class m190227_093439_project_add_typemap extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'typemap', $this->json()->notNull());
    }

    public function safeDown()
    {
        echo "m190227_093439_project_add_typemap cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190227_093439_project_add_typemap cannot be reverted.\n";

        return false;
    }
    */
}
