<?php
declare(strict_types=1);

namespace prime\controllers\accessRequest;

use prime\models\ar\AccessRequest;
use prime\models\ar\Permission;
use prime\models\search\AccessRequest as AccessRequestSearch;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\web\Request;
use yii\web\User as UserComponent;

class Index extends Action
{
    public function run(
        Request $request,
        UserComponent $user
    ) {
        $userAccessRequestDataprovider = \Yii::createObject(ActiveDataProvider::class, [[
            'query' => AccessRequest::find()
                ->withoutResponse()
                ->notExpired()
                ->createdBy($user->id),
        ]]);

        $openAccessRequestsSearchModel = \Yii::createObject(AccessRequestSearch::class, [
            'query' => AccessRequest::find()
                ->withoutResponse()
                ->notExpired(),
            'filter' => static fn(AccessRequest $model) => $user->can(Permission::PERMISSION_RESPOND, $model),
        ]);

        return $this->controller->render(
            'index',
            [
                'userAccessRequestDataprovider' => $userAccessRequestDataprovider,
                'openAccessRequestsSearchModel' => $openAccessRequestsSearchModel,
                'openAccessRequestsDataprovider' => $openAccessRequestsSearchModel->search($request->queryParams),
            ]
        );
    }
}
