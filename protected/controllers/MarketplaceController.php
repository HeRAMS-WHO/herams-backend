<?php

namespace prime\controllers;

use app\components\Request;
use prime\components\Controller;
use prime\factories\MapLayerFactory;
use prime\models\ar\Project;
use prime\models\Country;
use prime\models\search\Report;
use prime\objects\ResponseCollection;
use SamIT\LimeSurvey\JsonRpc\Client;
use yii\helpers\ArrayHelper;

class MarketplaceController extends Controller
{
    public function actionMap(Client $limesurvey)
    {
        $mapLayerData = [
            'projects' => Project::find()->notClosed(),
            'countryGrades' => new ResponseCollection(), //$limesurvey->getResponses(486496),
            'eventGrades' => new ResponseCollection(), //$limesurvey->getResponses(473297),
            'healthClusters' => new ResponseCollection()
        ];
        return $this->render('map', ['mapLayerData' => $mapLayerData]);
    }

    public function actionList(Request $request)
    {
        $reportSearch = new Report();
        $reportsDataProvider = $reportSearch->search($request->queryParams);

        return $this->render('list', [
            'reportsDataProvider' => $reportsDataProvider,
            'reportSearch' => $reportSearch
        ]);
    }

    public function actionSummary($id, $layer)
    {
        $mapLayer = MapLayerFactory::get($layer);
        return $mapLayer->renderSummary($this->getView(), $id);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ],
                    ]
                ]
            ]
        );
    }
}