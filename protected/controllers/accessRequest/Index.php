<?php
declare(strict_types=1);

namespace prime\controllers\accessRequest;

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
        UserComponent $user
    ) {
        $openAccessRequestsSearchModel = new AccessRequestSearch(
            AccessRequest::find()
                ->withoutResponse()
                ->notExpired(),
            $user->identity,
            static fn(AccessRequest $model) => $user->can(Permission::PERMISSION_RESPOND, $model),
        );

        $closedAccessRequestsSearchModel = new AccessRequestSearch(
            AccessRequest::find()
                ->andWhere(['>', 'created_at', strtotime('-90 days', 90)])
                ->withResponse(),
            $user->identity,
            static fn(AccessRequest $model) => $user->can(Permission::PERMISSION_RESPOND, $model),
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
