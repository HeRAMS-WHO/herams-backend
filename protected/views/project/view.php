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

echo ProjectPageMenu::widget([
    'project' => $project,
    'collapsible' => false,
    'footer' => $this->render('//footer', ['projects' => Project::find()->all()]),
    'params' => Yii::$app->request->queryParams,
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

while (!empty($stack)) {
    /** @var PageInterface $p */
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
echo Html::beginTag('div', ['class' => 'content dashboard']);

foreach ($page->getChildElements() as $element) {
    Yii::beginProfile('Render element ' . $element->id);
    echo "<!-- Begin chart {$element->id} -->";
    $level = ob_get_level();
    ob_start();
    try {
        echo $element->getWidget($survey, $data, $page)->run();
        echo ob_get_clean();
    } catch (Throwable $t) {
        if (!YII_ENV_PROD) {
            throw $t;
        }
        while (ob_get_level() > $level) {
            ob_end_clean();
        }
        \Yii::error($t);
        echo Html::tag(
            'div',
            "Rendering this element caused an error: <strong>{$t->getMessage()}</strong>. The most common reason for the error is an invalid question code in its configuration. You can edit the element " . Html::a('here', ['/element/update', 'id' => $element->id]) . '.',
            [
                'class' => 'element',
                ]
        );
    }
    echo "<!-- End chart {$element->id} -->";
    Yii::endProfile('Render element ' . $element->id);
}

echo Html::endTag('div');
