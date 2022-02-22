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
            'workspace_id' => $this->integer()->notNull(),
            'uuid' => ' binary(16) not null',
//            'name' => $this->string()->notNull(),
//            'alternative_name' => $this->string(),
//            'i18n' => $this->json(),
//            'code' => $this->string(),
            'deleted_at' => $this->dateTime()->null(),
            'deactivated_at' => $this->dateTime()->null(),
//            'coordinates' => 'point null'
        ]);
        $this->createIndex('uuid', '{{%facility}}', ['uuid'], true);
        $this->addForeignKey('workspace_id', '{{%facility}}', ['workspace_id'], '{{%workspace}}', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%facility}}');
    }
}
