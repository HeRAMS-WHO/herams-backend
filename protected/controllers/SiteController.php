<?php
    namespace prime\controllers;
    use app\components\Html;
    use prime\components\Controller;
    use prime\components\JwtSso;
    use yii\captcha\CaptchaAction;
    use yii\filters\AccessControl;
    use yii\helpers\ArrayHelper;

    class SiteController extends Controller
    {
        public function actionLimeSurvey(
            JwtSso $limesurveySSo,
            ?string $error = null
        ) {
            if (isset($error)) {
                echo Html::tag('pre', htmlentities($error));
                return;
            }
            $limesurveySSo->loginAndRedirectCurrentUser();
        }

        public function actionLogout()
        {	
            $this->layout = 'logout';	
            return $this->render('logout');	
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
                                'actions' => ['captcha', 'logout']
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
