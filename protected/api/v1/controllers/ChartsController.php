<?php
namespace prime\api\v1\controllers;

use app\models\Overview;
use app\models\stats\ServiceAvailability;
use prime\models\ar\Tool;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\caching\Cache;


class ChartsController extends Controller
{
    /**
     * Predefined country status for world map
     */
    public function actionView(Client $limeSurvey, Cache $cache, $id, $pid, $cid, $services=false)
    {
        $data = [];

        if (!$services) {
            $model = Tool::loadone($pid);

            // get list of charts in category
            $charts = \Yii::$app->db->createCommand(
                'SELECT * FROM prime2_category_chart cc join prime2_indicator ii on cc.indicator_id = ii.id WHERE category_id=:id ORDER BY cc.display_order ASC'
            )
                ->bindValue(':id', $cid)
                ->queryAll();

            $structure = Overview::loadStructure($limeSurvey, $cache, $model->base_survey_eid);
            $responses = Overview::loadResponses($cache, $pid, Overview::filters());

            // Add formatted values to result data
            foreach ($charts as $chart) {
                $labels = Overview::questionLabels($structure, $chart['query']);
                $data[] = Overview::formatChart($chart, $responses, $labels);
            }
        } else {
            // Get service availability for given services
            $sa = new ServiceAvailability();
            $data = $sa->status($limeSurvey, $cache, $services, $pid);
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

