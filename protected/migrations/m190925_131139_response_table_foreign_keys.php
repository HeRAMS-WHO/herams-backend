<?php

use yii\db\Migration;

/**
 * Class m190925_131139_response_table_foreign_keys
 */
class m190925_131139_response_table_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('workspace', '{{%response}}');
        // Deleting a workspace deletes the data.
        $this->addForeignKey('workspace', '{{%response}}', ['workspace_id'], '{{%workspace}}', ['id'], 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190925_131139_response_table_foreign_keys cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190925_131139_response_table_foreign_keys cannot be reverted.\n";

        return false;
    }
    */
}
