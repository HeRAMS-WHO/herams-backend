<?php

/** @var View $this */
/** @var \prime\models\ar\Tool $project */
/** @var \prime\models\ar\Page $page */

use yii\web\View;

echo \prime\widgets\menu\Menu::widget([
    'project' => $project,
    'params' => \Yii::$app->request->queryParams,
    'currentPage' => $page,
    'survey' => $survey,

]);

$this->params['breadcrumbs'] = [
    [
        'label' => $project->getDisplayField(),
        'url' => ['project/view', 'id' => $project->id]
    ],
];

// Get page stack.
$stack = [];
$parent = $page;
while (null !== ($parent = $parent->getParentPage())) {
    $stack[] = $parent;
}

while(!empty($stack)) {
    /** @var \prime\interfaces\PageInterface $p */
    $p = array_pop($stack);
    $this->params['breadcrumbs'][] = [
        'label' => $p->getTitle(),
    ];
}


$this->params['breadcrumbs'][] = [
    'label' => $page->getTitle(),
    'url' => [
        'project/view',
        'id' => $project->id,
        'page_id' => $page->getId(),
        'parent_id' => $page->getParentId()
    ]
];

echo $this->render('view/filters', [
    'types' => $types,
    'survey' => $survey,
    'project' => $project,
    'filterModel' => $filterModel,
    'data' => $data
]);
echo \yii\helpers\Html::beginTag('div', ['class' => 'content']);

    foreach($page->getChildElements() as $element) {
        \Yii::beginProfile('Render element ' . $element->id);
        echo $element->getWidget($survey, $data, $page)->run();
        \Yii::endProfile('Render element ' . $element->id);
    }

    echo \yii\helpers\Html::endTag('div');