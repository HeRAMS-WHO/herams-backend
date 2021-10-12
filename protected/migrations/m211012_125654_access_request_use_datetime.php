<?php
declare(strict_types=1);

use prime\helpers\MigrationHelper;
use yii\db\Migration;

/**
 * Class m211012_125654_access_request_use_datetime
 */
class m211012_125654_access_request_use_datetime extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $helper = new MigrationHelper($this);
        $helper->changeColumnFromIntToDatetimeWithNull('{{%access_request}}', 'responded_at');
        $helper->changeColumnFromIntToDatetimeWithNull('{{%access_request}}', 'expires_at');

        $this->dropColumn('{{%access_request}}', 'created_at');
        $this->dropForeignKey('fk-access_request-created_by-user-id', '{{%access_request}}');
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $helper = new MigrationHelper($this);
        $helper->changeColumnFromDatetimeToIntWithNull('{{%access_request}}', 'responded_at');
        $helper->changeColumnFromDatetimeToIntWithNull('{{%access_request}}', 'expires_at');

        $this->addColumn('{{%access_request}}', 'created_at', $this->integer());
        $this->addForeignKey('fk-access_request-created_by-user-id', '{{%access_request}}', 'created_by', '{{%user}}', 'id');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211012_125654_access_request_use_datetime cannot be reverted.\n";

        return false;
    }
    */
}
