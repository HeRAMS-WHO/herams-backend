<?php
declare(strict_types=1);

use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m210810_065557_add_expires_at_to_access_request
 */
class m210810_065557_add_expires_at_to_access_request extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%access_request}}', 'expires_at', $this->integer()->after('created_at'));
        $this->update('{{%access_request}}', ['expires_at' => new Expression('`created_at` + ' . 2 * 7 * 24 * 60 * 60)]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%access_request}}', 'expires_at');
    }
}
