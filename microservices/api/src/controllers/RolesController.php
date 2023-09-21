<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\api\controllers\roles\Index;

use yii\web\Request;
use yii\web\Response;

final class RolesController extends Controller
{
    public function actions()
    {
        return [
            'index' => Index::class
        ];
    }
}
