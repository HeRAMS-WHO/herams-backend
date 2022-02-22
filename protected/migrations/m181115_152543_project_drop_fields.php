<?php

use yii\db\Migration;

/**
 * Class m181115_152543_project_drop_fields
 */
class m181115_152543_project_drop_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach (
            [
            'description',
            'data_survey_eid',
            'latitude',
            'longitude',
            'default_generator',
            'locality_name',
            'country_iso_3',
            ] as $column
        ) {
            $this->dropColumn('{{%project}}', $column);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181115_152543_project_drop_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181115_152543_project_drop_fields cannot be reverted.\n";

        return false;
    }
    */
}
