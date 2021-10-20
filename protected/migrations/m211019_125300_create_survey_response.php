<?php
declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m211019_125300_create_survey_response
 */
class m211019_125300_create_survey_response extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): bool
    {
        $this->createTable(
            '{{%survey_response}}',
            [
                'id' => $this->primaryKey(),
                'survey_id' => $this->integer()->notNull(),
                'facility_id' => $this->integer()->notNull(),
                'data' => $this->json()->notNull(),
                'created_at' => $this->dateTime()->notNull(),
                'created_by' => $this->integer()->notNull(),
            ]
        );

        $this->addForeignKey('fk-survey_response-survey_id-survey-id', '{{%survey_response}}', ['survey_id'], '{{%survey}}', ['id']);
        $this->addForeignKey('fk-survey_response-facility_id-facility-id', '{{%survey_response}}', ['facility_id'], '{{%facility}}', ['id']);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): bool
    {
        $this->dropTable('{{%survey_response}}');
        return true;
    }
}
