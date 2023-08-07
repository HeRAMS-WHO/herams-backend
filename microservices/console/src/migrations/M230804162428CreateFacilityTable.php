<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%facility}}`.
 */
class M230804162428CreateFacilityTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%facility}}', [
            'id' => $this->primaryKey(),
            'workspace_id' => $this->integer()->notNull(),
            'data' => $this->json(),
            'admin_data' => $this->json(),
            'latitude' => $this->decimal(10, 8),
            'longitude' => $this->decimal(11, 8),
            'can_receive_situation_update' => $this->boolean()->notNull()->defaultValue(true),
            'date_of_update' => $this->date(),
            'tier' => "ENUM('Primary', 'Secondary', 'Tertiary', 'Other')",
            'status' => "ENUM('Active', 'Closed', 'Deleted') DEFAULT 'Active'",
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%facility}}');
    }
}
