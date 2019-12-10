<?php
    namespace prime\controllers;
    use prime\components\Controller;
    use prime\controllers\site\Admin;
    use prime\controllers\site\LimeSurvey;
    use prime\controllers\site\WorldMap;
    use prime\interfaces\TicketingInterface;
    use yii\captcha\CaptchaAction;
    use yii\filters\AccessControl;
    use yii\helpers\ArrayHelper;
    use yii\web\BadRequestHttpException;
    use yii\web\ErrorAction;

    class SiteController extends Controller
    {
             public function actions()
        {
            return [
                'error' => [
                    'class' => ErrorAction::class,
                    'layout' => 'map-popover',
                    'view' => 'error'
                ],
                'world-map' => [
                    'class' => WorldMap::class
                ],
                'lime-survey' => [
                    'class' => LimeSurvey::class
                ]
            ];
        }

        public function behaviors()
        {
            return ArrayHelper::merge(parent::behaviors(),
                [
                    'access' => [
                        'class' => AccessControl::class,
                        'rules' => [
                            [
                                'allow' => 'true',
                                'actions' => ['captcha', 'logout', 'error']
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
