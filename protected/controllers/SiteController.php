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
        public $defaultAction = 'about';
        public function actionAbout()
        {
            return $this->render('about');
        }
        public function actionIndex(User $user, Session $session)
        {
            /** @var \prime\models\ar\User $identity */
            $identity = $user->identity;
            if ($identity->getProjects()->count() > 0) {
                return $this->redirect('projects/list');
            } else {
                $session->setFlash('info', \Yii::t('app', "We noticed you do not have any projects yet. We redirected you to the new project page so you can get started ASAP!"));
                return $this->redirect('projects/new');
            }
            return $this->render('index');
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
                                'actions' => ['captcha', 'about']
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