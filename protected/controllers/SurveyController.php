<?php

declare(strict_types=1);

namespace prime\controllers;

use herams\common\models\PermissionOld;
use prime\components\Controller;
use prime\controllers\survey\Create;
use prime\controllers\survey\Index;
use prime\controllers\survey\Update;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class SurveyController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;

    public function actions(): array
    {
        return [
            'create' => Create::class,
            'index' => Index::class,
            'update' => Update::class,
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'verb' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'create' => ['get'],
                        'index' => ['get'],
                        'update' => ['get'],
                    ],
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => [PermissionOld::PERMISSION_ADMIN],
                        ],
                    ],
                ],
            ]
        );
    }
}
