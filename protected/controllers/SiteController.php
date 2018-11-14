<?php
    namespace prime\controllers;
    use app\components\Html;
    use prime\components\Controller;
    use prime\components\JwtSso;
    use yii\captcha\CaptchaAction;
    use yii\filters\AccessControl;
    use yii\helpers\ArrayHelper;
    use yii\helpers\FileHelper;
    use yii\web\Response;

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

        public function actionTextImage(Response $response, $text)
        {
            $text = filter_var($text, FILTER_SANITIZE_STRING);
            $response->headers->set('Content-Type', FileHelper::getMimeTypeByExtension('.svg'));
            $response->format = Response::FORMAT_RAW;
            return Html::textImage($text);
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
                                'actions' => ['captcha', 'about', 'logout']
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
