<?php

use yii\db\Migration;

class m191024_124402_assignments_to_permissions extends Migration
{
    public function safeUp()
    {
        foreach ((new \yii\db\Query())->from('{{%auth_assignment}}')->all() as $assignment) {
            try {
                \Yii::$app->authManager->assign(
                    \Yii::$app->authManager->createRole($assignment['item_name']),
                    $assignment['user_id']
                );
            } catch (\Throwable $t) {
            }
        }
        $this->dropTable('{{%auth_assignment}}');
    }

    public function safeDown()
    {
        echo "m191024_124402_assignments_to_permissions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191024_124402_assignments_to_permissions cannot be reverted.\n";

        return false;
    }
    */
}
