<?php


namespace prime\api\v1\controllers;


use prime\models\ar\Workspace;
use prime\models\ar\Project;
use yii\caching\Cache;

class CollectionsController extends Controller
{

    public function actionView(Cache $cache, $id, $entity = 'project'
    )
    {
        $cacheKey = __CLASS__ . __FILE__ . $id . $entity;
        if (false === $responses = $cache->get($cacheKey)) {
            $responses = [];

            switch ($entity) {
                case 'project':
                    $data = Workspace::loadOne($id)->getResponses();
                    break;
                case 'tool':
                    $data = Project::loadOne($id)->getResponses();
            }

            foreach($data as $response) {
                $responses[] = $response->getData();
            }
            $cache->set($cacheKey, $responses, 120);
        }
        return $responses;
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