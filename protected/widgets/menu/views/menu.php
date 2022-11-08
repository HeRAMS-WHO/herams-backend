<?php

declare(strict_types=1);

use herams\common\models\Page;
use yii\helpers\Html;

/**
 * @var \herams\common\models\Project $project
 * @var Page $currentPage
 */

echo Html::beginTag('nav');
foreach ($project->mainPages as $page) {
    echo Html::beginTag('section', [
        'class' => 'expanded',
    ]);
    $link = count($page->children) > 0 ? [
        'projects/view',
        'id' => $project->id,
        'page_id' => $page->id,
    ] : '';
    echo Html::a($page->title, $link, [
        'style' => [
            'color' => 'red',
        ],
        'class' => [
            $currentPage->id === $page->id ? 'active' : '',
        ],
    ]);
    foreach ($page->children as $child) {
        echo Html::a($child->title, [
            'projects/view',
            'id' => $project->id,
            'page_id' => $child->id,
        ], [
            'class' => [
                $currentPage->id === $child->id ? 'active' : '',
            ],
        ]);
    }
    echo Html::endTag('section');
}
echo Html::endTag('nav');
