<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\controllers\admin\Dashboard;

class AdminController extends Controller
{
    public $defaultAction = 'dashboard';
    public $layout = 'css3-grid';
    public function actions()
    {
        return [
            'dashboard' => Dashboard::class
        ];
    }


}