<?php
declare(strict_types=1);

use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m211006_073837_add_timestamp_fields
 */
class m211006_073837_add_timestamp_fields extends Migration
{
    protected $timestampFields = [
        'project' => [
            'created_at',
            'updated_at',
        ],
        'workspace' => [
            'updated_at',
        ],
        'facility' => [
            'created_at',
            'updated_at',
        ],
        'response' => [
            'created_at',
        ],
        'survey' => [
            'created_at',
            'updated_at',
        ],
    ];

    protected $renameColumns = [
        'workspace' => [
            'closed' => 'closed_at',
            'created' => 'created_at',
        ],
        'facility' => [
            'deactivated' => 'deactivated_at',
            'deleted' => 'deleted_at',
        ],
        'response' => [
            'last_updated' => 'updated_at',
        ],
        'session' => [
            'created' => 'created_at',
            'updated' => 'updated_at',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        // Rename timed columns
        $this->renameColumn('{{%user}}', 'created_at', 'temp_created_at');
        $this->renameColumn('{{%user}}', 'updated_at', 'temp_updated_at');
        $this->renameColumn('{{%permission}}', 'created_at', 'temp_created_at');
        $this->renameColumn('{{%access_request}}', 'created_at', 'temp_created_at');
        $this->renameColumn('{{%access_request}}', 'expires_at', 'temp_expires_at');
        $this->renameColumn('{{%access_request}}', 'responded_at', 'temp_responded_at');
        // Create new columns
        $this->addColumn('{{%user}}', 'created_at', $this->dateTime()->null());
        $this->addColumn('{{%user}}', 'updated_at', $this->dateTime()->null());
        $this->addColumn('{{%permission}}', 'created_at', $this->dateTime()->null());
        $this->addColumn('{{%access_request}}', 'created_at', $this->dateTime()->null());
        $this->addColumn('{{%access_request}}', 'expires_at', $this->dateTime()->null());
        $this->addColumn('{{%access_request}}', 'responded_at', $this->dateTime()->null());
        // Update data to columns
        $this->update(
            '{{%user}}',
            [
                'created_at' => new Expression('IF(`temp_created_at` > 1, FROM_UNIXTIME(`temp_created_at`), null)'),
                'updated_at' => new Expression('IF(`temp_updated_at` > 1, FROM_UNIXTIME(`temp_updated_at`), null)'),
            ]
        );
        $this->update(
            '{{%permission}}',
            [
                'created_at' => new Expression('IF(`temp_created_at` > 1, FROM_UNIXTIME(`temp_created_at`), null)'),
            ]
        );
        $this->update(
            '{{%access_request}}',
            [
                'created_at' => new Expression('IF(`temp_created_at` > 1, FROM_UNIXTIME(`temp_created_at`), null)'),
                'expires_at' => new Expression('IF(`temp_expires_at` > 1, FROM_UNIXTIME(`temp_expires_at`), null)'),
                'responded_at' => new Expression('IF(`temp_responded_at` > 1, FROM_UNIXTIME(`temp_responded_at`), null)'),
            ]
        );
        // Remove temp columns
        $this->dropColumn('{{%user}}', 'temp_created_at');
        $this->dropColumn('{{%user}}', 'temp_updated_at');
        $this->dropColumn('{{%permission}}', 'temp_created_at');
        $this->dropColumn('{{%access_request}}', 'temp_created_at');
        $this->dropColumn('{{%access_request}}', 'temp_expires_at');
        $this->dropColumn('{{%access_request}}', 'temp_responded_at');

        foreach ($this->timestampFields as $table => $fields) {
            foreach ($fields as $field) {
                $this->addColumn("{{%{$table}}}", $field, $this->dateTime()->null());
            }
        }

        foreach ($this->renameColumns as $table => $columns) {
            foreach ($columns as $columnFrom => $columnTo) {
                $this->renameColumn("{{%{$table}}}", "$columnFrom", "$columnTo");
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Rename timed columns
        $this->renameColumn('{{%user}}', 'created_at', 'temp_created_at');
        $this->renameColumn('{{%user}}', 'updated_at', 'temp_updated_at');
        $this->renameColumn('{{%permission}}', 'created_at', 'temp_created_at');
        $this->renameColumn('{{%access_request}}', 'created_at', 'temp_created_at');
        $this->renameColumn('{{%access_request}}', 'expires_at', 'temp_expires_at');
        $this->renameColumn('{{%access_request}}', 'responded_at', 'temp_responded_at');
        // Create new columns
        $this->addColumn('{{%user}}', 'created_at', $this->integer()->notNull());
        $this->addColumn('{{%user}}', 'updated_at', $this->integer()->notNull());
        $this->addColumn('{{%permission}}', 'created_at', $this->integer()->notNull());
        $this->addColumn('{{%access_request}}', 'created_at', $this->integer()->null());
        $this->addColumn('{{%access_request}}', 'expires_at', $this->integer()->null());
        $this->addColumn('{{%access_request}}', 'responded_at', $this->integer()->null());
        // Update data to columns
        $this->update(
            '{{%user}}',
            [
                'created_at' => new Expression('IF(`temp_created_at` IS NOT NULL, UNIX_TIMESTAMP(`temp_created_at`), 1)'),
                'updated_at' => new Expression('IF(`temp_updated_at` IS NOT NULL, UNIX_TIMESTAMP(`temp_updated_at`), 1)'),
            ]
        );
        $this->update(
            '{{%permission}}',
            [
                'created_at' => new Expression('IF(`temp_created_at` IS NOT NULL, UNIX_TIMESTAMP(`temp_created_at`), 1)'),
            ]
        );
        $this->update(
            '{{%access_request}}',
            [
                'created_at' => new Expression('IF(`temp_created_at` IS NOT NULL, UNIX_TIMESTAMP(`temp_created_at`), NULL)'),
                'expires_at' => new Expression('IF(`temp_expires_at` IS NOT NULL, UNIX_TIMESTAMP(`temp_expires_at`), NULL)'),
                'responded_at' => new Expression('IF(`temp_responded_at` IS NOT NULL, UNIX_TIMESTAMP(`temp_responded_at`), NULL)'),
            ]
        );
        // Remove temp columns
        $this->dropColumn('{{%user}}', 'temp_created_at');
        $this->dropColumn('{{%user}}', 'temp_updated_at');
        $this->dropColumn('{{%permission}}', 'temp_created_at');
        $this->dropColumn('{{%access_request}}', 'temp_created_at');
        $this->dropColumn('{{%access_request}}', 'temp_expires_at');
        $this->dropColumn('{{%access_request}}', 'temp_responded_at');

        foreach ($this->timestampFields as $table => $fields) {
            foreach ($fields as $field) {
                $this->dropColumn("{{%{$table}}}", $field);
            }
        }

        foreach ($this->renameColumns as $table => $columns) {
            foreach ($columns as $columnFrom => $columnTo) {
                $this->renameColumn("{{%{$table}}}", $columnTo, $columnFrom);
            }
        }
    }
}
