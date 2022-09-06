<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\project\Create;
use prime\modules\Api\controllers\project\Index;
use prime\modules\Api\controllers\project\LatestData;
use prime\modules\Api\controllers\project\Locales;
use prime\modules\Api\controllers\project\Summary;
use prime\modules\Api\controllers\project\Update;
use prime\modules\Api\controllers\project\Validate;
use prime\modules\Api\controllers\project\Variables;
use prime\modules\Api\controllers\project\View;
use yii\filters\AccessControl;

class ProjectController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'summary'],
            ],
            ...$behaviors['access']['rules'],
        ];
        return $behaviors;
    }

    public function actions(): array
    {
        return [
            'index' => Index::class,
            'summary' => Summary::class,
            'variables' => Variables::class,
            'latest-data' => LatestData::class,
            'validate' => Validate::class,
            'create' => Create::class,
            'update' => Update::class,
            'view' => View::class,
            'locales' => Locales::class,
        ];
    }
}
