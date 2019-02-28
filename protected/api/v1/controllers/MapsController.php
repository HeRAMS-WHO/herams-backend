<?php


namespace prime\api\v1\controllers;


use prime\models\ar\Project;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MapsController extends Controller
{

    public function actionView(Response $response, $id)
    {
        $base = \Yii::getAlias('@app/data/countryPolygons');
        $options = scandir($base);


//        if (isset($options[$id])
//            && substr_compare($options[$id], '.json', -5, 5) === 0
//        ) {
//            $json = file_get_contents("$base/{$options[$id]}");
//            if ($response->format === Response::FORMAT_JSON) {
//                $response->content = $json;
//            } else {
//                $response->data = json_decode($json, true);
//            }
//
//            return $response;
//        } else
        if (null !== $tool = Project::findOne(['id' => $id])) {
            if (!empty($tool->explorer_map)) {
                $data = $tool->explorer_map;
            } else {
                $data = file_get_contents("$base/" . end($options));
            }
            if ($response->format === Response::FORMAT_JSON) {
                $response->content = $data;
            } else {
                $response->data = json_decode($data, true);
            }
        } else {
            throw new NotFoundHttpException("Polygons file for tool with id $id not found");
        }

//        $cacheKey = __CLASS__ . __FILE__ . $id . $entity;
//        if (false === $responses = $cache->get($cacheKey)) {
//            $responses = [];
//
//            switch ($entity) {
//                case 'project':
//                    $data = Project::loadOne($id)->getResponses();
//                    break;
//                case 'tool':
//                    $data = Tool::loadOne($id)->getResponses();
//            }
//
//            foreach($data as $response) {
//                $responses[] = $response->getData();
//            }
//            $cache->set($cacheKey, $responses, 3600);
//        }
//        return $responses;
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
//            'cache' => [
//                'class' => \yii\filters\HttpCache::class,
//                'lastModified' => function(\yii\base\Action $action, $params) {
//                    return 0;
//                }
//                'only' => ['view'],
//                'etagSeed' => function ($action, $params) {
//                    $post = $this->findModel(\Yii::$app->request->get('id'));
//                    return serialize([$post->title, $post->content]);
//                },
//            ],
        return $result;
    }
}