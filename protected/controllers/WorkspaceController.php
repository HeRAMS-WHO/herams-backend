<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\controllers\workspace\Configure;
use prime\controllers\workspace\Limesurvey;
use prime\controllers\workspace\Update;

class WorkspaceController extends Controller
{
    public $layout = '//admin';
    public function actions()
    {
        return [
            'configure' => Configure::class,
            'limesurvey' => Limesurvey::class,
            'update' => Update::class
        ];
    }

}