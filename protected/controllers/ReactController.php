<?php

namespace prime\controllers;

use prime\components\Controller;

class ReactController extends \prime\components\Controller
{
    public $layout = Controller::LAYOUT_REACT;

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControllerReact::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
