<?php
    namespace prime\controllers;
    use prime\components\Controller;
    use yii\captcha\CaptchaAction;
    use yii\filters\AccessControl;
    use yii\helpers\ArrayHelper;
    use yii\web\Session;
    use yii\web\User;

    class SiteController extends Controller
    {
        public function actionIndex(User $user)
        {
            if($user->id !== null) {
                return $this->redirect(['/marketplace']);
            } else {
                return $this->redirect(['/site/about']);
            }
        }

        public function actionAbout()
        {
            return $this->render('about');
        }

        public function actions()
        {
            return [
                'captcha' => [
                    'class' => CaptchaAction::class,
                    'fixedVerifyCode' => php_sapi_name() == 'cli-server' ? 'test' : null
                ]
            ];
        }

        public function behaviors()
        {
            return ArrayHelper::merge(parent::behaviors(),
                [
                    'access' => [
                        'class' => AccessControl::class,
                        'rules' => [
                            [
                                'allow' => 'true',
                                'actions' => ['captcha', 'about', 'index']
                            ],
                            [
                                'allow' => 'true',
                                'roles' => ['@']
                            ]
                        ]
                    ]
                ]
            );
        }
    }