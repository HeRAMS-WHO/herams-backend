<?php

declare(strict_types=1);

namespace herams\console\migrations;

use yii\db\Migration;

class m210511_085244_add_newsletter_subscription_to_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'newsletter_subscription', $this->boolean()->notNull());
        $this->update('{{%user}}', [
            'newsletter_subscription' => true,
        ]);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'newsletter_subscription');
    }
}
