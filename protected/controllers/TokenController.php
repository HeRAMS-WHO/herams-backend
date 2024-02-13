<?php

namespace prime\controllers;

use Yii;

class TokenController extends \prime\components\Controller
{
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
        //Json response yii2 code
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $csfrToken = Yii::$app->request->getCsrfToken(true);
        $response->data = [
            'csrfToken' => $csfrToken,
        ];
        return $response;
    }
}
