<?php

declare(strict_types=1);

use herams\common\models\Page;
use herams\common\models\Project;
use prime\interfaces\DashboardWidgetInterface;
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
 * @var \prime\helpers\HeramsVariableSet $variables
 */

echo ProjectPageMenu::widget([
    'project' => $project,
    'collapsible' => false,
    'footer' => $this->render('//footer'),
    'params' => Yii::$app->request->queryParams,
    'currentPage' => $page,
]);

$this->title = \Yii::t('app.pagetitle', $page->getTitle());
echo Html::beginTag('div', [
    'class' => 'content dashboard',
]);

foreach ($page->getChildElements() as $element) {
    Yii::beginProfile('Render element ' . $element->id);
    echo "<!-- Begin element {$element->id} -->";
    if ($element instanceof DashboardWidgetInterface) {
        try {
            $element->renderWidget($variables, $this, $data);
        } catch (Throwable $t) {
            if (! YII_ENV_PROD) {
                throw $t;
            }
            \Yii::error($t);
            echo Html::tag(
                'div',
                "Rendering this element caused an error: <strong>{$t->getMessage()}</strong>. The most common reason for the error is an invalid question code in its configuration. You can edit the element " . Html::a('here', [
                    '/element/update',
                    'id' => $element->id,
                ]) . '.',
                [
                    'class' => 'element',
                ]
            );
        }
    }
    echo "<!-- End element {$element->id} -->";
    Yii::endProfile('Render element ' . $element->id);
}

echo Html::endTag('div');
