<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m190213_140119_rename_project_table extends Migration
{
    public function safeUp()
    {
        $this->renameTable('{{%project}}', '{{%workspace}}');
    }

    public function safeDown()
    {
        $this->renameTable('{{%workspace}}', '{{%project}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190213_140119_rename_project_table cannot be reverted.\n";

        return false;
    }
    */
}
