<?php
namespace prime\controllers;


use prime\components\Controller;
use prime\components\NotificationService;
use prime\models\forms\Settings;
use yii\web\Request;

class SettingsController extends Controller
{
    public $layout = 'admin';
    public function actionIndex(
        Request $request,
        NotificationService $notificationService
    ) {
        $settings = new Settings();
        if ($request->isPost && $settings->load($request->getBodyParams())) {
            if ($settings->save()) {
                $notificationService->success(\Yii::t('app', 'Settings saved'));
            }
        }
        return $this->render('index', ['settings' => $settings]);
    }
}