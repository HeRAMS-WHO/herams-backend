<?php
namespace prime\controllers;


use prime\components\Controller;
use prime\models\forms\Settings;
use yii\web\Request;
use yii\web\Session;

class SettingsController extends Controller
{
    public $layout = 'simple';
    public function actionIndex(Request $request, Session $session) {
        $settings = new Settings();
        if ($request->isPost && $settings->load($request->getBodyParams())) {
            if ($settings->save()) {
                $session->setFlash('success', \Yii::t('app', 'Settings saved'));
            }
        }
        return $this->render('index', ['settings' => $settings]);
    }
}