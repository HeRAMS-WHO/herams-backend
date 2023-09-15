<?php

declare(strict_types=1);

namespace prime\controllers\user;

use herams\common\models\PermissionOld;
use prime\components\Controller;
use SamIT\abac\AuthManager;
use SamIT\abac\values\Grant;
use yii\base\Action;
use yii\data\ArrayDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\User;
use function iter\toArray;

class Roles extends Action
{
    public function run(
        User $user,
        AuthManager $abacManager,
        int $id
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_CONTENT;
        if (! $user->can(PermissionOld::PERMISSION_ADMIN)) {
            throw new ForbiddenHttpException();
        }
        $model = \herams\common\domain\user\User::findOne([
            'id' => $id,
        ]);
        if (! isset($model)) {
            throw new ForbiddenHttpException();
        }
        return $this->controller->renderContent(\kartik\grid\GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'models' => toArray($abacManager->getRepository()->search($abacManager->resolveSubject($model), null, null)),
            ]),
            'columns' => [
                [
                    'label' => 'Source ID',
                    'value' => function (Grant $grant) {
                        return $grant->getSource()->getId();
                    },
                ],
                [
                    'label' => 'Source type',
                    'value' => function (Grant $grant) {
                        return $grant->getSource()->getAuthName();
                    },
                ],
                [
                    'label' => 'Target ID',
                    'value' => function (Grant $grant) {
                        return $grant->getTarget()->getId();
                    },
                ],
                [
                    'label' => 'Target type',
                    'value' => function (Grant $grant) {
                        return $grant->getTarget()->getAuthName();
                    },
                ],
                [
                    'label' => 'PermissionOld',
                    'value' => function (Grant $grant) {
                        return PermissionOld::permissionLabels()[$grant->getPermission()] ?? $grant->getPermission();
                    },
                ],

            ],
        ]));
    }
}
