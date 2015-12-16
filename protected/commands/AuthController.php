<?php


namespace prime\commands;


use prime\models\ar\User;
use yii\console\Controller;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\rbac\ManagerInterface;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\IdentityInterface;

class AuthController extends Controller
{
    protected function createRelation(ManagerInterface $authManager, $parent, $child) {
        $parentItem = $authManager->getRole($parent);
        $childItem = $authManager->getPermission($child);
        if ($authManager->hasChild($parentItem, $childItem)) {
            $this->stdout("Skipped relation: $parent ==> $child \n", Console::FG_YELLOW);
        } elseif ($authManager->addChild($parentItem, $childItem)) {
            $this->stdout("Created relation: $parent ==> $child \n", Console::FG_GREEN);
        } else {
            $this->stdout("Failed to create relation: $parent ==> $child \n", Console::FG_RED);
        }

    }
    protected function create(ManagerInterface $authManager, $type, $name, $description)
    {
        $get = "get" . ucfirst($type);
        $create = "create" . ucfirst($type);
        /** @var Role $role */
        if (null === $item = $authManager->$get($name)) {
            $item = $authManager->$create($name);
            $item->description = $description;
            if ($authManager->add($item)) {
                $this->stdout("Created $type: '{$item->name}', {$item->description}\n", Console::FG_GREEN);
            } else {
                $this->stdout("Failed to create $type: '{$item->name}', {$item->description}\n", Console::FG_RED);
            }
        } else {
            $this->stdout("Skipped $type: '{$item->name}', {$item->description}\n", Console::FG_YELLOW);
        }
    }
    /**
     * Create the default admin role if it does not exist.
     */
    public function actionInit(ManagerInterface $authManager)
    {
        $this->create($authManager, 'role', 'admin', "Default admin role, users with this role can do anything.");

        // Define default permissions.
        foreach([
            'tools' => 'Allow a user to manage tool configuration / creation.'
        ] as $permission => $description) {
            $this->create($authManager, 'permission', $permission, $description);
        }

        // Default rights.
        foreach([
            'admin' => ['tools']
        ] as $parent => $children) {
            foreach ($children as $child) {
                $this->createRelation($authManager, $parent, $child);
            }

        }


    }
    /**
     * Grants specified role to the user.
     * @param \yii\web\User $user DI
     * @param ManagerInterface $authManager DI
     * @param int $userId The user ID
     * @param string $roleName The role to grant
     */
    public function actionGrant(\yii\web\User $user, ManagerInterface $authManager, $userId, $roleName)
    {
        $class = $user->identityClass;
        $identity = $class::findIdentity($userId);
        $role = $authManager->getRole($roleName);
        if (!isset($identity, $role)) {
            throw new \Exception("User or role not found.");
        }
        try {
            $result = $authManager->assign($role, $userId);
            $this->stdout("Role granted.", Console::FG_GREEN);
        } catch (\Exception $e) {
            $this->stdout($e->getMessage() . "\n", Console::FG_RED);
        }


    }

    /**
     * List users, optionally filtering by string.
     * @param int $search
     */
    public function actionUsers($search = null)
    {
        $class = \Yii::$app->user->identityClass;

        if (is_subclass_of($class, ActiveRecord::class)) {
            $rows = [];
            $columns = [];
            /** @var ActiveRecord $user */
            /** @var IdentityInterface $user */
            foreach ($class::find()->all() as $user) {
                if (empty($columns)) {
                    $columns['++ id ++'] = 8;
                    foreach($user->attributes as $key => $dummy) {
                        $columns[$key] = strlen($key);
                    }


                }

                if (!isset($search) || strpos(implode('', $user->attributes), $search) !== false) {
                    foreach($user->attributes as $key => $value) {
                        $columns[$key] = max($columns[$key], strlen($value));
                    }
                    $row = $user->attributes;
                    $row['++ id ++'] = $user->getId();
                    $rows[] = $row;
                }
            }

            if (!empty($rows)) {
                $this->renderRow(array_combine(array_keys($columns), array_keys($columns)), $columns);
                foreach($rows as $row) {
                    $this->renderRow($row, $columns);
                }
            }
        }
    }

    protected function renderField($value, $length) {
        echo "| " . str_pad($value, $length, " ") ." |";
    }
    protected function renderRow($values, $lengths)
    {
        foreach($lengths as $column => $length) {

            $this->renderField(ArrayHelper::getValue($values, $column), $length);

        }
        echo "\n";
    }

    /**
     * List all roles
     */
    public function actionRoles(ManagerInterface $authManager)
    {
        $rows = [];
        $columns = [];
        /** @var \yii\rbac\Role $role */
        foreach ($authManager->getRoles() as $role) {
            if (empty($columns)) {
                foreach(['name', 'description'] as $name) {
                    $columns[$name] = strlen($name);
                }


            }
            foreach(['name', 'description'] as $name) {
                $columns[$name] = max($columns[$name], strlen($role->$name));
            }


            $rows[] = $role;

        }

        if (!empty($rows)) {
            $this->renderRow(array_combine(array_keys($columns), array_keys($columns)), $columns);
            foreach($rows as $row) {
                $this->renderRow($row, $columns);
            }
        }

    }
}