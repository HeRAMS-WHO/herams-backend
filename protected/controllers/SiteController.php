<?php
    namespace prime\controllers;

    use prime\components\Controller;
    use prime\controllers\site\Admin;
    use prime\controllers\site\LimeSurvey;
    use prime\controllers\site\Status;
    use prime\controllers\site\WorldMap;
    use yii\filters\AccessControl;
    use yii\helpers\ArrayHelper;
    use yii\web\ErrorAction;

    class SiteController extends Controller
{
    public function actions()
    {
        return [
            'status' => Status::class,
            'error' => [
                'class' => ErrorAction::class,
                'layout' => 'map-popover-error',
                'view' => 'error'
            ],
            'world-map' => WorldMap::class,
            'lime-survey' => LimeSurvey::class
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => 'true',
                            'actions' => ['captcha', 'logout', 'error', 'status']
                        ],
                        [
                            'allow' => 'true',
                            'roles' => ['@']
                        ]
                    ]
                ]
            ]
        );
    }
}
