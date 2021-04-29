<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%facility}}`.
 */
class m210429_094516_create_facility_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%facility}}', [
            'id' => $this->primaryKey(),
            'uuid' => ' binary(16) not null',
            'name' => $this->string(),
            'alternative_name' => $this->string(),
        ]);
        $this->createIndex('uuid', '{{%facility}}', ['uuid'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%facility}}');
    }
}
