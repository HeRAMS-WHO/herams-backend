<?php

declare(strict_types=1);

namespace herams\api\controllers\permission;

use herams\api\domain\permission\NewPermission;
use herams\common\domain\permission\PermissionRepository;
use herams\common\helpers\ModelValidator;
use herams\common\interfaces\ModelHydratorInterface;
use SamIT\abac\AuthManager;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Request;
use yii\web\Response;

class Create extends Action
{
    public function run(
        Request $request,
        AuthManager $abacManager,
        PermissionRepository $permissionRepository,
        Response $response,
        ModelValidator $modelValidator,
        ModelHydratorInterface $modelHydrator,
    ) {
        if (! $request->isPost) {
            throw new MethodNotAllowedHttpException();
        }

        $permission = new NewPermission();

        $modelHydrator->hydrateFromJsonDictionary($permission, (array) $request->bodyParams);

        if (! $modelValidator->validateModel($permission)) {
            return $modelValidator->renderValidationErrors($permission, $response);
        }

        if (! $abacManager->getRepository()->check($permission)) {
            $abacManager->getRepository()->grant($permission);
            $response->setStatusCode(204, \Yii::t('app', 'Permission granted'));
        } else {
            $response->setStatusCode(303, \Yii::t('app', 'Permission already exists'));
        }
        $id = $permissionRepository->retrieveId($permission);
        $response->headers->add('Location', Url::to([
            '/permission/delete',
            'id' => $id,
        ], true));
        return $response;
    }
}
