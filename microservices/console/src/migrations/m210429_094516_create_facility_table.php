<?php
declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%facility}}`.
 */
class m210429_094516_create_facility_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%facility}}', [
            'id' => $this->primaryKey(),
            'workspace_id' => $this->integer()->notNull(),
            'uuid' => ' binary(16) not null',
            'deleted_at' => $this->dateTime()->null(),
            'deactivated_at' => $this->dateTime()->null(),
        ]);
        $this->createIndex('uuid', '{{%facility}}', ['uuid'], true);
        $this->addForeignKey('workspace_id', '{{%facility}}', ['workspace_id'], '{{%workspace}}', ['id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%facility}}');
    }
}
