<?php

namespace prime\controllers;

use app\components\Request;
use prime\components\Controller;
use prime\factories\MapLayerFactory;
use prime\models\ar\Project;
use prime\models\Country;
use prime\models\search\Report;
use yii\helpers\ArrayHelper;

class MarketplaceController extends Controller
{
    public function actionMap()
    {
        return $this->render('map');
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