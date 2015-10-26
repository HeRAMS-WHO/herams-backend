<?php
namespace prime\controllers;


use prime\components\Controller;
use prime\models\forms\Settings;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SettingsController extends Controller
{
    public function actionIndex() {
        $settings = new Settings();
        return $this->render('index', ['settings' => $settings]);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['admin'],
                        ],
                    ]
                ]
            ]
        );
    }
}