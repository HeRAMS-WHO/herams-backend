<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m190828_131450_element_add_size extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%element}}', 'width', $this->tinyInteger()->notNull()->defaultValue(1)->unsigned());
        $this->addColumn('{{%element}}', 'height', $this->tinyInteger()->notNull()->defaultValue(1)->unsigned());
    }

    public function safeDown()
    {
        echo "m190828_131450_element_add_size cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190828_131450_element_add_size cannot be reverted.\n";

        return false;
    }
    */
}
