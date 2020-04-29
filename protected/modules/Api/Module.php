<?php
declare(strict_types=1);

namespace prime\modules\Api;

use yii\filters\ContentNegotiator;
use yii\web\GroupUrlRule;
use yii\web\Response;
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
                    'pattern' => '<controller:\w+>s',
                    'verb' => 'get',
                    'route' => '<controller>/index'
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller>/<id:\d+>',
                    'route' => '<controller>/view',
                    'verb' => 'get'
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller>/<id:\d+>/<action:\w+>/<target_id:\d+>',
                    'route' => '<controller>/<action>',
                    'verb' => ['put', 'delete']
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => '<controller>/<id:\d+>/<action:\w+>',
                    'route' => '<controller>/<action>',
                    'verb' => ['get']
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => 'response',
                    'route' => 'response/update',
                    'verb' => 'post'
                ],
                [
                    'class' => UrlRule::class,
                    'pattern' => 'response',
                    'route' => 'response/delete',
                    'verb' => 'delete'
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
                    'application/json' => Response::FORMAT_JSON,
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
