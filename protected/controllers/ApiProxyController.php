<?php

declare(strict_types=1);

namespace prime\controllers;

use herams\common\values\UserId;
use prime\components\ApiProxy;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

final class ApiProxyController extends Controller
{

    public function behaviors(): array
    {
        return [
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        ];
    }
    public function beforeAction($action): bool
    {
//        foreach(\Yii::$app->log->targets as $target){
//            $target->setEnabled(false);
//        }
        return parent::beforeAction($action);
    }

    public function actionCore(
        Response $response,
        ApiProxy $apiProxy,
        \yii\web\User $user,
        Request $request
    ): Response {
        $userId = new UserId($user->getId());
        session_abort();
        header_remove();
        \Yii::beginProfile('request');
        $upstreamResponse = $apiProxy->forwardRequestToCore($request, $userId);
        \Yii::endProfile('request');
        $response->data = $upstreamResponse->getBody()->getContents();
        $response->format = Response::FORMAT_RAW;
        $headers = $response->getHeaders();
        $headers->fromArray($upstreamResponse->getHeaders());

        if (intdiv($upstreamResponse->getStatusCode(), 100) !== 2) {
            \Yii::warning("Upstream status code: {$upstreamResponse->getStatusCode()}");
            \Yii::warning('Upstream headers: ' . print_r($headers->toOriginalArray(), true));
            \Yii::error("Upstream response: {$response->data}");
        }
        $response->setStatusCode($upstreamResponse->getStatusCode());

        return $response;
    }
}
