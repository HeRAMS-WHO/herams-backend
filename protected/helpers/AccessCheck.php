<?php
declare(strict_types=1);

namespace prime\helpers;

use prime\interfaces\AccessCheckInterface;
use yii\rbac\CheckAccessInterface;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\User;

class AccessCheck implements AccessCheckInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function requirePermission(?object $model, string $permission, ?string $forbiddenMessage = null): void
    {
        if (!isset($model)) {
            throw new NotFoundHttpException();
        }

        if (!$this->user->can($permission, $model)) {
            throw new ForbiddenHttpException($forbiddenMessage);
        }
    }

    public function requireGlobalPermission(string $permission, ?string $forbiddenMessage = null): void
    {
        if (!$this->user->can($permission)) {
            throw new ForbiddenHttpException($forbiddenMessage);
        }
    }
}
