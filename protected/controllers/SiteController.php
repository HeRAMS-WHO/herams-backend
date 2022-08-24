<?php

declare(strict_types=1);

namespace prime\controllers;

use prime\components\Controller;
use prime\controllers\site\LimeSurvey;
use prime\controllers\site\Maintenance;
use prime\controllers\site\Status;
use prime\controllers\site\WorldMap;
use yii\web\ErrorAction;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'status' => Status::class,
            'maintenance' => Maintenance::class,
            'error' => [
                'class' => ErrorAction::class,
                'layout' => Controller::LAYOUT_MAP_POPOVER_ERROR,
                'view' => 'error',
            ],
            'world-map' => WorldMap::class,
            'lime-survey' => LimeSurvey::class,
        ];
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        // Prepend this rule so we don't needlessly open a session.
        array_unshift($behaviors['access']['rules'], [
            'allow' => 'true',
            'actions' => ['captcha', 'logout', 'error', 'status', 'maintenance'],
        ]);
        $behaviors['access']['rules'][] = [
            'allow' => 'true',
            'roles' => ['@'],
        ];
        return $behaviors;
    }

    protected function getDefaultEntityAction(): string|null
    {
        return null;
    }
}
