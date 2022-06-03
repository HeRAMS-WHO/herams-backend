<?php

use yii\db\Migration;

class m191025_093828_drop_profile extends Migration
{
    public function safeUp()
    {
        foreach (['confirmed_at', 'auth_key', 'unconfirmed_email', 'registration_ip', 'flags', 'access_token'] as $column) {
            $this->dropColumn('{{%user}}', $column);
        }
        $this->addColumn('{{%user}}', 'name', $this->string()->append('collate utf8mb4_general_ci'));
        /** @var \prime\models\ar\User $user */
        foreach (\prime\models\ar\User::find()->each() as $user) {
            $profile = (new \yii\db\Query())->from('{{%profile}}')->andWhere([
                'user_id' => $user->id,
            ])->one();

            $user->name = trim("{$profile['first_name']} {$profile['last_name']}");
            if (! empty($user->name)) {
                $user->save();
            }
        }
        $this->dropTable('{{%profile}}');
    }

    public function safeDown()
    {
        echo "m191025_093828_drop_profile cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191025_093828_drop_profile cannot be reverted.\n";

        return false;
    }
    */
}
