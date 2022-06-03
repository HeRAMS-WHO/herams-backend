<?php

declare(strict_types=1);

use yii\db\Migration;

class m210323_074719_create_access_request extends Migration
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
                'created_at' => $this->integer(),
                'responded_at' => $this->integer(),
            ]
        );

        $this->createIndex('i-access_request-target_class-target_id', '{{%access_request}}', ['target_class', 'target_id']);
        $this->addForeignKey('fk-access_request-created_by-user-id', '{{%access_request}}', ['created_by'], '{{%user}}', ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-access_request-responded_by-user-id', '{{%access_request}}', ['responded_by'], '{{%user}}', ['id'], 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%access_request}}');
    }
}
