<?php

use yii\db\Migration;

class m151028_120616_create_response_table extends Migration
{
    public function up()
    {
        $responseTable = \prime\models\ar\Response::tableName();
        $this->createTable($responseTable, [
            'id' => $this->string(36)->notNull(),
            'created' => $this->dateTime(),
            'project_id' => $this->integer(),
            'user_id' => $this->integer(),
            'survey_id' => $this->integer(),
            'data' => $this->text()
        ]);


        $surveyTable = \prime\models\ar\Survey::tableName();
        $this->createTable($surveyTable, [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'parent_id' => $this->integer()->defaultValue(null),
        ]);
        $this->addPrimaryKey('', $responseTable, ['id']);

        $this->addForeignKey('response_survey', $responseTable, 'survey_id', $surveyTable, 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('response_user', $responseTable, 'user_id', \prime\models\ar\User::tableName(), 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('response_project', $responseTable, 'user_id', \prime\models\ar\Project::tableName(), 'id', 'RESTRICT', 'RESTRICT');

        $this->addForeignKey('survey_parent', $surveyTable, 'parent_id', $surveyTable, 'id', 'RESTRICT', 'RESTRICT');


    }

    public function down()
    {
        $this->dropTable(\prime\models\ar\Response::tableName());
        $this->dropTable(\prime\models\ar\Survey::tableName());
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
