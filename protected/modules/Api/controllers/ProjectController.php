<?php

declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\project\Index;
use prime\modules\Api\controllers\project\Summary;
use yii\web\Controller;

class ProjectController extends Controller
{
    public function actions(): array
    {
        return [
            'index' => Index::class,
            'summary' => Summary::class
        ];
    }
}
