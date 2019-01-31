<?php


namespace prime\controllers;


use prime\components\Controller;
use prime\controllers\page\Update;

class PageController extends Controller
{
    public function actions()
    {
        return [
            'update' => Update::class
        ];
    }
}