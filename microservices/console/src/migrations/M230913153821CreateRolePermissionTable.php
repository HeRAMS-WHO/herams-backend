<?php
namespace herams\console\migrations;
use yii\db\Migration;
/**
 * Handles the creation of table `{{%role}}`.
 */
class M230913153821CreateRolePermissionTable extends Migration {
    public function safeUp()
    {
        // Create your table
        $this->createTable('{{%role_permission}}', [
            'id' => $this->primaryKey(),
            'created_by' => $this->integer(),
            'created_date' => $this->dateTime(),
            'last_modified_by' => $this->integer(),
            'last_modified_date' => $this->dateTime(),
            'permission_code' => $this->string(),
            'role_id' => $this->integer(),
        ]);

        // Add foreign key constraint to the created_by column
        $this->addForeignKey(
            'fk-role_permission-created_by',
            '{{%role_permission}}',
            'created_by',
            '{{%user}}', // Replace with the correct table name for users
            'id',
            'CASCADE' // You can customize the onDelete behavior as needed
        );

        // Add foreign key constraint to the last_modified_by column
        $this->addForeignKey(
            'fk-role_permission-last_modified_by',
            '{{%role_permission}}',
            'last_modified_by',
            '{{%user}}', // Replace with the correct table name for users
            'id',
            'CASCADE' // You can customize the onDelete behavior as needed
        );

        // Add foreign key constraint to the permission_code column
        $this->addForeignKey(
            'fk-role_permission-permission_code',
            '{{%role_permission}}',
            'permission_code',
            '{{%permissions}}', // Replace with the correct table name for permissions
            'code',
            'CASCADE' // You can customize the onDelete behavior as needed
        );

        // Add foreign key constraint to the role_id column
        $this->addForeignKey(
            'fk-role_permission-role_id',
            '{{%role_permission}}',
            'role_id',
            '{{%role}}', // Replace with the correct table name for roles
            'id',
            'CASCADE' // You can customize the onDelete behavior as needed
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key constraints first
        $this->dropForeignKey('fk-role_permission-created_by', '{{%role_permission}}');
        $this->dropForeignKey('fk-role_permission-last_modified_by', '{{%role_permission}}');
        $this->dropForeignKey('fk-role_permission-permission_code', '{{%role_permission}}');
        $this->dropForeignKey('fk-role_permission-role_id', '{{%role_permission}}');

        // Then, drop the table
        $this->dropTable('{{%role_permission}}');
    }
}