<?php
declare(strict_types=1);

namespace prime\controllers;

use prime\components\ApiProxy;
use prime\components\Controller;
use Psr\Http\Message\ResponseInterface;
use yii\web\Request;
use yii\web\Response;
use yii\web\Session;

class ApiProxyController extends Controller
{
    public function beforeAction($action): bool
    {


        foreach(\Yii::$app->log->targets as $target){
//            $target->setEnabled(false);
        }
        return parent::beforeAction($action);
    }

    public function actionCore(
        Response $response,
        ApiProxy $apiProxy,
        \yii\web\User $user,
        Request $request): Response
    {
        $identity = $user->getIdentity();
        session_abort();
        \Yii::beginProfile('request');
        $upstreamResponse =  $apiProxy->forwardRequestToCore($request, $identity);
        $response->data = $upstreamResponse->getBody()->getContents();
        $response->getHeaders()->fromArray($upstreamResponse->getHeaders());
        $response->setStatusCode($upstreamResponse->getStatusCode());
        \Yii::endProfile('request');
        return $response;

    }

}
