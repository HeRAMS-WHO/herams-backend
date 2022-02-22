<?php

declare(strict_types=1);

namespace prime\controllers\user;

use prime\models\ar\AccessRequest;
use prime\models\ar\User as ActiveRecordUser;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\web\User as UserComponent;

class AccessRequests extends Action
{
    public function run(
        UserComponent $user
    ) {
        /** @var ActiveRecordUser $identity */
        $identity = $user->identity;

        $respondedAccessRequestDataprovider = new ActiveDataProvider([
            'query' => AccessRequest::find()
                ->withFields('created_at')
                ->andWhere(['responded_by' => $identity->id]),
        ]);

        $userAccessRequestDataprovider = new ActiveDataProvider([
            'query' => AccessRequest::find()
                ->withFields('created_at')
                ->andWhere(['created_by' => $identity->id]),
        ]);

        return $this->controller->render(
            'access-requests',
            [
                'model' => $identity,
                'respondedAccessRequestDataprovider' => $respondedAccessRequestDataprovider,
                'userAccessRequestDataprovider' => $userAccessRequestDataprovider,
            ]
        );
    }
}
