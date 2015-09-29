<?php
    namespace prime\controllers;
    use prime\components\Controller;
    use yii\captcha\CaptchaAction;

    class SiteController extends Controller
    {
        public function accessRules() {
            $rules = [
				[
					'allow',
					'actions' => ['index']
				]
			];
			return array_merge($rules, parent::accessRules());
        }

        public function actionIndex()
        {
            return $this->render('index');
        }

        public function actions()
        {
            return [
                'captcha' => [
                    'class' => CaptchaAction::class
                ]
            ];
        }
    }