<?php
declare(strict_types=1);

namespace prime\commands;

use prime\models\ar\User;
use SamIT\Yii2\abac\AuthManager;
use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\console\Controller;
use yii\helpers\Console;

class PermissionController extends Controller
{
    use ActionInjectionTrait;

    public function actionMakeAdmin(
        AuthManager $authManager,
        string $email
    ) {
        $user = User::findOne([
            'email' => $email
        ]);
        if (!isset($user)) {
            throw new \RuntimeException('User not found');
        }

        $authManager->assign($authManager->createRole('admin'), $user->id);
        if ($authManager->checkAccess($user->id, 'admin')) {
            $this->stdout("Permission granted\n", Console::FG_GREEN);
        } else {
            $this->stdout("Failed to grant permission\n", Console::FG_RED);
        }
    }
}
