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
    public function actionView(Client $limeSurvey, Cache $cache, $id, $code, $ind, $services=false)
    {
        try {
            $mapPoints = Overview::mapPoints($limeSurvey, $cache, $id, $code, $services);

            $points = [
                'results' => [
                    'type' => "map",
                    'config' => Overview::mapLegend($ind),
                    'hf_list' => $mapPoints,
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