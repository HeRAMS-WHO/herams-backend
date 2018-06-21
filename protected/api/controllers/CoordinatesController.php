<?php

namespace prime\api\controllers;

use app\models\Overview;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\caching\Cache;
use yii\web\HttpException;


class CoordinatesController extends Controller
{
    /**
     * Predefined country status for world map
     */
    public function actionView(Client $limeSurvey, Cache $cache, $id, $pid, $code, $ind, $services=false)
    {
        try {

            $mapData = Overview::mapPoints($limeSurvey, $cache, $pid, $code, $ind, $services);

            $points = [
                'results' => [
                    'type' => "map",
                    'config' => $mapData['legend'],
                    'hf_list' => $mapData['points'],
                ],
            ];

            return $points;
        } catch (\Exception $ex) {
            throw new HttpException(404, $ex->getMessage());
        }
    }


    public function behaviors()
    {
        $result = parent::behaviors();

        array_unshift(
            $result['access']['rules'],
            [
                'allow' => true,
                'roles' => ['@'],
                'actions' => ['view']
            ]
        );

        return $result;
    }

}
