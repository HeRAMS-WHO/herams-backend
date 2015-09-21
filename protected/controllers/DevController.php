<?php
    namespace app\controllers;
    use app\components\Controller;

    class DevController extends Controller {
//        public function beforeAction($action) {
//            if (!defined('YII_DEBUG') || !YII_DEBUG) {
//                return false;
//            }
//            parent::beforeAction($action);
//        }

        public function actionIndex() {
            $path = '';
            var_dump(getcwd());
            for ($i = substr_count(getcwd(), '/'); $i > 0; $i--) {
                $path .= '../';
            }
            var_dump($path .trim(\Yii::getAlias('@webroot/assets/compiled'), '/'));
            var_dump(realpath($path . trim(\Yii::getAlias('@webroot/assets/compiled'), '/')));

//            echo $path;
//            var_dump(file_exists(trim($path, '/')));
//            die();
        }
    }