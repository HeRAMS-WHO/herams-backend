<?php

declare(strict_types=1);

use prime\models\ar\Page;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var \prime\models\project\ProjectForExternalDashboard $project */
/** @var Page $page */

$this->title = $project->getDisplayField();

echo Html::tag('iframe', '', [
    'style' => [
        'grid-area' => 'main',
        'width' => '100%',
        'border' => 'none',
        'height' => '100%'
    ],
    'src' => $project->getExternalUrl(),
]);
