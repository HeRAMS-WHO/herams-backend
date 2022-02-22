<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\project\Index;
use yii\web\Controller;

class FavoriteController extends Controller
{
    public function actions()
    {
        return [
            'index' => Index::class,
        ];
    }
}
