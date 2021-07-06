<?php
declare(strict_types=1);

use prime\interfaces\PageInterface;
use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\widgets\menu\ProjectPageMenu;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var Project $project */
/** @var Page $page */

$this->title = $project->getDisplayField();

echo Html::tag('iframe', '', [
    'style' => [
        'grid-area' => 'main',
        'width' => '100%',
        'border' => 'none',
        'height' => '100%'
    ],
    'src' => $project->getOverride('dashboard'),
]);
