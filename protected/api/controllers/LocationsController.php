<?php

namespace prime\api\controllers;

use prime\models\ar\Tool;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\caching\Cache;
use yii\web\HttpException;
use app\models\Overview;


class LocationsController extends Controller
{
    /**
     * Values for common filters.
     * In transition as we evaluate how front could do this automatically.
     */
    public function actionView(Client $limeSurvey, Cache $cache, $id, $pid)
    {
        try {
            $model = Tool::loadone($pid);
            $labels = Overview::geoLabels($limeSurvey, $cache, $model->base_survey_eid);

            if ($pid == 10) {
                $locations = \Yii::$app->db->createCommand('SELECT geo_id, geo_name, geo_level, parent_id FROM prime2_geography WHERE geo_id in (1,3,9,37) OR parent_id in (3, 9, 37)')
                    ->queryAll();
                $types = \Yii::$app->db->createCommand('SELECT option_label as label, option_code as code, option_color as color FROM `prime2_indicator_option` WHERE indicator_id=11')
                    ->queryAll();
            } else {
                $locations = \Yii::$app->db->createCommand('SELECT geo_id, geo_name, geo_level, parent_id FROM prime2_geography WHERE geo_id  >= 300')
                    ->queryAll();
                $types = \Yii::$app->db->createCommand('SELECT option_label as label, option_code as code, option_color as color FROM `prime2_indicator_option` WHERE indicator_id=22')
                    ->queryAll();
            }

            $hfTypes = [];
            foreach ($types as $hfType) {
                if (isset($labels['types'][$hfType['code']])) {
                    $hfType['label'] = $labels['types'][$hfType['code']];
                    $hfTypes[] = $hfType;
                }
            }

            $result = [
                'results' => [
                    'locations' => [
                        'locations_types_labels' => $labels['location'],
                        'locations_list' => $locations
                    ],
                    'dates' => date('Y-m-d'),
                    'hf_types' => $hfTypes
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
