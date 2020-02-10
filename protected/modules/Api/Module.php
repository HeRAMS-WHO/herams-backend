<?php
declare(strict_types=1);

namespace prime\modules\Api;


use yii\base\BootstrapInterface;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\GroupUrlRule;
use yii\web\Response;
use yii\web\UrlManager;
use yii\web\UrlRule;

class Module extends \yii\base\Module
{
    public static function urlRule(): array
    {
        return [
            'class' => GroupUrlRule::class,
            'prefix' => 'api/',
            'rules' => [
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller>/<id:\d+>',
                    'route' => '<controller>/view',
                    'verb' => 'get'
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => 'response/update',
                    'route' => 'response/update',
                    'verb' => 'post'
                ],
                [
                    'pattern' => '<p:.*>',
                    'route' => '',
                ]
            ]
        ];
    }

    public function behaviors()
    {
        return [
//            HttpBearerAuth::class => [
//                'class' => HttpBearerAuth::class,
//                'user' => $this->get('user'),
//            ],
            ContentNegotiator::class => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    Response::FORMAT_JSON,
                    Response::FORMAT_XML,

                ]
            ]
        ];
    }

    public function createControllerByID($id)
    {
        $result = parent::createControllerByID($id);
        if ($result instanceof \yii\web\Controller) {
            $result->enableCsrfValidation = false;
        }
        return $result;
    }


    public function beforeAction($action)
    {
        if (\Yii::$app->has('session', true)) {
            throw new \Exception('Session already instantiated, this should not happen');
        }
        \Yii::$app->request->enableCsrfCookie = false;
        return parent::beforeAction($action);
    }
}