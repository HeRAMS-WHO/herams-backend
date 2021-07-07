<?php
declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m210511_085244_add_newsletter_subscription_to_user
 */
class m210511_085244_add_newsletter_subscription_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'newsletter_subscription', $this->boolean()->notNull());
        $this->update('{{%user}}', ['newsletter_subscription' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'newsletter_subscription');
    }
}
