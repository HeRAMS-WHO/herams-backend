<?php
declare(strict_types=1);

use prime\helpers\MigrationHelper;
use yii\db\Migration;

/**
 * Class m211012_141754_user_use_datetime
 */
class m211012_141754_user_use_datetime extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $helper = new MigrationHelper($this);
        $helper->changeColumnFromIntToDatetime('{{%user}}', 'created_at');
        $helper->changeColumnFromIntToDatetime('{{%user}}', 'updated_at');

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $helper = new MigrationHelper($this);
        $helper->changeColumnFromDatetimeToInt('{{%user}}', 'responded_at');
        $helper->changeColumnFromDatetimeToInt('{{%user}}', 'updated_at');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211012_141754_user_use_datetime cannot be reverted.\n";

        return false;
    }
    */
}
