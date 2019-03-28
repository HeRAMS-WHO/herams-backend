<?php

/** @var View $this */
/** @var \prime\models\ar\Project $project */
/** @var \prime\models\ar\Page $page */

use prime\widgets\menu\ProjectPageMenu;
use yii\web\View;

echo ProjectPageMenu::widget([
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

if ($project->pages[0]->getId() !== $page->getId()) {
    $this->params['breadcrumbs'][] = [
        'label' => $page->getTitle(),
//    'url' => [
//        'project/view',
//        'id' => $project->id,
//        'page_id' => $page->getId(),
//        'parent_id' => $page->getParentId()
//    ]
    ];
}

$this->title = $page->getTitle();


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
        echo "<!-- Chart {$element->id} -->";
        try {
            echo $element->getWidget($survey, $data, $page)->run();
        } catch (\Throwable $t) {
            echo \yii\helpers\Html::tag('div', $t->getMessage(), [
                'class' => 'element',
                'style' => [
                    'white-space' => 'pre'
                ]
            ]);
        }
        \Yii::endProfile('Render element ' . $element->id);
    }

    echo \yii\helpers\Html::endTag('div');