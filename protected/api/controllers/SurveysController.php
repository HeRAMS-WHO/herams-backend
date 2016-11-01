<?php
namespace prime\api\controllers;

use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\SerializeHelper;
use yii\caching\Cache;
use yii\helpers\ArrayHelper;
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

    public function behaviors()
    {


        $result = ArrayHelper::merge(parent::behaviors(), [
            'cache' => [
                'class' => \yii\filters\HttpCache::class,
                'lastModified' => function($action, $params) {
                            return 0;
                }
//                'only' => ['view'],
//                'etagSeed' => function ($action, $params) {
//                    $post = $this->findModel(\Yii::$app->request->get('id'));
//                    return serialize([$post->title, $post->content]);
//                },
            ],
            'access' => [
                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['*'],
//                        'actions' => ['view']
//                    ]
                ]
            ],

        ]);
//        vdd($result);
        return $result;
    }

}
