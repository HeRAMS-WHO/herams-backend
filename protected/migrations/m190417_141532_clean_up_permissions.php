<?php

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
            'target' => \prime\models\ar\Workspace::class,
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
