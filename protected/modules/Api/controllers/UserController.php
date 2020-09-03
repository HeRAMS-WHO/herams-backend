<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\user\Workspaces;
use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class UserController extends \yii\rest\Controller
{
    use ActionInjectionTrait;
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
            'workspaces' => Workspaces::class
        ];
    }
}
