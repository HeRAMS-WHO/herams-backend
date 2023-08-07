<?php

namespace herams\console\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project}}`.
 */
class M230804162603CreateProjectTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'hidden' => $this->boolean()->defaultValue(false),
            'latitude' => $this->float(),
            'longitude' => $this->float(),
            'visibility' => $this->string(10)->notNull()->append('COLLATE ascii_bin')->defaultValue('public'),
            'country' => $this->char(3)->append('COLLATE ascii_bin'),
            'i18n' => $this->json(),
            'languages' => $this->json(),
            'admin_survey_id' => $this->integer(),
            'data_survey_id' => $this->integer(),
            'primary_language' => $this->string(10)->notNull()->append('COLLATE ascii_bin')->defaultValue('en'),
            'dashboard_url' => $this->string()->append('COLLATE utf8mb4_bin')
        ], 'charset = utf8mb3');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%project}}');
    }
}
