<?php
declare(strict_types=1);

namespace prime\modules\Api\controllers;

use prime\modules\Api\controllers\project\Index;
use prime\modules\Api\controllers\project\Summary;
use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\web\Controller;

class ProjectController extends Controller
{
    use ActionInjectionTrait;

    public function actions()
    {
        return [
            'index' => Index::class,
            'summary' => Summary::class
        ];
    }
}
