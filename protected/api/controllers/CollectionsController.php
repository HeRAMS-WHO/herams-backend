<?php


namespace prime\api\controllers;


use prime\models\ar\Project;
use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\SerializeHelper;
use yii\caching\Cache;
use yii\helpers\ArrayHelper;

class CollectionsController extends Controller
{

    public function actionView(Client $limeSurvey, Cache $cache, $id)
    {
        $cacheKey = __CLASS__ . __FILE__ . $id;
        if (false === $responses = $cache->get($cacheKey)) {
            $responses = [];
            $project = Project::loadOne($id);
//            vdd($project);

            foreach($project->getResponses() as $response) {
                $responses[] = $response->getData();
            }
            $cache->set($cacheKey, $responses, 3600);
        }
        return $responses;
    }

    public function behaviors()
    {


        $result = ArrayHelper::merge([
            'access' => [
                'rules' => [
                    [
                        'allow' => true,
//                        'roles' => ['*'],
                        'actions' => ['view']
                    ]
                ]
            ],
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
        ], parent::behaviors());
        return $result;
    }
}