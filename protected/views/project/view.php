<?php

declare(strict_types=1);

use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\widgets\menu\ProjectPageMenu;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var Project $project
 * @var Page $page
 * @var \SamIT\LimeSurvey\Interfaces\SurveyInterface $survey
 * @var \prime\models\forms\ResponseFilter $filterModel
 * @var array $data
 * @var array $types
 */

echo ProjectPageMenu::widget([
    'project' => $project,
    'collapsible' => false,
    'footer' => $this->render('//footer'),
    'params' => Yii::$app->request->queryParams,
    'currentPage' => $page,
    'survey' => $survey,

]);

$this->title = \Yii::t('app.pagetitle', $page->getTitle());

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
    echo "<!-- Begin element {$element->id} -->";
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
    echo "<!-- End element {$element->id} -->";
    Yii::endProfile('Render element ' . $element->id);
}

echo Html::endTag('div');
