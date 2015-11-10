<?php


namespace prime\commands;


use prime\models\User;
use yii\console\Controller;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\rbac\ManagerInterface;
use yii\rbac\Role;
use yii\web\IdentityInterface;

class AuthController extends Controller
{

    /**
     * Create the default admin role if it does not exist.
     */
    public function actionInit(ManagerInterface $authManager)
    {
        /** @var Role $role */
        if (null === $role = $authManager->getRole('admin')) {
            $role = $authManager->createRole('admin');
            $role->description = "Default admin role, users with this role can do anything.";
            $authManager->update('admin', $role);
        }



        if(isset($role)) {
            $this->stdout("Created role: '{$role->name}', {$role->description}\n", Console::FG_YELLOW);
            $this->stdout("OK\n", Console::FG_GREEN);
        } else {
            $this->stdout("NOT OK\n", Console::FG_RED);
        }
    }
    /**
     * Grants specified role to the user.
     * @param $user int The user ID
     * @param $role string The role to grant
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
    public function actionRoles()
    {
        $rows = [];
        $columns = [];
        /** @var \yii\rbac\Role $role */
        foreach (app()->authManager->getRoles() as $role) {
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