<?php

use yii\db\Migration;

class m151104_081900_create_country extends Migration
{
    public function up()
    {
        $this->createTable(\prime\models\Country::tableName(),[
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
            \prime\models\Project::tableName(),
            ['id']
        );

        $this->addForeignKey(
            'country_id',
            $projectCountryTableName,
            ['country_id'],
            \prime\models\Country::tableName(),
            ['id']
        );
    }

    public function down()
    {
        $this->dropTable('{{%country}}');
        $this->dropTable('{{%project_country}}');
    }
}
