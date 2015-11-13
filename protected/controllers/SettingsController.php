<?php
namespace prime\controllers;


use prime\components\Controller;
use prime\models\forms\Settings;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;

class SettingsController extends Controller
{
    public function actionIndex(Request $request, Session $session) {
        $settings = new Settings();
        if ($request->isPost && $settings->load($request->getBodyParams())) {
            if ($settings->save()) {
                $session->setFlash('success', \Yii::t('app', 'Settings saved'));
            }
        }
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