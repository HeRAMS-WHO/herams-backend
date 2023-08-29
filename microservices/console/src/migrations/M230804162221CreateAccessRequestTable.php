<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%access_request}}`.
 */
class M230804162221CreateAccessRequestTable extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            '{{%access_request}}',
            [
                'id' => $this->primaryKey(),
                'target_class' => $this->string()->notNull(),
                'target_id' => $this->integer()->notNull(),
                'subject' => $this->string(),
                'body' => $this->text(),
                'permissions' => $this->json(),
                'accepted' => $this->boolean(),
                'response' => $this->text(),
                'created_by' => $this->integer(),
                'responded_by' => $this->integer(),
                'responded_at' => $this->dateTime(),
                'expires_at' => $this->dateTime(),
            ]
        );

        $this->createIndex(
            'fk-access_request-created_by-user-id',
            '{{%access_request}}',
            ['created_by']
        );

        $this->createIndex(
            'i-access_request-target_class-target_id',
            '{{%access_request}}',
            ['target_class', 'target_id']
        );
        $this->addForeignKey(
            'fk-access_request-responded_by-user-id',
            '{{%access_request}}',
            ['responded_by'],
            '{{%user}}',
            ['id'],
            'SET NULL',
            'CASCADE'
        );
    }


    public function safeDown()
    {
        $this->dropForeignKey('fk-access_request-responded_by-user-id', '{{%access_request}}');
        $this->dropTable('{{%access_request}}');
    }
}
