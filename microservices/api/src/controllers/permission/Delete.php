<?php

declare(strict_types=1);

namespace herams\api\controllers\permission;

use herams\common\domain\permission\PermissionRepository;
use herams\common\domain\permission\ProposedGrant;
use herams\common\interfaces\AccessCheckInterface;
use herams\common\models\Permission;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\values\Authorizable;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\User;

class Delete extends Action
{
    public function run(
        Request $request,
        AuthManager $abacManager,
        Resolver $abacResolver,
        AccessCheckInterface $accessCheck,
        Response $response,
        PermissionRepository $permissionRepository,
        User $user,
        int $id
    ) {
        if (! $request->isDelete) {
            throw new MethodNotAllowedHttpException();
        }
        $permission = $permissionRepository->retrieve($id);

        if ($user->getIsGuest()) {
            throw new ForbiddenHttpException();
        }
        $sourceObject = $abacResolver->toSubject(new Authorizable($permission->source_id, $permission->source));
        $targetObject = $abacResolver->toSubject(new Authorizable($permission->target_id, $permission->target));

        $proposedGrant = new ProposedGrant($sourceObject, $targetObject, $permission->permission);
        $accessCheck->requirePermission($proposedGrant, Permission::PERMISSION_DELETE);
        $abacManager->revoke($sourceObject, $targetObject, $permission->permission);
        $response->headers->add('x-status-text', \Yii::t('app', 'Permission revoked'));
        $response->setStatusCode(204, \Yii::t('app', 'Permission revoked'));

        return $response;
    }
}
