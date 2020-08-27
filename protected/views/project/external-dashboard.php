<?php

/** @var View $this */
/** @var Project $project */
/** @var Page $page */

use prime\interfaces\PageInterface;
use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\widgets\menu\ProjectPageMenu;
use yii\helpers\Html;
use yii\web\View;

$this->title = $project->getDisplayField();
$this->params['breadcrumbs'][] = $this->title;
echo Html::tag('iframe', '', [
    'style' => [
        'grid-area' => 'main',
        'width' => '100%',
        'border' => 'none',
        'height' => '100%'
    ],
    'src' => $project->getOverride('dashboard'),
]);
