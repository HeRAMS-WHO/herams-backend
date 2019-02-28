<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\controllers\workspace\Configure;

class WorkspaceController extends Controller
{
    public function actions()
    {
        return [
            'configure' => Configure::class,
            'view' => View::class
        ];
    }

}