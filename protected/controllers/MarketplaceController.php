<?php

namespace prime\controllers;

use prime\components\Controller;

class MarketplaceController extends Controller
{
    public function actionMap()
    {
        return $this->render('map');
    }
}