<?php


namespace prime\rules;


use prime\models\ar\Tool;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use yii\rbac\Item;
use yii\rbac\Rule;

class CreateToolInstance extends Rule
{
    public $name = __CLASS__;
    /**
     * Executes the rule.
     *
     * @param string|integer $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[ManagerInterface::checkAccess()]].
     * @return boolean a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (!isset($params['toolId'])) {
            return Permission::anyAllowed(User::findOne(['id' => $user]), Tool::class, Permission::PERMISSION_INSTANTIATE);
        } else {
            return Permission::isAllowed(User::findOne(['id' => $user]), Tool::findOne(['id' => $params['toolId']]), Permission::PERMISSION_INSTANTIATE);
        }

    }
}