<?php
declare(strict_types=1);

namespace prime\interfaces;

use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

interface AccessCheckInterface
{
    /**
     * @param object|null $model
     * @param string $permission
     * @throws NotFoundHttpException if $model is null
     * @throws ForbiddenHttpException if current user does not have $permission for model $model
     */
    public function requirePermission(?object $model, string $permission, ?string $forbiddenMessage = null): void;


    public function requireGlobalPermission(string $permission, ?string $forbiddenMessage = null): void;

    /**
     * Check if the current user has permission on given model
     */
    public function checkPermission(object $model, string $permission): bool;
}
