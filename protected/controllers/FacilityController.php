<?php
declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\facility\CopyLatestResponse;
use prime\controllers\facility\Create;
use prime\controllers\facility\Index;
use prime\controllers\facility\Responses;
use prime\controllers\facility\Update;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class FacilityController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ]
                ]
            ]
        );
    }

    public function actions(): array
    {
        return [
            'index' => Index::class,
            'create' => Create::class,
            'update' => Update::class,
            'responses' => Responses::class,
            'copy-latest-response' => CopyLatestResponse::class
        ];
    }
}
