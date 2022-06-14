<?php

declare(strict_types=1);

namespace prime\controllers\accessRequest;

use prime\interfaces\AccessCheckInterface;
use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\search\AccessRequest as AccessRequestSearch;
use yii\base\Action;
use yii\web\Request;
use yii\web\User as UserComponent;

class Index extends Action
{
    public function run(
        Request $request,
        AccessCheckInterface $accessCheck,
        UserComponent $user
    ) {
        $openAccessRequestsSearchModel = new AccessRequestSearch(
            AccessRequest::find()
                ->withoutResponse()
                ->notExpired()
                ->withFields('created_at')
                ->orderBy([
                    'created_at' => SORT_DESC,
                ]),
            $user->identity,
            static fn (AccessRequest $model) => $accessCheck->checkPermission($model, Permission::PERMISSION_RESPOND),
        );

        $closedAccessRequestsSearchModel = new AccessRequestSearch(
            AccessRequest::find()
                ->orderBy([
                    'responded_at' => SORT_DESC,
                ])
                ->withResponse(),
            $user->identity,
            static fn (AccessRequest $model) => $accessCheck->checkPermission($model, Permission::PERMISSION_RESPOND),
        );

        return $this->controller->render(
            'index',
            [
                'closedAccessRequestsSearchModel' => $closedAccessRequestsSearchModel,
                'closedAccessRequestsDataprovider' => $closedAccessRequestsSearchModel->search($request->queryParams),
                'openAccessRequestsSearchModel' => $openAccessRequestsSearchModel,
                'openAccessRequestsDataprovider' => $openAccessRequestsSearchModel->search($request->queryParams),
            ]
        );
    }
}
