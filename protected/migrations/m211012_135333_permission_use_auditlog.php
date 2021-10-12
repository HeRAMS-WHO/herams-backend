<?php
declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m211012_135333_permission_use_auditlog
 */
class m211012_135333_permission_use_auditlog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-permission-created_by-user-id', '{{%permission}}');
        $this->dropColumn('{{%permission}}', 'created_by');
        $this->dropColumn('{{%permission}}', 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%permission}}', 'created_by', $this->integer());
        $this->addColumn('{{%permission}}', 'created_at', $this->integer());
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211012_135333_permission_use_auditlog cannot be reverted.\n";

        return false;
    }
    */
}
