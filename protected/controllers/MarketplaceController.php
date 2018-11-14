<?php

namespace prime\controllers;

use prime\components\Controller;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class MarketplaceController extends Controller
{
    public $defaultAction = 'herams';

    public function actionHerams()
    {
        $this->layout = 'angular';
        return $this->render('herams');
    }

    public function actionMap(Request $request, Client $limeSurvey)
    {
        return $this->render('herams'); // Country page on Angular
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ],
                    ]
                ]
            ]
        );
    }
}
