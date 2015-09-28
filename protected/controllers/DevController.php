<?php
    namespace prime\controllers;
    use prime\components\Controller;
    use prime\models\Setting;
    use samit\limesurvey\models\Survey;

    class DevController extends Controller{
//        public function beforeAction($action) {
//            if (!defined('YII_DEBUG') || !YII_DEBUG) {
//                return false;
//            }
//            parent::beforeAction($action);
//        }

        public function actionIndex() {
//            var_dump(Setting::find()->all());
            $survey = Survey::find()->with('setting')->where(['sid' => 'test'])->one();
            var_dump($survey);
            die();
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