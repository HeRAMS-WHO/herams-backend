<?php

namespace prime\controllers;

use prime\components\Controller;
use prime\models\Country;

class MarketplaceController extends Controller
{
    public function actionMap()
    {
        return $this->render('map');
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