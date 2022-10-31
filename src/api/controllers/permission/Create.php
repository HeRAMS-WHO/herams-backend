<?php

declare(strict_types=1);

namespace herams\api\controllers\permission;

use prime\helpers\ProposedGrant;
use prime\interfaces\AccessCheckInterface;
use prime\models\ar\Permission;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\values\Authorizable;
use yii\base\Action;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Request;
use yii\web\Response;

class Create extends Action
{
    public function run(
        Request $request,
        AuthManager $abacManager,
        Resolver $abacResolver,
        AccessCheckInterface $accessCheck,
        Response $response,
    ) {
        $sourceObject = $abacResolver->toSubject(new Authorizable($source_id, $source));
        $targetObject = $abacResolver->toSubject(new Authorizable($target_id, $target));

        $proposedGrant = new ProposedGrant($sourceObject, $targetObject, $permission);

       if ($request->isPost) {
            $accessCheck->requirePermission($proposedGrant, Permission::PERMISSION_CREATE);
            $abacManager->grant($sourceObject, $targetObject, $permission);
            $response->setStatusCode(204, \Yii::t('app', 'Permission granted'));
            $response->headers->add('x-status-text', \Yii::t('app', 'Permission granted'));
        } else {
            throw new MethodNotAllowedHttpException();
        }
        return $response;
    }
}
