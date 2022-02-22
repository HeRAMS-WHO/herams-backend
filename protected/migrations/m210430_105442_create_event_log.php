<?php

use yii\db\Migration;

/**
 * Class m210430_105442_create_event_log
 */
class m210430_105442_create_event_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event_log}}', [
            'id' => $this->primaryKey(),
            'source_name' => $this->string(50)->notNull()->append('COLLATE ascii_bin'),
            'source_id' => $this->integer()->notNull(),
            'target_name' => $this->string(50)->notNull()->append('COLLATE ascii_bin'),
            'target_id' => $this->integer()->notNull(),
            'event' => $this->string(50)->notNull()->append('COLLATE ascii_bin'),
            'data' => $this->json(),
            'created' => $this->dateTime()->defaultExpression('now()'),
        ]);

        // Add 2 indexes, 1 for source 1 for target
        $this->createIndex('source', '{{%event_log}}', ['source_name', 'source_id', 'created']);
        $this->createIndex('target', '{{%event_log}}', ['target_name', 'target_id', 'created']);
        $this->createIndex('event', '{{%event_log}}', ['event', 'created']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event_log}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210430_105442_create_event_log cannot be reverted.\n";

        return false;
    }
    */
}
