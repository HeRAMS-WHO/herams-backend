<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m210610_122240_response_add_auto_increment_key extends Migration
{
    public function safeUp()
    {
        $this->createIndex('oldprimary', '{{%response}}', ['id', 'survey_id']);
        $this->dropPrimaryKey('PRIMARY', '{{%response}}');

        $this->addColumn('{{%response}}', 'auto_increment_id', $this->integer()->unique()->notNull()->append('AUTO_INCREMENT'));
        $this->addPrimaryKey('PRIMARY', '{{%response}}', 'auto_increment_id');
        $this->dropIndex('auto_increment_id', '{{%response}}');

        $this->alterColumn('{{%response}}', 'id', $this->integer()->null());
        $this->alterColumn('{{%response}}', 'survey_id', $this->integer()->null());
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
        echo "m210610_122240_response_add_auto_increment_key cannot be reverted.\n";

        return false;
    }
    */
}
