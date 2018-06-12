<?php
namespace prime\api\controllers;

use app\models\Overview;
use app\models\stats\ServiceAvailability;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\caching\Cache;


class ChartsController extends Controller
{
    /**
     * Predefined country status for world map
     */
    public function actionView(Client $limeSurvey, Cache $cache, $id, $cid, $services=false)
    {
        $data = [];

        if (!$services) {
            // get list of charts in category
            $charts = \Yii::$app->db->createCommand(
                'SELECT * FROM prime2_category_chart cc join prime2_indicator ii on cc.indicator_id = ii.id WHERE category_id=:id'
            )
                ->bindValue(':id', $cid)
                ->queryAll();

            $responses = Overview::loadResponses($cache, $id, Overview::filters());

            // Add formatted values to result data
            foreach ($charts as $chart) {
                $data[] = Overview::formatChart($chart, $responses);
            }
        } else {
            // Get service availability for given services
            $sa = new ServiceAvailability();
            $data = $sa->status($limeSurvey, $cache, $services, $id);
        }
        return $data;
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

