<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m190315_124746_project_add_overrides extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'overrides', $this->json()->notNull());
    }

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