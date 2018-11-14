<?php

namespace prime\api\controllers;

use app\models\ar\Category;
use app\models\ar\CountryStatus;


class CountriesController extends Controller
{
    /**
     * Predefined country status for world map
     */
    public function actionView($id)
    {
        $page = Category::findOne($id);
        $countries = CountryStatus::find()->asArray()->all();

        $home = json_decode(str_replace('#countries#', json_encode($countries), $page->json_template));
        $user = app()->user->identity;
        $home->results->userinfo = [
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getUsername()
        ];

        return $home;
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
