<?php
declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m211012_112105_create_audit_log
 */
class m211012_112105_create_audit_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%audit}}', [
            'id' => $this->primaryKey(),
            'subject_name' => $this->string()->notNull()->append('COLLATE ascii_bin'),
            'subject_id' => $this->integer()->notNull(),
            'event' => $this->string(30)->notNull()->append('COLLATE ascii_bin'),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull()
        ]);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%audit}}');
        return true;
    }
}
