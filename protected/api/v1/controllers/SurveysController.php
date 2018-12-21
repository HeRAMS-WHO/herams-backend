<?php
namespace prime\api\v1\controllers;

use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\SerializeHelper;
use yii\caching\Cache;
use yii\web\HttpException;

class SurveysController extends Controller
{
    public function actionView(Client $limeSurvey, Cache $cache, $id)
    {
        $cacheKey = "STRUCTURE.$id";
        if (false === $survey = $cache->get($cacheKey)) {
            try {
                $survey = SerializeHelper::toArray($limeSurvey->getSurvey($id));
            } catch (\Exception $e) {
                throw new HttpException(404, $e->getMessage());
            }
            $cache->set($cacheKey, $survey, 3600);
        }
        return $survey;
    }

    public function behaviors()
    {

        $result = parent::behaviors();

        array_unshift($result['access']['rules'],
            [
                'allow' => true,
                'roles' => ['@'],
                'actions' => ['view']
            ]
        );
        return $result;
    }

}
