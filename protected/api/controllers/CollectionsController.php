<?php


namespace prime\api\controllers;


use prime\models\ar\Project;
use prime\models\ar\Tool;
use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\SerializeHelper;
use yii\caching\Cache;
use yii\helpers\ArrayHelper;

class CollectionsController extends Controller
{

    public function actionView(Client $limeSurvey, Cache $cache, $id, $entity = 'project')
    {
        $cacheKey = __CLASS__ . __FILE__ . $id . $entity;
        if (false === $responses = $cache->get($cacheKey)) {
            $responses = [];

            switch ($entity) {
                case 'project':
                    $data = Project::loadOne($id)->getResponses();
                    break;
                case 'tool':
                    $data = Tool::loadOne($id)->getResponses();
            }

            foreach($data as $response) {
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