<?php

namespace prime\controllers;

use app\components\Request;
use prime\components\Controller;
use prime\models\Country;
use prime\models\search\Report;

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

    public function actionSummary($iso_3, $layer)
    {
        $country = Country::findOne($iso_3);
        switch($layer) {
            case 'Reports':
                return $this->render('summaries/reports', [
                    'country' => $country
                ]);
                break;
        }
    }
}