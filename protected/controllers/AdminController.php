<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\controllers\admin\Dashboard;
use prime\controllers\admin\Limesurvey;

class AdminController extends Controller
{
    public $defaultAction = 'dashboard';
    public $layout = 'admin';
    public function actions()
    {
        return [
            'dashboard' => Dashboard::class,
            'limesurvey' => Limesurvey::class
        ];
    }


}