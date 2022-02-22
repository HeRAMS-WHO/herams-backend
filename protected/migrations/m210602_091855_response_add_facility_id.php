<?php

use yii\db\Migration;

/**
 * Class m210602_091855_response_add_facility_id
 */
class m210602_091855_response_add_facility_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%response}}', 'facility_id', $this->integer());
        $this->addForeignKey('facility_response', '{{%response}}', ['facility_id'], '{{%facility}}', ['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210602_091855_response_add_facility_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210602_091855_response_add_facility_id cannot be reverted.\n";

        return false;
    }
    */
}
