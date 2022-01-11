<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\workspace\Import;
use prime\modules\Api\controllers\workspace\Refresh;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class WorkspaceController extends \yii\rest\Controller
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
        ]);
    }

    public function actions()
    {
        return [
            'refresh' => Refresh::class,
        ];
    }
}
