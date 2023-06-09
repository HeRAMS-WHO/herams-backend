<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m200611_085414_project_add_country extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%project}}', 'country', $this->char(3)->append('COLLATE ascii_bin'));
    }

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
