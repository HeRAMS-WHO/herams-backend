<?php

namespace prime\controllers;

use app\components\Request;
use prime\components\Controller;
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
        switch($layer) {
            case 'reports':
                $country = Country::findOne($id);
                return $this->render('summaries/reports', [
                    'country' => $country
                ]);
                break;
            case 'projects':
                $project = Project::findOne($id);
                return $this->render('summaries/projects', [
                    'project' => $project
                ]);
                break;
        }
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