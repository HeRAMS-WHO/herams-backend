<?php

/** @var View $this */
/** @var \prime\models\ar\Tool $project */
/** @var \prime\models\ar\Page $page */

use yii\web\View;

echo \prime\widgets\menu\Menu::widget([
    'project' => $project,
    'currentPage' => $page
]);

echo $this->render('view/filters', [
    'types' => $types,
    'survey' => $survey,
    'project' => $project,
    'filterModel' => $filterModel
]);
echo \yii\helpers\Html::beginTag('div', ['class' => 'content']);
    foreach($page->elements as $element) {
        echo $element->getWidget($survey, $data)->run();
    }

    echo \yii\helpers\Html::endTag('div');