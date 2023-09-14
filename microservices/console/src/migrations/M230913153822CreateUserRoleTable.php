<?php
namespace herams\console\migrations;
use yii\db\Migration;
/**
 * Handles the creation of table `{{%role}}`.
 */
class M230913153822CreateUserRoleTable extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create your table
        $this->createTable('{{%user_role}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'role_id' => $this->integer(),
            'target' => $this->string()->notNull(),
            'target_id' => $this->integer(),
            'created_date' => $this->dateTime(),
            'created_by' => $this->integer(),
            'last_modified_date' => $this->dateTime(),
            'last_modified_by' => $this->integer(),
        ]);

        // Add foreign key constraint to the user_id column
        $this->addForeignKey(
            'fk-user_role-user_id',
            '{{%user_role}}',
            'user_id',
            '{{%user}}', // Replace with the correct table name for users
            'ID',
            'CASCADE' // You can customize the onDelete behavior as needed
        );

        // Add foreign key constraint to the role_id column
        $this->addForeignKey(
            'fk-user_role-role_id',
            '{{%user_role}}',
            'role_id',
            '{{%role}}', // Replace with the correct table name for roles
            'ID',
            'CASCADE' // You can customize the onDelete behavior as needed
        );

        // Add an index on the 'target' column for faster lookups
        $this->createIndex(
            'idx-user_role-target',
            '{{%user_role}}',
            'target'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key constraints first
        $this->dropForeignKey('fk-user_role-user_id', '{{%user_role}}');
        $this->dropForeignKey('fk-user_role-role_id', '{{%user_role}}');

        // Drop the index on the 'target' column
        $this->dropIndex('idx-user_role-target', '{{%user_role}}');

        // Then, drop the table
        $this->dropTable('{{%user_role}}');
    }
}