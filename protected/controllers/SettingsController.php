<?php
namespace prime\controllers;


use prime\components\Controller;
use prime\models\forms\Settings;

class SettingsController extends Controller
{
    public function actionIndex() {
        $settings = new Settings();
        return $this->render('index', ['settings' => $settings]);
    }
}