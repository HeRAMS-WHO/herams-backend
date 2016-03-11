<?php
namespace prime\api\controllers;

use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\SerializeHelper;
use yii\caching\Cache;
use yii\web\HttpException;

class SurveysController extends Controller
{
    public function actionView(Client $limeSurvey, Cache $cache, $id, $language = null)
    {
        $cacheKey = __CLASS__ . __FUNCTION__ . "$id.$language";
        if (false === $survey = $cache->get($cacheKey)) {
            try {
                $survey = SerializeHelper::toArray($limeSurvey->getSurvey($id, $language));

            } catch (\Exception $e) {
                throw new HttpException(404, $e->getMessage());
            }
            $cache->set($cacheKey, $survey, 3600);
        }

        return $survey;


    }


}
