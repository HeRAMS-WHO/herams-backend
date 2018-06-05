<?php

namespace prime\api\controllers;

use SamIT\LimeSurvey\JsonRpc\Client;
use yii\caching\Cache;
use yii\web\HttpException;


class LocationsController extends Controller
{
    /**
     * Values for common filters.
     * In transition as we evaluate how front could do this automatically.
     */
    public function actionView($id)
    {
        try {
            $locations = \Yii::$app->db->createCommand('SELECT geo_id, geo_name, geo_level, parent_id FROM prime2_geography WHERE geo_id in (1,9) OR parent_id = 9')
                ->queryAll();

            $dates = \Yii::$app->db->createCommand('SELECT DISTINCT DATE(submit_date) as ddate FROM prime2_response_master ORDER BY ddate DESC')
                ->queryAll();

            $types = \Yii::$app->db->createCommand('SELECT option_label as label, option_code as code, option_color as color FROM `prime2_indicator_option` WHERE indicator_id=11')
                ->queryAll();

            $result = [
                'results' => [
                    'locations' => $locations,
                    'dates' => array_column($dates, 'ddate'),
                    'hf_types' => $types
                ],
            ];

            return $result;
        } catch (\Exception $ex) {
            throw new HttpException(404, $ex->getMessage());
        }
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