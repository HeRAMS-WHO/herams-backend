<?php
    namespace app\controllers;
    use app\components\Controller;

    class DevController extends Controller {
        public function beforeAction($action) {
            if (!defined('YII_DEBUG') || !YII_DEBUG) {
                return false;
            }
            parent::beforeAction($action);
        }
    }