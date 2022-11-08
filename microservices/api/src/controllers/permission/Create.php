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
use SamIT\abac\values\Grant;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Request;
use yii\web\Response;

class Create extends Action
{
    public function run(
        Request $request,
        AuthManager $abacManager,
        Resolver $abacResolver,
        PermissionRepository $permissionRepository,
        AccessCheckInterface $accessCheck,
        Response $response,
    ) {
        if (!$request->isPost) {
            throw new MethodNotAllowedHttpException();
        }
        $source_id = $request->getBodyParam('source_id');
        $source = $request->getBodyParam('source');
        $target_id = $request->getBodyParam('target_id');
        $target = $request->getBodyParam('target');
        $permission = $request->getBodyParam('permission');
        if (!isset($source_id, $source, $target_id, $target, $permission)) {
            throw new BadRequestHttpException();
        }
        $sourceAuthorizable = new Authorizable((string) $source_id, $source);
        $sourceObject = $abacResolver->toSubject($sourceAuthorizable);
        $targetAuthorizable = new Authorizable((string) $target_id, $target);
        $targetObject = $abacResolver->toSubject($targetAuthorizable);

        $proposedGrant = new ProposedGrant($sourceObject, $targetObject, $permission);


        $accessCheck->requirePermission($proposedGrant, Permission::PERMISSION_CREATE);

        $grant = new Grant($sourceAuthorizable, $targetAuthorizable, $permission);
        if (!$abacManager->getRepository()->check($grant)) {
            $abacManager->getRepository()->grant($grant);
            $response->setStatusCode(204, \Yii::t('app', 'Permission granted'));
        } else {
            $response->setStatusCode(303, \Yii::t('app', 'Permission already exists'));
        }
        $id = $permissionRepository->retrieveId($sourceAuthorizable, $targetAuthorizable, $permission);
        $response->headers->add('Location', Url::to(['/permission/delete', 'id' => $id], true));
     return $response;
    }
}
