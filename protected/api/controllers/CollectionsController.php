<?php


namespace prime\api\controllers;


use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\SerializeHelper;
use yii\caching\Cache;

class CollectionsController extends Controller
{

    public function actionView(Client $limeSurvey, Cache $cache, $id)
    {
        $cacheKey = __CLASS__ . __FILE__ . $id;
        if (false === $responses = $cache->get($cacheKey)) {
            $responses = [];
            foreach($limeSurvey->getResponses($id) as $response) {
                $responses[] = $response->getData();
            }
            $cache->set($cacheKey, $responses, 3600);
        }
        return $responses;
    }
}