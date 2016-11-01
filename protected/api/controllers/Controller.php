<?php


namespace prime\api\controllers;

use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

use yii\filters\auth\QueryParamAuth;
use yii\web\Response;

class Controller extends \yii\rest\ActiveController
{
    use ActionInjectionTrait;

    public function runAction($id, $params = [])
    {
        $bodyParams = app()->request->getBodyParams();
        $params = array_merge($params,  isset($bodyParams) ? $bodyParams : []);
        return parent::runAction($id, $params);
    }


    public function init() {
        // http://www.yiiframework.com/doc-2.0/guide-rest-authentication.html
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->loginUrl = null;
    }


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'optional' => ['*'],
            'authMethods' => [
                [
                    'class' => QueryParamAuth::class,
                    'except' => ['options']
                ],
                [
                    'class' => HttpBearerAuth::class,
                    'except' => ['options']
                ]
            ]
        ];
        $behaviors['access'] = [
             'class' => AccessControl::class,
             'rules' => [
                 [
                     'allow' => true,
                     'roles' => ['admin'],
                 ], [
                     'allow' => true,
                     'verbs' => ['OPTIONS']
                 ]
             ]
        ];
        return $behaviors;
    }

    public function actions()
    {
        return [];
    }



    public function actionOptions(Response $response)
    {
        $response->getHeaders()->add('Access-Control-Allow-Headers', 'authorization, accept, content-type');
        return;
    }

    protected function verbs()
    {
        return array_merge(parent::verbs(), [
            'options' => ['OPTIONS']
        ]);
    }


}