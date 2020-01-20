<?php

use yii\db\Migration;

/**
 * Class m200120_133435_admin_to_global_permission
 */
class m200120_133435_admin_to_global_permission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** @var \SamIT\Yii2\abac\AuthManager $manager */
        $manager = \Yii::$app->authManager;
        if (!$manager instanceof \SamIT\Yii2\abac\AuthManager) {
            throw new \yii\base\InvalidConfigException('Manager should be instance of ' . \SamIT\Yii2\abac\AuthManager::class);
        }
        $role = new \yii\rbac\Role(['name' => 'admin']);

        $query = new \yii\db\Query();
        $query->from('{{%auth_assignment}}')->andWhere(['item_name' => 'admin']);
        foreach($query->all() as $assignment) {
            $manager->assign($role, $assignment['user_id']);
            $this->delete('{{%auth_assignment}}', $assignment);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200120_133435_admin_to_global_permission cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200120_133435_admin_to_global_permission cannot be reverted.\n";

        return false;
    }
    */
}
