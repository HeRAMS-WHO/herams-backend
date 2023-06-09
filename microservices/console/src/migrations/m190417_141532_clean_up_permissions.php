<?php

declare(strict_types=1);

namespace herams\console\migrations;

use herams\common\models\Workspace;
use yii\db\Migration;

class m190417_141532_clean_up_permissions extends Migration
{
    public function safeUp()
    {
        $this->delete('{{%permission}}', [
            'permission' => 'instantiate',
        ]);

        $this->delete('{{%permission}}', [
            'permission' => 'share',
        ]);
        $this->delete('{{%permission}}', [
            'permission' => 'read',
            'target' => Workspace::class,
        ]);
    }

    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190417_141532_clean_up_permissions cannot be reverted.\n";

        return false;
    }
    */
}
