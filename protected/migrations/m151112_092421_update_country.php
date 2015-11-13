<?php

use yii\db\Migration;

class m151112_092421_update_country extends Migration
{
    public function up()
    {
        $this->dropForeignKey('project_id', \prime\models\ar\ProjectCountry::tableName());
        $this->dropForeignKey('country_id', \prime\models\ar\ProjectCountry::tableName());
        $this->dropTable('{{%country}}');
        $this->dropTable(\prime\models\ar\ProjectCountry::tableName());

        $this->createTable(
            \prime\models\ar\ProjectCountry::tableName(),[
            'project_id' => $this->integer()->notNull(),
            'country_iso_3' => $this->string(3)->notNull()
        ]);

        $this->addForeignKey(
            'project_id',
            \prime\models\ar\ProjectCountry::tableName(),
            ['project_id'],
            \prime\models\ar\Project::tableName(),
            ['id']
        );

        $this->createIndex('projectCountryUnique',
            '{{%project_country}}',
            [
                'project_id',
                'country_iso_3'
            ],
            true
        );
    }

    public function down()
    {
        $this->dropTable(\prime\models\ar\ProjectCountry::tableName());
        $this->createTable('{{%country}}',[
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'iso_code' => $this->string(3)->notNull(),
            'latitude' => $this->decimal(12,8)->notNull(),
            'longitude' => $this->decimal(12,8)->notNull()
        ]);

        $projectCountryTableName = '{{%project_country}}';
        $this->createTable($projectCountryTableName,[
            'project_id' => $this->integer()->notNull(),
            'country_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'project_id',
            $projectCountryTableName,
            ['project_id'],
            \prime\models\ar\Project::tableName(),
            ['id']
        );

        $this->addForeignKey(
            'country_id',
            $projectCountryTableName,
            ['country_id'],
            '{{%country}}',
            ['id']
        );
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
